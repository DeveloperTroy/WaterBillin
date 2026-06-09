<?php
$servername = 'localhost';
$username = 'root';
$password = '';

$conn = mysqli_connect($servername, $username, $password);
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

$sql = 'CREATE DATABASE IF NOT EXISTS water_billing_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
if (!mysqli_query($conn, $sql)) {
    die('Database creation failed: ' . mysqli_error($conn));
}

mysqli_select_db($conn, 'water_billing_system');

$customersSql = "CREATE TABLE IF NOT EXISTS customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    email VARCHAR(255) DEFAULT NULL,
    phone VARCHAR(50) NOT NULL,
    meter_number VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$billsSql = "CREATE TABLE IF NOT EXISTS bills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    bill_date DATE NOT NULL,
    due_date DATE NOT NULL,
    `usage` INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('Paid', 'Due', 'Overdue') NOT NULL DEFAULT 'Due',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (!mysqli_query($conn, $customersSql)) {
    die('Customers table creation failed: ' . mysqli_error($conn));
}

if (!mysqli_query($conn, $billsSql)) {
    die('Bills table creation failed: ' . mysqli_error($conn));
}

$message = 'Database and tables are ready. You can now use the Water Billing System.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install Water Billing System</title>
    <style>
        body { font-family: Arial, sans-serif; background: #eef6ff; margin: 0; }
        .install-shell { max-width: 680px; margin: 120px auto; background: #fff; padding: 32px; border-radius: 24px; box-shadow: 0 30px 80px rgba(17,46,83,0.12); }
        h1 { margin-top: 0; }
        p { color: #33475b; line-height: 1.7; }
        a { color: #0d86ff; }
    </style>
</head>
<body>
    <div class="install-shell">
        <h1>Installation complete</h1>
        <p><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
        <p>Open <a href="index.php">index.php</a> to begin using the system.</p>
    </div>
</body>
</html>
