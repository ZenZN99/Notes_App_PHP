<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$id = (int) $_POST['id'];


$stmt = $conn->prepare("SELECT id, title, content FROM notes WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $_SESSION['user_id']);
$stmt->execute();

$result = $stmt->get_result();
$note = $result->fetch_assoc();

if (!$note) {
    die("Unauthorized or Note not found");
}

if (isset($_POST['update_note'])) {

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    $update = $conn->prepare("UPDATE notes SET title = ?, content = ? WHERE id = ? AND user_id = ?");
    $update->bind_param("ssii", $title, $content, $id, $_SESSION['user_id']);
    $update->execute();

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

        <h2>Edit Note</h2>

        <form method="POST">
            <input type="text" name="title" value="<?= htmlspecialchars($note['title']) ?>" required>
            <textarea name="content" required><?= htmlspecialchars($note['content']) ?></textarea>

            <button type="submit" name="update_note">Update</button>
        </form>

        <a class="back" href="notes.php">← Back to Notes</a>

    </div>
</body>

</html>
