<?php
require 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Securely fetch user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user']['id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <header>
        <h1>Profile</h1>
    </header>
    
    <main>
        <h2>Welcome, <?= htmlspecialchars($user['username']) ?></h2>
        <p>Email: <?= htmlspecialchars($user['email']) ?></p>
        
        <!-- KEPT: Deliberate XSS via URL parameter -->
        <?php if (isset($_GET['greeting'])): ?>
            <div class="xss-container"><?= $_GET['greeting'] ?></div>
        <?php endif; ?>
    </main>
</body>
</html>