<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES['avatar']['name']);
    
    // Secure file upload
    $allowedTypes = ['image/jpeg', 'image/png'];
    if (in_array($_FILES['avatar']['type'], $allowedTypes)) {
        move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile);
        $message = "File uploaded successfully.";
    } else {
        $message = "Only JPG/PNG files allowed.";
    }
}

// KEPT: Deliberate SSRF vulnerability
if (isset($_GET['url'])) {
    $url = $_GET['url'];
    $content = file_get_contents($url);
    echo $content;
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload</title>
    <link rel="stylesheet" href="upload.css">
</head>
<body>
    <header>
        <h1>Upload</h1>
    </header>
    
    <main>
        <?php if (isset($message)): ?>
            <p><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="avatar" accept="image/*">
            <button type="submit">Upload</button>
        </form>
        
        <!-- KEPT: SSRF test form -->
        <form method="GET">
            <input type="text" name="url" placeholder="http://example.com">
            <button type="submit">Fetch URL</button>
        </form>
    </main>
</body>
</html>