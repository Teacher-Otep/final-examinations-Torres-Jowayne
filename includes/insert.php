<?php
require_once __DIR__ . '/dbstudents.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$surname        = trim($_POST['surname'] ?? '');
$name           = trim($_POST['name'] ?? '');
$middlename     = trim($_POST['middlename'] ?? '');
$address        = trim($_POST['address'] ?? '');
$contact_number = trim($_POST['contact_number'] ?? '');

if ($surname === '' || $name === '') {
    header('Location: ../index.php?status=error');
    exit;
}

$stmt = $conn->prepare('INSERT INTO students (surname, name, middlename, address, contact_number) VALUES (?, ?, ?, ?, ?)');
if (! $stmt) {
    header('Location: ../index.php?status=error');
    exit;
}

$stmt->bind_param('sssss', $surname, $name, $middlename, $address, $contact_number);
$success = $stmt->execute();
$stmt->close();
$conn->close();

if ($success) {
    header('Location: ../index.php?status=success');
} else {
    header('Location: ../index.php?status=error');
}
exit;
