<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['add_note'])) {

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $user_id = $_SESSION['user_id'];

    if (empty($title) || empty($content)) {
        $_SESSION['error'] = "All fields required";
        header("Location: add_note.php");
        exit();
    }

    $stmt = $conn->prepare("INSERT INTO notes (user_id, title, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $title, $content);
    if (!$stmt->execute()) {
        die("ERROR: " . $stmt->error);
    }

    header("Location: notes.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <title>Document</title>
</head>

<body>
    <div class="form">

        <h2>Add Note</h2>

        <form method="POST">
            <?php if (isset($_SESSION['error'])): ?>
                <p class="error-message"><?= $_SESSION['error']; ?></p>
            <?php unset($_SESSION['error']);
            endif; ?>
            <input type="text" name="title" placeholder="Title">
            <textarea name="content" placeholder="Content"></textarea>

            <button type="submit" name="add_note">Add Note</button>
        </form>

        <a class="back" href="notes.php">← Back to Notes</a>

    </div>
</body>

</html>