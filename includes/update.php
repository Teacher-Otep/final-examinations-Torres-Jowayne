<?php
require_once __DIR__ . '/dbstudents.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$id         = intval($_POST['id'] ?? 0);
$surname    = trim($_POST['surname'] ?? '');
$name       = trim($_POST['name'] ?? '');
$middlename = trim($_POST['middlename'] ?? '');
$address    = trim($_POST['address'] ?? '');
$contact    = trim($_POST['contact_number'] ?? '');

if ($id <= 0 || $surname === '' || $name === '') {
    header('Location: ../index.php?status=update_error&section=update');
    exit;
}

$stmt = $conn->prepare('UPDATE students SET surname=?, name=?, middlename=?, address=?, contact_number=? WHERE id=?');
if (!$stmt) {
    header('Location: ../index.php?status=update_error&section=update');
    exit;
}

$stmt->bind_param('sssssi', $surname, $name, $middlename, $address, $contact, $id);
$success = $stmt->execute();
$stmt->close();
$conn->close();

if ($success) {
    header('Location: ../index.php?status=update_success&section=update');
} else {
    header('Location: ../index.php?status=update_error&section=update');
}
exit;
