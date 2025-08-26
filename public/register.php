<?php
session_start();
include __DIR__ . '/database.php';

// --- logging helpers ---
function elog($m)
{
    $msg = str_replace(["\r", "\n"], ' ', (string) $m);
    @file_put_contents('php://stderr', "[app] $msg\n");
    if ((getenv('APP_DEBUG_LOG') ?? '0') === '1') {
        header('X-App-Log: ' . substr($msg, 0, 180));
    }
}
function hlog_post()
{
    if ((getenv('APP_DEBUG_LOG') ?? '0') === '1') {
        foreach ($_POST as $k => $v) {
            if (is_array($v))
                $v = json_encode($v);
            $k = preg_replace('/[^A-Za-z0-9_-]/', '', $k);
            $v = str_replace(["\r", "\n"], ' ', (string) $v);
            header('X-App-P-' . $k . ': ' . substr($v, 0, 120));
        }
    }
}

elog('BOOT register ' . ($_SERVER['REQUEST_METHOD'] ?? 'CLI'));
if ($_SERVER['REQUEST_METHOD'] === 'POST')
    hlog_post();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accountType = $_POST['account-type'] ?? '';
    if ($accountType === 'company') {
        elog('REDIRECT company-registration');
        header('Location: /company-registration.php');
        exit;
    }

    $email = trim($_POST['user_email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $username = trim($_POST['username'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $ageRaw = $_POST['age'] ?? '';
    $age = ($ageRaw === '' ? null : (int) $ageRaw);
    $role = 'gebruiker';

    if ($email === '' || $password === '' || $confirm === '' || $age === null) {
        $error = 'Vul alle verplichte velden in.';
        elog('VALIDATION fail email/psw/age');
    } elseif ($password !== $confirm) {
        $error = 'Wachtwoorden komen niet overeen.';
        elog('VALIDATION passwords_mismatch');
    } else {
        // 1) Bestaat e-mailadres al?
        $sqlCheck = "SELECT 1 FROM dbo.Users WHERE user_email = ?";
        $stmtCheck = sqlsrv_prepare($conn, $sqlCheck, [&$email]);
        if ($stmtCheck === false) {
            elog('prepare check FAILED: ' . print_r(sqlsrv_errors(), true));
            $error = 'Database fout (prepare check).';
        } elseif (!sqlsrv_execute($stmtCheck)) {
            elog('execute check FAILED: ' . print_r(sqlsrv_errors(), true));
            $error = 'Database fout (execute check).';
        } else {
            $exists = sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_NUMERIC) ? true : false;
            sqlsrv_free_stmt($stmtCheck);
            elog('CHECK exists=' . ($exists ? 1 : 0));

            if ($exists) {
                $error = 'Dit e-mailadres is al in gebruik. Probeer opnieuw.';
            } else {
                // 2) Insert (code-only fix met computed id + transactie)
                $hashed = password_hash($password, PASSWORD_DEFAULT);

                // start transactie (atomic + voorkomt race conditions)
                sqlsrv_begin_transaction($conn);

                $sqlIns = "
INSERT INTO dbo.Users (id, user_email, [password], username, address, city, gender, age, role)
OUTPUT INSERTED.id
SELECT ISNULL(MAX(id),0)+1, ?,?,?,?,?,?,?,?
FROM dbo.Users WITH (TABLOCKX, HOLDLOCK);
";
                $paramsIns = [&$email, &$hashed, &$username, &$address, &$city, &$gender, &$age, &$role];
                $stmtIns = sqlsrv_prepare($conn, $sqlIns, $paramsIns);

                if ($stmtIns === false) {
                    elog('prepare insert FAILED: ' . print_r(sqlsrv_errors(), true));
                    sqlsrv_rollback($conn);
                    $error = 'Database fout (prepare insert).';
                } elseif (!sqlsrv_execute($stmtIns)) {
                    elog('execute insert FAILED: ' . print_r(sqlsrv_errors(), true));
                    sqlsrv_rollback($conn);
                    $error = 'Fout bij aanmaken account.';
                } else {
                    // haal nieuwe id uit OUTPUT INSERTED.id
                    $out = sqlsrv_fetch_array($stmtIns, SQLSRV_FETCH_NUMERIC);
                    $userId = $out[0] ?? null;
                    sqlsrv_free_stmt($stmtIns);

                    // commit
                    sqlsrv_commit($conn);

                    // 3) Sessions + redirect
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_id'] = $userId;
                    $_SESSION['user_role'] = $role;

                    elog("REGISTER OK email=$email id=" . ($userId ?? 'null'));
                    sqlsrv_close($conn);
                    header("Location: /payment.php?user_id={$userId}&plan=premium", true, 302);
                    exit;
                }
            }
        }
    }
}

