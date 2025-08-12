<?php

function header_log($k, $v) {
    if ((getenv('APP_DEBUG_LOG') ?? '0') === '1') {
        header("X-App-$k: ".substr((string)$v, 0, 200));
    }
}
session_start();
include __DIR__ . '/database.php';

/* Zorg dat error_log naar Render Logs gaat en toggle via env */
ini_set('log_errors', '1');
ini_set('error_log', 'php://stderr');
function app_log($m){ if ((getenv('APP_DEBUG_LOG') ?? '0') === '1') error_log('[app] '.$m); }

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accountType = $_POST['account-type'] ?? '';
    if ($accountType === 'company') { header('Location: /public/company-register.php'); exit; }

    $email    = trim($_POST['user_email']       ?? '');
    $password = $_POST['password']              ?? '';
    $confirm  = $_POST['confirm_password']      ?? '';
    $username = trim($_POST['username']         ?? '');
    $address  = trim($_POST['address']          ?? '');
    $city     = trim($_POST['city']             ?? '');
    $gender   = trim($_POST['gender']           ?? '');
    $ageRaw   = $_POST['age']                   ?? '';
    $age      = ($ageRaw === '' ? null : (int)$ageRaw);
    $role     = 'gebruiker';

    if ($email === '' || $password === '' || $confirm === '' || $age === null) {
        $error = 'Vul alle verplichte velden in.';
    } elseif ($password !== $confirm) {
        $error = 'Wachtwoorden komen niet overeen.';
    } else {

        // 1) Bestaat e-mailadres al?
        $sqlCheck  = "SELECT 1 FROM dbo.users WHERE user_email = ?";
        $stmtCheck = sqlsrv_prepare($conn, $sqlCheck, [ &$email ]);
        if ($stmtCheck === false) {
            app_log('prepare check failed: '.print_r(sqlsrv_errors(), true));
            $error = 'Database fout (prepare check).';
        } elseif (!sqlsrv_execute($stmtCheck)) {
            app_log('execute check failed: '.print_r(sqlsrv_errors(), true));
            $error = 'Database fout (execute check).';
        } else {
            $exists = sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_NUMERIC) ? true : false;
            sqlsrv_free_stmt($stmtCheck);

            if ($exists) {
                $error = 'Dit e-mailadres is al in gebruik. Probeer opnieuw.';
            } else {
                // 2) Insert
                $hashed = password_hash($password, PASSWORD_DEFAULT);

                // Let op: [password] met brackets voor de zekerheid
                $sqlIns = "INSERT INTO dbo.users (user_email, [password], username, address, city, gender, age, role)
                           VALUES (?,?,?,?,?,?,?,?)";
                $paramsIns = [ &$email, &$hashed, &$username, &$address, &$city, &$gender, &$age, &$role ];
                $stmtIns = sqlsrv_prepare($conn, $sqlIns, $paramsIns);

                if ($stmtIns === false) {
                    app_log('prepare insert failed: '.print_r(sqlsrv_errors(), true));
                    $error = 'Database fout (prepare insert).';
                } elseif (!sqlsrv_execute($stmtIns)) {
                    app_log('execute insert failed: '.print_r(sqlsrv_errors(), true));
                    $error = 'Fout bij aanmaken account.';
                } else {
                    // 3) Last insert id
                    $resId = sqlsrv_query($conn, "SELECT CAST(SCOPE_IDENTITY() AS int) AS id");
                    $idRow = $resId ? sqlsrv_fetch_array($resId, SQLSRV_FETCH_ASSOC) : null;
                    $userId = $idRow['id'] ?? null;
                    if ($resId) sqlsrv_free_stmt($resId);
                    sqlsrv_free_stmt($stmtIns);

                    // 4) Sessions + redirect
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['user_email']     = $email;
                    $_SESSION['user_id']        = $userId;
                    $_SESSION['user_role']      = $role;

                    app_log("REGISTER ok email=$email id=".($userId ?? 'null'));
                    sqlsrv_close($conn);

                    // Gebruik een absolute path voor zekerheid
                    header('Location: /pinterpalbot.php', true, 302);
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

        .form-container input, .form-container select {
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

    <!-- Multi-Step Form -->
    <form id="signupForm" action="" method="post">
        <!-- Stap 1: Kies accounttype -->
        <div class="form-step active">
            <label for="account-type">Choose Account Type:</label>
            <div>
                <input type="radio" id="user-option" name="account-type" value="gebruiker" required>
                <label for="user-option">I am an individual</label>
            </div>
            <div>
                <input type="radio" id="company-option" name="account-type" value="company" required>
                <label for="company-option">I am a Company</label>
            </div>
            <button type="button" onclick="nextStep()">Next</button>
        </div>

        <!-- Rest van de stappen voor "User" -->
        <div class="form-step">
            <input type="email" name="user_email" placeholder="Email Address" required>
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="Password" required>
                <span class="password-toggle" onclick="togglePassword(this)">👁️</span>
            </div>
            <div class="password-container">
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                <span class="password-toggle" onclick="togglePassword(this)">👁️</span>
            </div>
            <button type="button" onclick="checkPasswords()">Next</button>
        </div>

        <div class="form-step">
            
            <input type="text" name="username" placeholder="Username (Optional)">
            <select name="country" required>
    <option value="" disabled selected>Select your country</option>
    <option value="Afghanistan">Afghanistan</option>
    <option value="Albania">Albania</option>
    <option value="Algeria">Algeria</option>
    <option value="Andorra">Andorra</option>
    <option value="Angola">Angola</option>
    <option value="Antigua and Barbuda">Antigua and Barbuda</option>
    <option value="Argentina">Argentina</option>
    <option value="Armenia">Armenia</option>
    <option value="Australia">Australia</option>
    <option value="Austria">Austria</option>
    <option value="Azerbaijan">Azerbaijan</option>
    <option value="Bahamas">Bahamas</option>
    <option value="Bahrain">Bahrain</option>
    <option value="Bangladesh">Bangladesh</option>
    <option value="Barbados">Barbados</option>
    <option value="Belarus">Belarus</option>
    <option value="Belgium">Belgium</option>
    <option value="Belize">Belize</option>
    <option value="Benin">Benin</option>
    <option value="Bhutan">Bhutan</option>
    <option value="Bolivia">Bolivia</option>
    <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
    <option value="Botswana">Botswana</option>
    <option value="Brazil">Brazil</option>
    <option value="Brunei">Brunei</option>
    <option value="Bulgaria">Bulgaria</option>
    <option value="Burkina Faso">Burkina Faso</option>
    <option value="Burundi">Burundi</option>
    <option value="Cabo Verde">Cabo Verde</option>
    <option value="Cambodia">Cambodia</option>
    <option value="Cameroon">Cameroon</option>
    <option value="Canada">Canada</option>
    <option value="Central African Republic">Central African Republic</option>
    <option value="Chad">Chad</option>
    <option value="Chile">Chile</option>
    <option value="China">China</option>
    <option value="Colombia">Colombia</option>
    <option value="Comoros">Comoros</option>
    <option value="Congo (Congo-Brazzaville)">Congo (Congo-Brazzaville)</option>
    <option value="Costa Rica">Costa Rica</option>
    <option value="Croatia">Croatia</option>
    <option value="Cuba">Cuba</option>
    <option value="Cyprus">Cyprus</option>
    <option value="Czech Republic">Czech Republic</option>
    <option value="Denmark">Denmark</option>
    <option value="Djibouti">Djibouti</option>
    <option value="Dominica">Dominica</option>
    <option value="Dominican Republic">Dominican Republic</option>
    <option value="Ecuador">Ecuador</option>
    <option value="Egypt">Egypt</option>
    <option value="El Salvador">El Salvador</option>
    <option value="Equatorial Guinea">Equatorial Guinea</option>
    <option value="Eritrea">Eritrea</option>
    <option value="Estonia">Estonia</option>
    <option value="Eswatini">Eswatini</option>
    <option value="Ethiopia">Ethiopia</option>
    <option value="Fiji">Fiji</option>
    <option value="Finland">Finland</option>
    <option value="France">France</option>
    <option value="Gabon">Gabon</option>
    <option value="Gambia">Gambia</option>
    <option value="Georgia">Georgia</option>
    <option value="Germany">Germany</option>
    <option value="Ghana">Ghana</option>
    <option value="Greece">Greece</option>
    <option value="Grenada">Grenada</option>
    <option value="Guatemala">Guatemala</option>
    <option value="Guinea">Guinea</option>
    <option value="Guinea-Bissau">Guinea-Bissau</option>
    <option value="Guyana">Guyana</option>
    <option value="Haiti">Haiti</option>
    <option value="Honduras">Honduras</option>
    <option value="Hungary">Hungary</option>
    <option value="Iceland">Iceland</option>
    <option value="India">India</option>
    <option value="Indonesia">Indonesia</option>
    <option value="Iran">Iran</option>
    <option value="Iraq">Iraq</option>
    <option value="Ireland">Ireland</option>
    <option value="Israel">Israel</option>
    <option value="Italy">Italy</option>
    <option value="Jamaica">Jamaica</option>
    <option value="Japan">Japan</option>
    <option value="Jordan">Jordan</option>
    <option value="Kazakhstan">Kazakhstan</option>
    <option value="Kenya">Kenya</option>
    <option value="Kiribati">Kiribati</option>
    <option value="Kuwait">Kuwait</option>
    <option value="Kyrgyzstan">Kyrgyzstan</option>
    <option value="Laos">Laos</option>
    <option value="Latvia">Latvia</option>
    <option value="Lebanon">Lebanon</option>
    <option value="Lesotho">Lesotho</option>
    <option value="Liberia">Liberia</option>
    <option value="Libya">Libya</option>
    <option value="Liechtenstein">Liechtenstein</option>
    <option value="Lithuania">Lithuania</option>
    <option value="Luxembourg">Luxembourg</option>
    <option value="Madagascar">Madagascar</option>
    <option value="Malawi">Malawi</option>
    <option value="Malaysia">Malaysia</option>
    <option value="Maldives">Maldives</option>
    <option value="Mali">Mali</option>
    <option value="Malta">Malta</option>
    <option value="Marshall Islands">Marshall Islands</option>
    <option value="Mauritania">Mauritania</option>
    <option value="Mauritius">Mauritius</option>
    <option value="Mexico">Mexico</option>
    <option value="Micronesia">Micronesia</option>
    <option value="Moldova">Moldova</option>
    <option value="Monaco">Monaco</option>
    <option value="Mongolia">Mongolia</option>
    <option value="Montenegro">Montenegro</option>
    <option value="Morocco">Morocco</option>
    <option value="Mozambique">Mozambique</option>
    <option value="Myanmar">Myanmar</option>
    <option value="Namibia">Namibia</option>
    <option value="Nauru">Nauru</option>
    <option value="Nepal">Nepal</option>
    <option value="Netherlands">Netherlands</option>
    <option value="New Zealand">New Zealand</option>
    <option value="Nicaragua">Nicaragua</option>
    <option value="Niger">Niger</option>
    <option value="Nigeria">Nigeria</option>
    <option value="North Korea">North Korea</option>
    <option value="North Macedonia">North Macedonia</option>
    <option value="Norway">Norway</option>
    <option value="Oman">Oman</option>
    <option value="Pakistan">Pakistan</option>
    <option value="Palau">Palau</option>
    <option value="Panama">Panama</option>
    <option value="Papua New Guinea">Papua New Guinea</option>
    <option value="Paraguay">Paraguay</option>
    <option value="Peru">Peru</option>
    <option value="Philippines">Philippines</option>
    <option value="Poland">Poland</option>
    <option value="Portugal">Portugal</option>
    <option value="Qatar">Qatar</option>
    <option value="Romania">Romania</option>
    <option value="Russia">Russia</option>
    <option value="Rwanda">Rwanda</option>
    <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
    <option value="Saint Lucia">Saint Lucia</option>
    <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
    <option value="Samoa">Samoa</option>
    <option value="San Marino">San Marino</option>
    <option value="Sao Tome and Principe">Sao Tome and Principe</option>
    <option value="Saudi Arabia">Saudi Arabia</option>
    <option value="Senegal">Senegal</option>
    <option value="Serbia">Serbia</option>
    <option value="Seychelles">Seychelles</option>
    <option value="Sierra Leone">Sierra Leone</option>
    <option value="Singapore">Singapore</option>
    <option value="Slovakia">Slovakia</option>
    <option value="Slovenia">Slovenia</option>
    <option value="Solomon Islands">Solomon Islands</option>
    <option value="Somalia">Somalia</option>
    <option value="South Africa">South Africa</option>
    <option value="South Korea">South Korea</option>
    <option value="South Sudan">South Sudan</option>
    <option value="Spain">Spain</option>
    <option value="Sri Lanka">Sri Lanka</option>
    <option value="Sudan">Sudan</option>
    <option value="Suriname">Suriname</option>
    <option value="Sweden">Sweden</option>
    <option value="Switzerland">Switzerland</option>
    <option value="Syria">Syria</option>
    <option value="Taiwan">Taiwan</option>
    <option value="Tajikistan">Tajikistan</option>
    <option value="Tanzania">Tanzania</option>
    <option value="Thailand">Thailand</option>
    <option value="Timor-Leste">Timor-Leste</option>
    <option value="Togo">Togo</option>
    <option value="Tonga">Tonga</option>
    <option value="Trinidad and Tobago">Trinidad and Tobago</option>
    <option value="Tunisia">Tunisia</option>
    <option value="Turkey">Turkey</option>
    <option value="Turkmenistan">Turkmenistan</option>
    <option value="Tuvalu">Tuvalu</option>
    <option value="Uganda">Uganda</option>
    <option value="Ukraine">Ukraine</option>
    <option value="United Arab Emirates">United Arab Emirates</option>
    <option value="United Kingdom">United Kingdom</option>
    <option value="United States">United States</option>
    <option value="Uruguay">Uruguay</option>
    <option value="Uzbekistan">Uzbekistan</option>
    <option value="Vanuatu">Vanuatu</option>
    <option value="Vatican City">Vatican City</option>
    <option value="Venezuela">Venezuela</option>
    <option value="Vietnam">Vietnam</option>
    <option value="Yemen">Yemen</option>
    <option value="Zambia">Zambia</option>
    <option value="Zimbabwe">Zimbabwe</option>
</select>
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
</div>

<script>
    let currentStep = 0;
    const formSteps = document.querySelectorAll(".form-step");

    function nextStep() {
        const accountType = document.querySelector('input[name="account-type"]:checked')?.value;

        // Redirect naar company-registration.php als "Company" is geselecteerd
        if (currentStep === 0 && accountType === 'company') {
            window.location.href = '/company-registration.php';  // Zet hier het juiste pad naar de PHP-pagina
            return;
        }

        // Ga naar de volgende stap voor gebruikers
        formSteps[currentStep].classList.remove("active");
        currentStep++;
        formSteps[currentStep].classList.add("active");
    }

    function checkPasswords() {
        const pass1 = document.getElementById('password').value;
        const pass2 = document.getElementById('confirm_password').value;

        if (!pass1 || !pass2 || pass1 !== pass2) {
            alert("Passwords do not match. Please try again.");
            return;
        }
        nextStep();
    }

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
