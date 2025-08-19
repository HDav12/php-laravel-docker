<?php
session_start();
include __DIR__ . '/database.php';

function elog($m){
    $msg = str_replace(["\r","\n"], ' ', (string)$m);
    @file_put_contents('php://stderr', "[app] $msg\n");
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $companyName   = trim($_POST['company_name']   ?? '');
    $companyEmail  = trim($_POST['company_email']  ?? '');
    $contactPerson = trim($_POST['contact_person'] ?? '');
    $phoneNumber   = trim($_POST['phone_number']   ?? '');
    $address       = trim($_POST['company_address']?? '');
    $city          = trim($_POST['company_city']   ?? '');
    $zipCode       = trim($_POST['zip_code']       ?? '');
    $country       = trim($_POST['country']        ?? '');
    $paymentPlan   = trim($_POST['payment_plan']   ?? 'basic');
    $password      =        $_POST['password']     ?? '';
    $confirm       =        $_POST['confirm_password'] ?? '';
    $role          = 'company';

    // Validatie
    if ($companyName==='' || $companyEmail==='' || $contactPerson==='' ||
        $phoneNumber==='' || $address==='' || $city==='' ||
        $zipCode==='' || $country==='' || $password==='' || $confirm==='') {
        $error = "Vul alle verplichte velden in.";
    } elseif (!filter_var($companyEmail, FILTER_VALIDATE_EMAIL)) {
        $error = "Voer een geldig e-mailadres in.";
    } elseif ($password !== $confirm) {
        $error = "Wachtwoorden komen niet overeen.";
    } else {
        // Bestaat het e-mailadres al in Users?
        $sqlCheck  = "SELECT 1 FROM dbo.Users WHERE user_email = ?";
        $stmtCheck = sqlsrv_prepare($conn, $sqlCheck, [ &$companyEmail ]);
        if ($stmtCheck === false) {
            elog('company CHECK prepare FAIL '.print_r(sqlsrv_errors(),true));
            $error = "Database fout (prepare check).";
        } elseif (!sqlsrv_execute($stmtCheck)) {
            elog('company CHECK execute FAIL '.print_r(sqlsrv_errors(),true));
            $error = "Database fout (execute check).";
        } else {
            $exists = sqlsrv_fetch_array($stmtCheck, SQLSRV_FETCH_NUMERIC) ? true : false;
            sqlsrv_free_stmt($stmtCheck);

            if ($exists) {
                $error = "Dit e-mailadres is al geregistreerd.";
            } else {
                // Transactie starten
                sqlsrv_begin_transaction($conn);

                // 1) Gebruiker aanmaken in Users met computed id (code-only fix)
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $sqlUser = "
INSERT INTO dbo.Users (id, user_email, [password], username, address, city, role)
OUTPUT INSERTED.id
SELECT ISNULL(MAX(id),0)+1, ?,?,?,?,?,?
FROM dbo.Users WITH (TABLOCKX, HOLDLOCK);
";
                $paramsUser = [ &$companyEmail, &$hashed, &$companyName, &$address, &$city, &$role ];
                $stmtUser   = sqlsrv_prepare($conn, $sqlUser, $paramsUser);

                if ($stmtUser === false) {
                    elog('company USER prepare FAIL '.print_r(sqlsrv_errors(),true));
                    sqlsrv_rollback($conn);
                    $error = "Database fout (prepare user).";
                } elseif (!sqlsrv_execute($stmtUser)) {
                    elog('company USER execute FAIL '.print_r(sqlsrv_errors(),true));
                    sqlsrv_rollback($conn);
                    $error = "Fout bij aanmaken gebruikersaccount.";
                } else {
                    $out    = sqlsrv_fetch_array($stmtUser, SQLSRV_FETCH_NUMERIC);
                    $userId = $out[0] ?? null;
                    sqlsrv_free_stmt($stmtUser);

                    if (!$userId) {
                        sqlsrv_rollback($conn);
                        $error = "Kon geen gebruikers-id bepalen.";
                    } else {
                        // 2) Company rij toevoegen (we gaan uit van identity op Companies; anders geen company_id)
                        $sqlComp = "
INSERT INTO dbo.Companies (user_id, company_name, company_email, contact_person, phone_number, company_address, company_city, zip_code, country, payment_plan)
VALUES (?,?,?,?,?,?,?,?,?,?)
";
                        $paramsComp = [ &$userId, &$companyName, &$companyEmail, &$contactPerson, &$phoneNumber, &$address, &$city, &$zipCode, &$country, &$paymentPlan ];
                        $stmtComp   = sqlsrv_prepare($conn, $sqlComp, $paramsComp);

                        if ($stmtComp === false) {
                            elog('company COMP prepare FAIL '.print_r(sqlsrv_errors(),true));
                            sqlsrv_rollback($conn);
                            $error = "Database fout (prepare company).";
                        } elseif (!sqlsrv_execute($stmtComp)) {
                            elog('company COMP execute FAIL '.print_r(sqlsrv_errors(),true));
                            sqlsrv_rollback($conn);
                            $error = "Fout bij opslaan van bedrijfsgegevens.";
                        } else {
                            // Probeer company_id via SCOPE_IDENTITY (werkt als Companies een identity PK heeft)
                            $resId = sqlsrv_query($conn, "SELECT CAST(SCOPE_IDENTITY() AS int) AS id");
                            $idRow = $resId ? sqlsrv_fetch_array($resId, SQLSRV_FETCH_ASSOC) : null;
                            $companyId = $idRow['id'] ?? null;
                            if ($resId) sqlsrv_free_stmt($resId);
                            sqlsrv_free_stmt($stmtComp);

                            // Alles ok -> commit
                            sqlsrv_commit($conn);

                            // Login + redirect
                            $_SESSION['user_logged_in'] = true;
                            $_SESSION['user_email']     = $companyEmail;
                            $_SESSION['user_id']        = $userId;
                            $_SESSION['user_role']      = $role;

                            // Fallback: als geen companyId, gebruik userId in de url
                            $cid = $companyId ?? $userId;

                            header("Location: /payment.php?company_id={$cid}&plan={$paymentPlan}", true, 302);
                            exit;
                        }
                    }
                }
            }
        }
    }
}

