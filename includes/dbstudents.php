<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'dbstudents';

$conn = new mysqli($db_host, $db_user, $db_pass);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

if (! $conn->select_db($db_name)) {
    $createDbSql = "CREATE DATABASE IF NOT EXISTS `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if (! $conn->query($createDbSql)) {
        die('Database creation failed: ' . $conn->error);
    }
    $conn->select_db($db_name);
}

$conn->set_charset('utf8mb4');


$createTableSql = "CREATE TABLE IF NOT EXISTS students (
    id INT(11) NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    surname VARCHAR(100) NOT NULL,
    middlename VARCHAR(100) DEFAULT NULL,
    address TEXT DEFAULT NULL,
    contact_number VARCHAR(20) DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (! $conn->query($createTableSql)) {
    die('Table creation failed: ' . $conn->error);
}


$checkCol = $conn->query(
    "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
     WHERE TABLE_SCHEMA = '$db_name'
       AND TABLE_NAME   = 'students'
       AND COLUMN_NAME  = 'contact'"
);
if ($checkCol && $checkCol->num_rows > 0) {
    $conn->query(
        "ALTER TABLE students
         CHANGE COLUMN `contact` `contact_number` VARCHAR(20) DEFAULT NULL"
    );
}
?>
