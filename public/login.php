<?php
require __DIR__.'/bootstrap.php';;
include __DIR__ . '/database.php';

function elog($m){
    $msg = str_replace(["\r","\n"], ' ', (string)$m);
    @file_put_contents('php://stderr', "[app] $msg\n");
}
function header_log($k,$v){
    if ((getenv('APP_DEBUG_LOG') ?? '0') === '1') {
        $k = preg_replace('/[^A-Za-z0-9_-]/','',$k);
        $v = str_replace(["\r","\n"], ' ', (string)$v);
        header('X-App-'.$k.': '.substr($v,0,180));
    }
}
ini_set('log_errors','1');
ini_set('error_log','php://stderr');

/* ===== redirect-based error handling (fallback als bootstrap er niet is) ===== */
if (!function_exists('redirect_fail')) {
    function new_error_id(){ return strtoupper(bin2hex(random_bytes(4))); }
    function redirect_fail(string $ctx, $details = null, int $http = 302){
        if ($details === null && function_exists('sqlsrv_errors')) $details = sqlsrv_errors();
        $id = new_error_id();
        @file_put_contents('php://stderr', "[app] FAIL[$id][$ctx] ".json_encode($details, JSON_UNESCAPED_SLASHES)."\n");
        $to = '/error.php?code='.rawurlencode($id);
        if (!headers_sent()) { header('Location: '.$to, true, $http); exit; }
        echo '<!doctype html><meta charset="utf-8"><script>location.href='.json_encode($to).'</script>';
        exit;
    }
}

$role  = $_SESSION['user_role'] ?? 'onbekend';
$error = '';

elog('BOOT login '.($_SERVER['REQUEST_METHOD'] ?? 'CLI'));
header_log('Boot','login');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['emailaddress'] ?? ($_POST['emailadress'] ?? ''));
    $password = trim($_POST['password']     ?? '');

    header_log('email', $email ?: '(empty)');
    elog('LOGIN post email=' . ($email ?: '(empty)'));

    if ($email === '' || $password === '') {
        $error = 'Vul zowel je e-mailadres als wachtwoord in.';
        elog('LOGIN missing-fields');
    } else {
        $sql    = 'SELECT id, user_email, [password], role FROM dbo.Users WHERE user_email = ?';
        $params = [ &$email ];
        $stmt   = sqlsrv_prepare($conn, $sql, $params);

        if ($stmt === false) {
            redirect_fail('LOGIN_PREP', sqlsrv_errors(), 302);
        }
        if (!sqlsrv_execute($stmt)) {
            redirect_fail('LOGIN_EXEC', sqlsrv_errors(), 302);
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        elog('LOGIN found='.($row?1:0));

        if ($row) {
            $passOk = password_verify($password, $row['password']);
            elog('LOGIN password_ok=' . ($passOk ? 1 : 0));
            if ($passOk) {
                session_regenerate_id(true);
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id']        = $row['id'];
                $_SESSION['user_email']     = $row['user_email'];
                $_SESSION['user_role']      = $row['role'];

                if ($stmt) sqlsrv_free_stmt($stmt);
                sqlsrv_close($conn);

                header('Location: /index.php', true, 302);
                exit;
            } else {
                $error = 'Ongeldige combinatie e-mailadres/wachtwoord.';
            }
        } else {
            $error = 'Geen account gevonden met dit e-mailadres.';
        }

        if ($stmt) sqlsrv_free_stmt($stmt);
    }
}

sqlsrv_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PinterPal</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .form-container {
            background-color: #0a7082;
            color: #000;
            padding: 2vw;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            border: 2px solid #ffc107;
            border-radius: 5px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 400px;
            margin: 5vw auto;
        }
        .form-container input {
            width: 80%;
            max-width: 350px;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 2px solid #0a7082;
            font-size: 1rem;
        }
        .password-container {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
            justify-content: center;
        }
        .password-container input {
            flex: 1;
            max-width: 350px;
        }
        .password-toggle {
            position: absolute;
            right: 15%;
            cursor: pointer;
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }
        .password-toggle:hover {
            transform: scale(1.2);
        }
        .form-container button {
            background-color: #8c52ff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 1vw;
            text-transform: uppercase;
            font-weight: bold;
        }
        .form-container button:hover {
            background-color: #7ae614;
        }
        .form-container a {
            color: #ffc107;
            font-weight: bold;
            text-decoration: none;
            margin-top: 15px;
            display: block;
        }
        .form-container a:hover {
            color: #7ae614;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">
            <h1>PINTERPAL</h1>
        </a>
        <div class="login-signup">
            <img src="img/pinterpal-header.png" alt="PinterPal Logo" class="header-logo">
        </div>
    </div>

    <div class="form-container">
        <h2>Login to PinterPal</h2>

        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <form method="post" action="login.php">
            <input type="text" name="emailaddress" placeholder="Email Address / Username" required>
            <div class="password-container">
                <input type="password" name="password" placeholder="Password" required>
                <span class="password-toggle" onclick="togglePassword(this)">👁️</span>
            </div>
            <button type="submit">Log In</button>
        </form>
        <a href="register.php">Don't have an account? Sign up here</a>
    </div>

    <script>
        function togglePassword(element) {
            const passwordInput = element.previousElementSibling;
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                element.textContent = "🙈";
            } else {
                passwordInput.type = "password";
                element.textContent = "👁️";
            }
        }
    </script>
</body>
</html>
