<?php
require 'config.php';

// =============================================
// INSECURE POST FETCHING (SQL INJECTION VULNERABLE)
// =============================================
$postId = $_GET['id']; // Unsanitized input!
$sql = "SELECT * FROM posts WHERE id = $postId";
$post = $pdo->query($sql)->fetch();

// =============================================
// INSECURE COMMENT HANDLING (XSS + SQLi VULNERABLE)
// =============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
    $comment = $_POST['comment']; // Unsanitized!
    $sql = "INSERT INTO comments (post_id, content) VALUES ($postId, '$comment')";
    $pdo->exec($sql);
}

// =============================================
// NEW: SEARCH RELATED COMMENTS (SQLi VULNERABLE)
// =============================================
$relatedComments = [];
if (isset($_GET['search_comments'])) {
    $searchTerm = $_GET['search_comments'];
    $sql = "SELECT * FROM comments 
            WHERE post_id = $postId 
            AND content LIKE '%$searchTerm%'";
    $relatedComments = $pdo->query($sql)->fetchAll();
}

// Get all comments for this post (XSS vulnerable)
$sql = "SELECT * FROM comments WHERE post_id = $postId";
$comments = $pdo->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($post['title']) ?></title>
    <link rel="stylesheet" href="post.css">
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
        <h1><?= htmlspecialchars($post['title']) ?></h1>
    </header>
    
    <main>
        <article>
            <p><?= htmlspecialchars($post['content']) ?></p>
        </article>
        
        <!-- NEW: COMMENT SEARCH FUNCTIONALITY -->
        <section class="comment-search">
            <h3>Search in Comments</h3>
            <form method="GET">
                <input type="hidden" name="id" value="<?= $postId ?>">
                <input type="text" name="search_comments" placeholder="Search comments...">
                <button type="submit">Search</button>
            </form>
            
            <?php if (!empty($relatedComments)): ?>
                <div class="search-results">
                    <h4>Matching Comments:</h4>
                    <?php foreach ($relatedComments as $comment): ?>
                        <div class="comment">
                            <p><?= $comment['content'] /* XSS VULNERABILITY */ ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
        
        <!-- REGULAR COMMENTS SECTION -->
        <section class="comments">
            <h2>All Comments</h2>
            
            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <p><?= $comment['content'] /* XSS VULNERABILITY */ ?></p>
                </div>
            <?php endforeach; ?>
            
            <form method="POST">
                <textarea name="comment" placeholder="Leave a comment..."></textarea>
                <button type="submit">Post Comment</button>
            </form>
        </section>
    </main>
    
    <footer>
        <p>&copy; 2023 Vulnerable Blog</p>
    </footer>
</body>
</html>