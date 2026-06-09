<?php
$page = 'bills';
$pageTitle = 'Bills';
require_once 'db.php';
include 'header.php';

if (isset($_GET['delete'])) {
    $billId = intval($_GET['delete']);
    $stmt = mysqli_prepare($conn, "DELETE FROM bills WHERE id = ?");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $billId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header('Location: bills.php?success=Bill+removed+successfully');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerId = intval($_POST['customer_id'] ?? 0);
    $billDate = $_POST['bill_date'] ?? '';
    $dueDate = $_POST['due_date'] ?? '';
    $usage = intval($_POST['usage'] ?? 0);
    $amount = floatval($_POST['amount'] ?? 0);
    $status = $_POST['status'] ?? 'Due';
    $notes = trim($_POST['notes'] ?? '');

    if ($customerId === 0 || $billDate === '' || $dueDate === '' || $usage <= 0 || $amount <= 0) {
        header('Location: bills.php?error=Please+fill+all+required+bill+fields');
        exit;
    }

    $status = in_array($status, ['Paid', 'Due', 'Overdue']) ? $status : 'Due';

    // Use prepared statements
    $stmt = mysqli_prepare($conn, "INSERT INTO bills (customer_id, bill_date, due_date, `usage`, amount, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        header('Location: bills.php?error=Database+error');
        exit;
    }
    
    mysqli_stmt_bind_param($stmt, "issidss", $customerId, $billDate, $dueDate, $usage, $amount, $status, $notes);
    if (mysqli_stmt_execute($stmt)) {
        header('Location: bills.php?success=Bill+added+successfully');
        exit;
    }
    
    mysqli_stmt_close($stmt);
    header('Location: bills.php?error=Unable+to+save+bill');
    exit;
}

$customerOptionsResult = mysqli_query($conn, 'SELECT id, customer_name FROM customers ORDER BY customer_name ASC');
$customerOptions = [];
if ($customerOptionsResult) {
    while ($row = mysqli_fetch_assoc($customerOptionsResult)) {
        $customerOptions[] = $row;
    }
}

$recentBillsResult = mysqli_query($conn, 'SELECT bills.*, customers.customer_name AS customer_name FROM bills JOIN customers ON bills.customer_id = customers.id ORDER BY bill_date DESC LIMIT 8');
$recentBills = [];
if ($recentBillsResult) {
    while ($row = mysqli_fetch_assoc($recentBillsResult)) {
        $recentBills[] = $row;
    }
}
?>

<section class="main-grid">
    <article class="panel">
        <div class="panel-header">
            <h2>Recent bills</h2>
            <a href="add_customer.php" class="secondary-btn">Add customer</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Bill</th>
                        <th>Due</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentBills as $bill): ?>
                        <tr>
                            <td><?= escape($bill['customer_name']) ?></td>
                            <td><?= escape(date('M j', strtotime($bill['bill_date']))) ?></td>
                            <td><?= escape(date('M j', strtotime($bill['due_date']))) ?></td>
                            <td><?= statusBadge($bill['status']) ?></td>
                            <td>TSh <?= escape(number_format($bill['amount'], 2)) ?></td>
                            <td>
                                <button type="button" class="danger-btn delete-button" data-confirm="Delete bill for <?= escape($bill['customer_name']) ?>?" data-url="bills.php?delete=<?= $bill['id'] ?>">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </article>

    <article class="panel form-card">
        <div class="panel-header">
            <h2>Create a new bill</h2>
        </div>
        <form method="post" class="form-grid">
            <div class="input-group">
                <label for="customer_id">Customer</label>
                <select id="customer_id" name="customer_id" required>
                    <option value="">Select customer</option>
                    <?php foreach ($customerOptions as $customer): ?>
                        <option value="<?= $customer['id'] ?>"><?= escape($customer['customer_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-group">
                <label for="bill_date">Bill date</label>
                <input id="bill_date" name="bill_date" type="date" required>
            </div>
            <div class="input-group">
                <label for="due_date">Due date</label>
                <input id="due_date" name="due_date" type="date" required>
            </div>
            <div class="input-group">
                <label for="usage">Usage (m³)</label>
                <input id="usage" name="usage" type="number" min="1" required>
            </div>
            <div class="input-group">
                <label for="amount">Amount</label>
                <input id="amount" name="amount" type="number" step="0.01" min="0" required>
            </div>
            <div class="input-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="Due">Due</option>
                    <option value="Paid">Paid</option>
                    <option value="Overdue">Overdue</option>
                </select>
            </div>
            <div class="input-group input-full">
                <label for="notes">Notes</label>
                <textarea id="notes" name="notes" rows="4" placeholder="Optional details"></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="primary-btn">Save bill</button>
            </div>
        </form>
    </article>
</section>

<?php include 'footer.php'; ?>
