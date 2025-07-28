<?php
require 'config.php';

// Broken Access Control - No admin check
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Command Injection Vulnerability
if (isset($_GET['cmd'])) {
    $output = shell_exec($_GET['cmd']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <header>
        <h1>Insecure Blog</h1>
        <nav>
            <a href="home.html">Home</a>
            <a href="login.php">Login</a>
            <a href="profile.php">Profile</a>
            <a href="admin.php">Admin</a>
        </nav>
    </header>

    <main>
        <section class="admin-controls">
            <h2>Admin Panel</h2>
            <p>Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?></p>
            
            <!-- Command Injection Vulnerability -->
            <div class="command-form">
                <h3>System Command</h3>
                <form method="GET">
                    <input type="text" name="cmd" placeholder="Enter command">
                    <button type="submit">Execute</button>
                </form>
                <?php if (isset($output)): ?>
                    <pre><?= $output ?></pre>
                <?php endif; ?>
            </div>

            <!-- User Management -->
            <div class="user-list">
                <h3>All Users</h3>
                <?php
                $users = $pdo->query("SELECT * FROM users")->fetchAll();
                foreach ($users as $user):
                ?>
                    <div class="user">
                        <p>
                            <strong>ID:</strong> <?= $user['id'] ?><br>
                            <strong>Username:</strong> <?= $user['username'] ?><br>
                            <strong>Email:</strong> <?= $user['email'] ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2023 Insecure Blog</p>
    </footer>
</body>
</html>