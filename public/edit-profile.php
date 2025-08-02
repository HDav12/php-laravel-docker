<?php
// Start de sessie om gebruikersgegevens op te halen
session_start();

// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    // Niet ingelogd → doorverwijzen naar login-pagina
    header("Location: login.php");
    exit;
}

include 'database.php'; // Verbinding met de database

// Haal het gebruikers-ID uit de sessie
$userId = $_SESSION['user_id'];

// Haal huidige gegevens op
$sql = "SELECT username, user_email, address, city, gender, age FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Update gegevens als het formulier wordt verzonden
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? $user['username'];
    $email = $_POST['user_email'] ?? $user['user_email'];
    $address = $_POST['address'] ?? $user['address'];
    $city = $_POST['city'] ?? $user['city'];
    $gender = $_POST['gender'] ?? $user['gender'];
    $age = $_POST['age'] ?? $user['age'];

    $sqlUpdate = "UPDATE users SET username = ?, user_email = ?, address = ?, city = ?, gender = ?, age = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("sssssii", $username, $email, $address, $city, $gender, $age, $userId);

    if ($stmtUpdate->execute()) {
        $successMessage = "Profile updated successfully!";
        $user = [
            'username' => $username,
            'user_email' => $email,
            'address' => $address,
            'city' => $city,
            'gender' => $gender,
            'age' => $age
        ];
    } else {
        $errorMessage = "An error occurred. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - PinterPal</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #0a7082;
            font-family: Arial, sans-serif;
        }

        .content-container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #ffc107;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }

        .content-container h2 {
            text-align: center;
            color: #000;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 2px solid #0a7082;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-actions {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            background-color: #8c52ff;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            text-transform: uppercase;
            cursor: pointer;
            text-decoration: none;
        }

        .btn:hover {
            background-color: #7ae614;
        }

        .message {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1rem;
        }

        .message.success {
            color: #28a745;
        }

        .message.error {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">
            <h1>PINTERPAL.</h1>
        </a>
        <div class="login-signup">
            <img src="img/pinterpal-header.png" alt="PinterPal Logo" class="header-logo">
        </div>
    </div>

    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <div class="content-container">
        <h2>Edit Profile</h2>

        <!-- Succes- en foutmeldingen -->
        <?php if (!empty($successMessage)): ?>
            <p class="message success"><?php echo htmlspecialchars($successMessage); ?></p>
        <?php endif; ?>
        <?php if (!empty($errorMessage)): ?>
            <p class="message error"><?php echo htmlspecialchars($errorMessage); ?></p>
        <?php endif; ?>

        <form method="POST" action="edit-profile.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="user_email">Email</label>
                <input type="email" id="user_email" name="user_email" value="<?php echo htmlspecialchars($user['user_email']); ?>" required>
            </div>

            <div class="form-group">
    <label for="country">Country You Live In</label>
    <input type="text" id="country" name="country" placeholder="Enter the country you live in" value="<?php echo htmlspecialchars($user['country'] ?? ''); ?>">
</div>
   

            
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city']); ?>" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" required>
                    <option value="male" <?php echo $user['gender'] === 'male' ? 'selected' : ''; ?>>Male</option>
                    <option value="female" <?php echo $user['gender'] === 'female' ? 'selected' : ''; ?>>Female</option>
                    <option value="other" <?php echo $user['gender'] === 'other' ? 'selected' : ''; ?>>Other</option>
                    <option value="prefer-not-to-say" <?php echo $user['gender'] === 'prefer-not-to-say' ? 'selected' : ''; ?>>Prefer not to say</option>
                </select>
            </div>
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($user['age']); ?>" required>
            </div>

   


            <!-- Acties -->
            <div class="form-actions">
                <button type="submit" class="btn">Save Changes</button>
                <a href="dashboard.php" class="btn">Cancel</a>
                <a href="index.php" class="btn">Done</a>


        

            </div>

                 
    </div>
</body>
</html>
