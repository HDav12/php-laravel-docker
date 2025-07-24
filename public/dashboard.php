<?php
// Start de sessie om gebruikersgegevens op te halen
session_start();
$role = $_SESSION['user_role'] ?? 'company';
// Controleer of de gebruiker is ingelogd
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    // Niet ingelogd → doorverwijzen naar login-pagina
    header("Location: login.php");
    exit;
}

include 'database.php'; // Verbinding met de database

// Haal het gebruikers-ID uit de sessie
$userId = $_SESSION['user_id'];

// Query om gebruikersgegevens op te halen
$sql = "SELECT user_email, username, address, city, gender, age FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Controleer of er resultaten zijn
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc(); // Haal de gegevens op als associatieve array
} else {
    echo "Fout: Gebruikersgegevens konden niet worden geladen.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - PinterPal</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #0a7082;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .content-container {
            max-width: 900px;
            margin: 20px auto;
            background-color: #ffc107;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 4px solid #fff;
            background-color: var(--background-color, #6c757d);
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
        }

        .profile-info h2 {
            font-size: 2rem;
            color: #000;
        }

        .profile-info p {
            font-size: 1rem;
            margin-bottom: 10px;
            color: #000;
        }

        .profile-actions {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .btn {
            padding: 10px 20px;
            background-color: #8c52ff;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            text-transform: uppercase;
            text-decoration: none;
            text-align: center;
        }

        .btn:hover {
            background-color: #7ae614;
        }

        @media (max-width: 768px) {
            .content-container {
                padding: 15px;
            }

            .profile-header {
                flex-direction: column;
                align-items: center;
            }

            .profile-info h2 {
                font-size: 1.5rem;
                text-align: center;
            }

            .profile-avatar {
                width: 80px;
                height: 80px;
            }

            .profile-actions {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="header">
    <p><strong>Rol:</strong> <?php echo htmlspecialchars($role); ?></p>
        <a href="index.php">
            <h1>PINTERPAL.</h1>
        </a>
        <div class="login-signup">
            <img src="img/pinterpal-logo.jpg" alt="PinterPal Logo" class="header-logo">
        </div>
    </div>

    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <div class="content-container">
        <div class="profile-header">
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
                <p><strong>Account Created:</strong> Example Date Placeholder</p>
            </div>
        </div>

        <!-- Actieknoppen -->
        <div class="profile-actions">
            <a href="edit-profile.php" class="btn">Edit Profile</a>
            <a href="logout.php" class="btn">Sign Out</a>

            
        </div>
    </div>
   
    <div  Earningsblok -->
        <div class="earnings-section">
            <h2>Earnings Overview</h2>
            <p>Track your earnings and get detailed insights about your activity on PinterPal.</p>
            <a href="profile-earn.php" class="btn">View Earnings</a>
        </div>
    </div>
</div>



            
        </div>
    </div>
</body>
</html>
