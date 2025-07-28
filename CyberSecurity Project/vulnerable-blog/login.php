<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Vulnerable SQL Injection
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $stmt = $pdo->query($sql);
    $user = $stmt->fetch();
    
    if ($user) {
        $_SESSION['user'] = $user;
        // Redirect to profile.php instead of index.html
        header('Location: profile.php');
        exit();
    } else {
        $error = "Invalid credentials";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <header>
    <h1>Insecure Blog</h1>
    <nav>
        <a href="home.html">Home</a>  <!-- Home button -->
        <a href="login.php">Login</a>
        <a href="profile.php">Profile</a>
        <a href="admin.php">Admin</a>
    </nav>
        <h1>Login</h1>
    </header>
    
    <main>
        <?php if (isset($error)): ?>
            <p style="color: red;"><?= $error ?></p>
        <?php endif; ?>
        
        <!-- CSRF Vulnerability: Missing token -->
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        
        <!-- XSS Vulnerability -->
        <?php if (isset($_GET['message'])): ?>
            <p><?= $_GET['message'] ?></p>
        <?php endif; ?>
    </main>
</body>
</html>