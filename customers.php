<?php
$page = 'customers';
$pageTitle = 'Customers';
require_once 'db.php';
include 'header.php';

if (isset($_GET['delete'])) {
    $customerId = intval($_GET['delete']);
    $stmt = mysqli_prepare($conn, "DELETE FROM customers WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $customerId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header('Location: customers.php?success=Customer+deleted+successfully');
    exit;
}

$columns = [];
$columnResult = mysqli_query($conn, 'SHOW COLUMNS FROM customers');
if ($columnResult) {
    while ($col = mysqli_fetch_assoc($columnResult)) {
        $columns[$col['Field']] = true;
    }
}

$customerNameKey = array_key_exists('name', $columns) ? 'name' : (array_key_exists('full_name', $columns) ? 'full_name' : (array_key_exists('customer_name', $columns) ? 'customer_name' : null));
$customerAddressKey = array_key_exists('address', $columns) ? 'address' : (array_key_exists('addr', $columns) ? 'addr' : (array_key_exists('location', $columns) ? 'location' : null));
$customerMeterKey = array_key_exists('meter_number', $columns) ? 'meter_number' : (array_key_exists('meter', $columns) ? 'meter' : null);
$customerPhoneKey = array_key_exists('phone', $columns) ? 'phone' : (array_key_exists('phone_number', $columns) ? 'phone_number' : null);

$customerQuery = mysqli_query($conn, 'SELECT * FROM customers ORDER BY created_at DESC');
$customers = [];
if ($customerQuery) {
    while ($row = mysqli_fetch_assoc($customerQuery)) {
        $customers[] = $row;
    }
}
?>

<section class="panel">
    <div class="panel-header">
        <h2>Customers</h2>
        <a href="add_customer.php" class="secondary-btn">Add new customer</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Meter</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= escape($customer[$customerNameKey] ?? '') ?></td>
                        <td><?= escape($customer[$customerMeterKey] ?? '') ?></td>
                        <td><?= escape($customer[$customerPhoneKey] ?? '') ?></td>
                        <td><?= escape($customer[$customerAddressKey] ?? '') ?></td>
                        <td><?= escape(isset($customer['created_at']) ? date('M j, Y', strtotime($customer['created_at'])) : '') ?></td>
                        <td>
                            <a class="ghost-btn" href="bills.php?customer_id=<?= $customer['id'] ?? '' ?>">New bill</a>
                            <button type="button" class="danger-btn delete-button" data-confirm="Delete customer <?= escape($customer[$customerNameKey] ?? 'this customer') ?>?" data-url="customers.php?delete=<?= $customer['id'] ?? '' ?>">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include 'footer.php'; ?>
