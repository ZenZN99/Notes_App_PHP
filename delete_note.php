<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$id = (int) $_POST['id'];

$stmt = $conn->prepare("DELETE FROM notes WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $id, $_SESSION['user_id']);
$stmt->execute();


if ($stmt->affected_rows === 0) {
    die("Unauthorized or Note not found");
}

header("Location: notes.php");
exit();
