<?php
require_once __DIR__ . '/dbstudents.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$id = intval($_POST['id'] ?? 0);

if ($id <= 0) {
    header('Location: ../index.php?status=delete_error&section=delete');
    exit;
}

$stmt = $conn->prepare('DELETE FROM students WHERE id=?');
if (!$stmt) {
    header('Location: ../index.php?status=delete_error&section=delete');
    exit;
}

$stmt->bind_param('i', $id);
$stmt->execute();
// Check if any rows were actually deleted
if ($stmt->affected_rows > 0) {
    $success = true;
} else {
    $success = false;
}
$stmt->close();
$conn->close();

if ($success) {
    header('Location: ../index.php?status=delete_success&section=delete');
} else {
    header('Location: ../index.php?status=delete_error&section=delete');
}
exit;
