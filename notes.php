<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM notes WHERE user_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
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
    <div class="page">

        <h2>My Notes</h2>

        <a class="add-btn" href="add_note.php">+ Add Note</a>

        <?php while ($note = $result->fetch_assoc()): ?>
            <div class="note">
                <h3><?= htmlspecialchars($note['title']) ?></h3>
                <p><?= htmlspecialchars($note['content']) ?></p>

                <div class="actions">
                    <a class="edit" href="edit_note.php?id=<?= $note['id'] ?>">Edit</a>
                    <a class="delete" href="delete_note.php?id=<?= $note['id'] ?>" onclick="return confirm('Delete?')">Delete</a>
                </div>
            </div>
        <?php endwhile; ?>

    </div>
</body>

</html>