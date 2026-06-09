<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$dbFile = __DIR__ . '/db.php';
echo 'Testing file: ' . $dbFile . '<br>';
echo 'Exists: ' . (file_exists($dbFile) ? 'yes' : 'no') . '<br>';
echo 'Readable: ' . (is_readable($dbFile) ? 'yes' : 'no') . '<br>';

include $dbFile;

if (isset($conn) && $conn) {
    echo 'Database connection successful.';
} else {
    echo 'Database connection failed: ' . mysqli_connect_error();
}
