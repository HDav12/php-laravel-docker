<!-- navbar.php -->
<?php
// Start alleen de sessie als deze nog niet draait.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
    <nav>
        <a href="dashboard.php">My Account</a>
        <a href="logout.php">SignOut</a>
    </nav>
<?php else: ?>
    <nav>
        <a href="login.php">LogIn</a>
        <a href="register.php">SignUp</a>
    </nav>
<?php endif; ?>
