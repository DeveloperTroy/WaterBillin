<?php

function escape($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function navLinkClass($currentPage, $linkPage) {
    return $currentPage === $linkPage ? 'sidebar-link active' : 'sidebar-link';
}

function showMessage() {
    if (isset($_GET['success'])) {
        $text = escape($_GET['success']);
        echo '<div class="flash flash-success">' . $text . '</div>';
    }
    if (isset($_GET['error'])) {
        $text = escape($_GET['error']);
        echo '<div class="flash flash-error">' . $text . '</div>';
    }
}

function statusBadge($status) {
    $status = ucfirst(strtolower($status));
    $class = 'badge paid';
    if ($status === 'Due') {
        $class = 'badge due';
    } elseif ($status === 'Overdue') {
        $class = 'badge overdue';
    }
    return '<span class="' . $class . '">' . escape($status) . '</span>';
}

function formatCurrency($value) {
    return '$' . number_format((float)$value, 2);
}
