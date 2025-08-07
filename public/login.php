<?php
session_start();

include __DIR__ . '/database.php';

$role  = $_SESSION['user_role'] ?? 'onbekend';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['emailaddress'] ?? '');
    $password = trim($_POST['password']     ?? '');

    if ($email === '' || $password === '') {
        $error = "Vul zowel je e-mailadres als wachtwoord in.";
    } else {
        $sql    = "SELECT id, user_email, password, role FROM users WHERE user_email = ?";
        $params = [$email];
        $stmt   = sqlsrv_prepare($conn, $sql, $params);

        if (!$stmt || !sqlsrv_execute($stmt)) {
            die(print_r(sqlsrv_errors(), true));
        }

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        if ($row) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id']        = $row['id'];
                $_SESSION['user_email']     = $row['user_email'];
                $_SESSION['user_role']      = $row['role'];

                sqlsrv_free_stmt($stmt);
                sqlsrv_close($conn);

                header("Location: index.php");
                exit;
            } else {
                $error = "Ongeldige combinatie e-mailadres/wachtwoord.";
            }
        } else {
            $error = "Geen account gevonden met dit e-mailadres.";
        }

        sqlsrv_free_stmt($stmt);
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

    <!-- Login Form -->
    <div class="form-container">
        <h2>Login to PinterPal</h2>

        <!-- Toon foutmelding -->
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
                element.textContent = "🙈"; // Change emoji to indicate "hide password"
            } else {
                passwordInput.type = "password";
                element.textContent = "👁️"; // Change emoji back to indicate "show password"
            }
        }
    </script>
</body>
</html>