// Geen POST of er was een $error -> pagina tonen met evt. foutmelding
?>




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
        <div class="login-signup">
            <!-- Dynamische login/signup of uitlog-knoppen -->
            <?php include 'navbar.php'; ?>
            <img src="img/pinterpal-header.png" alt="PinterPal Logo" class="header-logo">
        </div>
    </div>

 

       <div class="content">


    <div class="form-container">
        <h2>Register Your Company</h2>

        <!-- Toon foutmelding -->
        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <!-- Toon succesmelding -->
        <?php if (!empty($success)): ?>
            <p style="color: green;"><?php echo htmlspecialchars($success); ?></p>
        <?php endif; ?>

        <!-- Bedrijfsregistratieformulier -->
        <form action="company-registration.php" method="post">
            <!-- Bedrijfsinformatie -->
            <h3>Company Information</h3>
            <input type="text" name="company_name" placeholder="Company Name" required>
            <input type="email" name="company_email" placeholder="Company Email" required>
            <input type="text" name="contact_person" placeholder="Contact Person" required>
            <input type="tel" name="phone_number" placeholder="Phone Number" required>

            <h3>Address</h3>
            <input type="text" name="company_address" placeholder="Company Address" required>
            <input type="text" name="company_city" placeholder="City" required>
            <input type="text" name="zip_code" placeholder="ZIP Code" required>
            <h3>Account Login</h3>
            <input type="password" name="password" placeholder="Choose a password" required>
            <input type="password" name="confirm_password" placeholder="Confirm password" required>

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

            <h3>Payment Plan</h3>
            <div>
         
                <input type="radio" id="premium-plan" name="payment_plan" value="premium">
                <label for="premium-plan">Premium Plan (€29.90/month)</label>
            
            </div>

            <!-- Submit Button -->
            <button type="checkoutBtn">Register</button>
        </form>

        <a href="login.php">Already registered? Log in here.</a>
    </div>

    <style>
        .form-container {
            max-width: 500px;
            margin: 5vh auto;
            padding: 2rem;
            background-color: #ffc107;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .form-container h2, .form-container h3 {
            color: #0a7082;
        }

        .form-container input, .form-container select {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border-radius: 5px;
            border: 2px solid #0a7082;
            font-size: 1rem;
        }

        .form-container button {
            padding: 0.8rem;
            border: none;
            border-radius: 5px;
            background-color: #8c52ff;
            color: white;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #7ae614;
        }

        .form-container a {
            margin-top: 1rem;
            display: inline-block;
            color: #0a7082;
            text-decoration: none;
            font-weight: bold;
        }

        .form-container a:hover {
            text-decoration: underline;
        }

        .form-container div {
            margin-bottom: 1rem;
            text-align: left;
        }

        .form-container label {
            margin-left: 0.5rem;
            font-weight: bold;
        }
    </style>
</body>
</html>
