<?php
$page = 'add_customer';
$pageTitle = 'Add Customer';
require_once 'db.php';
include 'header.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $meter = trim($_POST['meter_number'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($name === '' || $phone === '' || $meter === '') {
        $error = 'Please complete every required field.';
    } else {
        // Use prepared statements to prevent SQL injection
        $stmt = mysqli_prepare($conn, "INSERT INTO customers (customer_name, created_at, email, phone, meter_number) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            $error = 'Database error: ' . mysqli_error($conn);
        } else {
            $createdAt = date('Y-m-d H:i:s');
            mysqli_stmt_bind_param($stmt, "sssss", $name, $createdAt, $email, $phone, $meter);
            if (mysqli_stmt_execute($stmt)) {
                header('Location: customers.php?success=Customer+added+successfully');
                exit;
            } else {
                $error = 'Error: ' . mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<section class="panel form-card">
    <div class="panel-header">
        <h2>Create a new customer</h2>
        <a href="customers.php" class="ghost-btn">View customers</a>
    </div>

    <?php if ($error): ?>
        <div class="flash flash-error"><?= escape($error) ?></div>
    <?php endif; ?>

    <form method="post" class="form-grid">
        <div class="input-group">
            <label for="name">Full name</label>
            <input id="name" name="name" type="text" placeholder="Jane Doe" required>
        </div>
        <div class="input-group">
            <label for="meter_number">Meter number</label>
            <input id="meter_number" name="meter_number" type="text" placeholder="A12-3456" required>
        </div>
        <div class="input-group">
            <label for="phone">Phone</label>
            <input id="phone" name="phone" type="tel" placeholder="555-123-8900" required>
        </div>
        <div class="input-group">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" placeholder="jane@example.com">
        </div>
        <div class="form-actions">
            <button type="submit" class="primary-btn">Save Customer</button>
        </div>
    </form>
</section>

<?php include 'footer.php'; ?>
