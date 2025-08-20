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

// Haal bestaande gegevens op
$sql = "SELECT username, user_email, address, city, gender, age FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Update gegevens als het formulier wordt verzonden
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bankAddress = $_POST['bank_address'] ?? '';
    $cryptoWallet = $_POST['crypto_wallet'] ?? '';

    $sqlUpdate = "UPDATE users SET bank_address = ?, crypto_wallet = ? WHERE id = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("ssi", $bankAddress, $cryptoWallet, $userId);

    if ($stmtUpdate->execute()) {
        $successMessage = "Details updated successfully!";
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
    <title>PinterPal Profile & Earnings</title>
    <link rel="stylesheet" href="style.css">
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
            color: #000;
        }

        .profile-earn {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .profile-avatar img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-info {
            flex-grow: 1;
        }

        .profile-info h2 {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .profile-actions {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }

        .profile-actions .btn {
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

        .earnings-section {
            margin-top: 30px;
        }

        .earnings-title {
            font-size: 1.6rem;
            margin-bottom: 15px;
            text-align: center;
        }

        .earnings-description {
            text-align: center;
            margin-bottom: 20px;
        }

        .video-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .video-container iframe {
            width: 100%;
            height: 315px;
            border: none;
            border-radius: 10px;
        }

        .earnings-link {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">
            <h1>PINTERPAL</h1>
        </a>
    </div>

    <div class="content-container">
        <!-- Profielsectie -->
        <div class="profile-earn">
            <div class="profile-avatar">
                <img src="img/pinterpal-start-trial.jpg" alt="Profile Picture">
            </div>
            <div class="profile-info">
                <h2><?php echo htmlspecialchars($user['username'] ?? 'User'); ?>'s Profile</h2>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['user_email']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
                <p><strong>City:</strong> <?php echo htmlspecialchars($user['city']); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['gender']); ?></p>
                <p><strong>Age:</strong> <?php echo htmlspecialchars($user['age']); ?></p>
            </div>
        </div>

        <!-- Actieknoppen -->
        <div class="profile-actions">
            <a href="edit-profile.php" class="btn">Edit Profile</a>
        </div>

        <!-- Earnings-sectie -->
        <div class="earnings-section">
            <h2 class="earnings-title">Earnings</h2>
            <p class="earnings-description">
                Earning with PinterPal is simple! Share your bank account details or Bitcoin wallet address with us to start earning rewards.
            </p>

            <!-- YouTube-video -->
            <div class="video-container">
                <iframe 
                    src="https://www.youtube.com/embed/R_vxmvqFaMc" 
                    title="YouTube video player" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>

 <!-- Link to Earnings Page -->
<div class="earnings-link">
    <form action="dashboard.php" method="POST">
        <label for="bank-address">Enter your bank account details (optional):</label><br>
        <textarea id="bank-address" name="bank_address" placeholder="Enter your bank details here" style="width: 100%; height: 40px;"></textarea>
        <br><br>
        
        <label for="bitcoin-address">Enter your Bitcoin wallet address (optional):</label><br>
        <textarea id="bitcoin-address" name="bitcoin_address" placeholder="Enter your Bitcoin wallet address here" style="width: 100%; height: 40px;"></textarea>
        <br><br>
        
        <p><strong>Important:</strong> Please double-check your entries. You are responsible for providing the correct details. If the address is incorrect, your payment may be sent to the wrong person.</p>
        
        <button type="submit" class="btn" style="background-color: #6a0dad; color: white; padding: 10px 20px; border: none; border-radius: 5px;">SAVE</button>
    </form>
</div>


    </div>
</body>
</html>
