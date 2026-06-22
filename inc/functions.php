<?php
// Common functions helper

function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function formatDate($date, $format = 'd M Y') {
    if (!$date) return '';
    $timestamp = strtotime($date);
    $months = [
        'Jan' => 'Jan', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Apr',
        'May' => 'Mei', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Agu',
        'Sep' => 'Sep', 'Oct' => 'Okt', 'Nov' => 'Nov', 'Dec' => 'Des'
    ];
    $date = date($format, $timestamp);
    foreach ($months as $en => $id) {
        $date = str_replace($en, $id, $date);
    }
    return $date;
}

function getCurrentUser($pdo) {
    if (!isset($_SESSION['user_id'])) return null;
    $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function checkAuth($role = null) {
    if (empty($_SESSION['user_id'])) {
        header('Location: /bimbel/auth/login.php');
        exit;
    }
    if ($role && $_SESSION['role'] !== $role) {
        header('Location: /bimbel/');
        exit;
    }
}

function generateInvoice() {
    return 'INV-' . date('YmdHis') . '-' . strtoupper(substr(md5(rand()), 0, 6));
}

function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function redirectIfNotRole($role) {
    checkAuth();
    if ($_SESSION['role'] !== $role) {
        header('Location: /bimbel/');
        exit;
    }
}
?>
