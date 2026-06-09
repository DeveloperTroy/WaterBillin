<?php
$page = 'dashboard';
$pageTitle = 'Dashboard';
require_once 'db.php';
include 'header.php';

$totalCustomersResult = mysqli_query($conn, 'SELECT COUNT(*) AS count FROM customers');
$totalCustomers = $totalCustomersResult ? mysqli_fetch_assoc($totalCustomersResult)['count'] : 0;

$activeBillsResult = mysqli_query($conn, "SELECT COUNT(*) AS count FROM bills WHERE status <> 'Paid'");
$activeBills = $activeBillsResult ? mysqli_fetch_assoc($activeBillsResult)['count'] : 0;

$revenueResult = mysqli_query($conn, "SELECT IFNULL(SUM(amount), 0) AS total FROM bills WHERE status = 'Paid'");
$revenue = $revenueResult ? mysqli_fetch_assoc($revenueResult)['total'] : 0;

$latestBillsResult = mysqli_query($conn, 'SELECT bills.*, customers.customer_name AS customer_name FROM bills JOIN customers ON bills.customer_id = customers.id ORDER BY bill_date DESC LIMIT 6');
$latestBills = [];
if ($latestBillsResult) {
    while ($row = mysqli_fetch_assoc($latestBillsResult)) {
        $latestBills[] = $row;
    }
}

$latestCustomersResult = mysqli_query($conn, 'SELECT * FROM customers ORDER BY created_at DESC LIMIT 4');
$latestCustomers = [];
if ($latestCustomersResult) {
    while ($row = mysqli_fetch_assoc($latestCustomersResult)) {
        $latestCustomers[] = $row;
    }
}
?>

<section class="hero-card">
    <div class="hero-copy">
        <p class="eyebrow">Dashboard overview</p>
        <h2>Everything you need to manage water billing.</h2>
        <p>Track customers, add new accounts, issue bills, and monitor payment status from one connected dashboard.</p>
        <div class="hero-actions">
            <a href="customers.php" class="secondary-btn">Customers</a>
            <a href="bills.php" class="secondary-btn">Bills</a>
            <a href="add_customer.php" class="primary-btn">Add customer</a>
        </div>
    </div>
</section>

<section class="cards-grid">
    <article class="summary-card">
        <p>Total customers</p>
        <h3><?= escape($totalCustomers) ?></h3>
    </article>
    <article class="summary-card">
        <p>Unpaid / overdue bills</p>
        <h3><?= escape($activeBills) ?></h3>
    </article>
    <article class="summary-card">
        <p>Paid revenue (TSh)</p>
        <h3>TSh <?= number_format((float)$revenue, 2) ?></h3>
    </article>
</section>

<section class="content-grid">
    <article class="panel">
        <div class="panel-header">
            <h2>Recent invoices</h2>
            <a href="bills.php" class="ghost-btn">See all</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Bill date</th>
                        <th>Status</th>
                        <th>Amount (TSh)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($latestBills as $bill): ?>
                        <tr>
                            <td><?= escape($bill['customer_name']) ?></td>
                            <td><?= escape(date('M j, Y', strtotime($bill['bill_date']))) ?></td>
                            <td><?= statusBadge($bill['status']) ?></td>
                            <td>TSh <?= escape(number_format($bill['amount'], 2)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </article>

    <article class="panel">
        <div class="panel-header">
            <h2>Newest customers</h2>
            <a href="customers.php" class="ghost-btn">View all</a>
        </div>
        <ul class="customer-list">
            <?php foreach ($latestCustomers as $customer): ?>
                <li>
                    <div>
                        <strong><?= escape($customer['customer_name']) ?></strong>
                        <p><?= escape($customer['meter_number']) ?> • <?= escape($customer['phone']) ?></p>
                    </div>
                    <span><?= escape(date('M j', strtotime($customer['created_at']))) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </article>
</section>

<?php include 'footer.php'; ?>
