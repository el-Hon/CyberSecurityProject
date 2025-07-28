<?php
require 'config.php';

$query = $_GET['query'] ?? '';

// KEPT: Deliberate SQL Injection vulnerability
$sql = "SELECT * FROM posts WHERE title LIKE '%$query%' OR content LIKE '%$query%'";
$posts = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
    <link rel="stylesheet" href="search.css">
</head>
<body>
    <header>
        <h1>Search Results</h1>
    </header>
    
    <main>
        <?php if (count($posts) > 0): ?>
            <?php foreach ($posts as $post): ?>
                <article>
                    <h3><?= htmlspecialchars($post['title']) ?></h3>
                    <p><?= htmlspecialchars($post['content']) ?></p>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No results found for "<?= htmlspecialchars($query) ?>"</p>
        <?php endif; ?>
    </main>
</body>
</html>