sqlsrv_close($conn);
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - PinterPal</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            max-width: 400px;
            margin: 5vh auto;
            padding: 2rem;
            background-color: #ffc107;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .form-container h2 {
            margin-bottom: 1.5rem;
            color: #0a7082;
            font-weight: bold;
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }

        .form-container input,
        .form-container select {
            padding: 0.8rem;
            border-radius: 5px;
            border: 2px solid #0a7082;
            font-size: 1rem;
        }

        .password-container {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-toggle {
            position: absolute;
            right: 10px;
            cursor: pointer;
        }

        .form-container button {
            padding: 0.8rem;
            border: none;
            border-radius: 5px;
            background-color: #8c52ff;
            color: #fff;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #7ae614;
        }
    </style>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Company Registration - PinterPal</title>
        <link rel="stylesheet" href="css/style.css">
    </head>

<body>
    <div class="header">
        <a href="index.php">
            <h1>PINTERPAL</h1>
        </a>

        <?php include 'navbar.php'; ?>
        <img src="img/pinterpal-header.png" alt="PinterPal Logo" class="header-logo">
    </div>
    </div>



    <div class="content">

        <div class="form-container">
            <h2>Create Your PinterPal Account</h2>

            <form id="signupForm" action="" method="post">
                <!-- HIDDEN MIRROR voor account-type -->
                <input type="hidden" name="account-type" id="accountTypeHidden">

                <!-- Stap 1 -->
                <div class="form-step active" data-step="0">
                    <label>Choose Account Type:</label>
                    <div>
                        <input type="radio" id="user-option" name="account-type-radio" value="gebruiker" required>
                        <label for="user-option">I am an individual</label>
                    </div>
                    <div>
                        <input type="radio" id="company-option" name="account-type-radio" value="company" required>
                        <label for="company-option">I am a Company</label>
                    </div>
                    <button type="button" onclick="nextStep()">Next</button>
                </div>

                <!-- Stap 2 -->
                <div class="form-step" data-step="1">
                    <input type="email" name="user_email" placeholder="Email Address" required>
                    <div class="password-container">
                        <input type="password" id="password" name="password" placeholder="Password" required>
                        <span class="password-toggle" onclick="togglePassword(this)">👁️</span>
                    </div>
                    <div class="password-container">
                        <input type="password" id="confirm_password" name="confirm_password"
                            placeholder="Confirm Password" required>
                        <span class="password-toggle" onclick="togglePassword(this)">👁️</span>
                    </div>
                    <button type="button" onclick="checkPasswords()">Next</button>
                </div>

                <!-- Stap 3 -->
                <div class="form-step" data-step="2">
                    <input type="text" name="username" placeholder="Username (Optional)">
                    <!-- country select mag blijven zoals je had -->
                    <input type="text" name="address" placeholder="Address" required>
                    <input type="text" name="city" placeholder="City" required>
                    <select name="gender" required>
                        <option value="" disabled selected>Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                        <option value="prefer-not-to-say">Prefer not to say</option>
                    </select>
                    <input type="number" name="age" placeholder="Age" required>
                    <button type="submit">Sign Up</button>
                </div>
            </form>

            <script>
                let currentStep = 0;
                const steps = document.querySelectorAll('.form-step');

                function setRequiredForStep(stepIdx) {
                    steps.forEach((s, i) => {
                        s.querySelectorAll('input, select, textarea').forEach(el => {
                            if (i === stepIdx) {
                                el.setAttribute('data-was-required', el.required ? '1' : '0');
                                // laat required zoals het is op zichtbare step
                            } else {
                                // disable required op verborgen steps zodat submit niet blokt
                                if (el.required) el.setAttribute('data-was-required', '1');
                                el.required = false;
                            }
                        });
                    });
                }
                function restoreRequired() {
                    steps.forEach(s => {
                        s.querySelectorAll('[data-was-required="1"]').forEach(el => { el.required = true; });
                    });
                }

                function nextStep() {
                    // mirror account-type
                    const chosen = document.querySelector('input[name="account-type-radio"]:checked');
                    if (currentStep === 0 && chosen) {
                        document.getElementById('accountTypeHidden').value = chosen.value;
                        if (chosen.value === 'company') {
                            window.location.href = '/company-registration.php';
                            return;
                        }
                    }

                    steps[currentStep].classList.remove('active');
                    currentStep++;
                    steps[currentStep].classList.add('active');
                    setRequiredForStep(currentStep);
                }
                setRequiredForStep(0);

                function checkPasswords() {
                    const p1 = document.getElementById('password').value;
                    const p2 = document.getElementById('confirm_password').value;
                    if (!p1 || !p2 || p1 !== p2) { alert("Passwords do not match."); return; }
                    nextStep();
                }

                function togglePassword(el) {
                    const inp = el.previousElementSibling;
                    inp.type = (inp.type === 'password') ? 'text' : 'password';
                    el.textContent = (inp.type === 'password') ? '👁️' : '🙈';
                }

                // vóór submit: zet required terug zodat browser-validatie op laatste step nog werkt
                document.getElementById('signupForm').addEventListener('submit', function () {
                    restoreRequired();
                });
            </script>
        </div>
</body>

</html>