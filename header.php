<?php
require_once 'db.php';
require_once 'functions.php';

/** @var mysqli $conn */

if (!isset($page)) {
    $page = 'dashboard';
}

$pageTitle = $pageTitle ?? 'Water Billing System';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escape($pageTitle) ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <div class="brand-panel">
                <div class="brand-mark">W</div>
                <div>
                    <p class="brand-tag">Water Billing</p>
                    <p class="brand-subtitle">Utility management</p>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="index.php" class="<?= navLinkClass($page, 'dashboard') ?>">Dashboard</a>
                <a href="customers.php" class="<?= navLinkClass($page, 'customers') ?>">Customers</a>
                <a href="add_customer.php" class="<?= navLinkClass($page, 'add_customer') ?>">Add Customer</a>
                <a href="bills.php" class="<?= navLinkClass($page, 'bills') ?>">Bills</a>
            </nav>

            <div class="sidebar-footer">
                <p>Need help?</p>
                <a href="mailto:abdulrahmankimbo@gmail.com" class="support-link">abdulrahmankimbo@gmail.com</a>
            </div>
        </aside>

        <div class="main-panel">
            <header class="topbar">
                <div>
                    <p class="hello-label">Welcome back</p>
                    <h1 class="page-title"><?= escape($pageTitle) ?></h1>
                </div>
                <div class="topbar-actions">
                    <button type="button" class="theme-switch">Switch Theme</button>
                    <a href="add_customer.php" class="primary-btn">Create Customer</a>
                </div>
            </header>

            <?php showMessage(); ?>
