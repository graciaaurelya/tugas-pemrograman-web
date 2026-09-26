<?php
 
declare(strict_types=1);
 
require_once './Transaction.php';
 
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
 
$_SESSION['balance'] ??= 0.0;
$_SESSION['transactions'] ??= [];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Manajemen Keuangan Sederhana</title>
</head>
<body>
    <h1>Sistem Manajemen Keuangan Sederhana</h1>
    <p>Saldo saat ini: Rp <?= number_format((float) $_SESSION['balance'], 2, ',', '.') ?></p>
</body>
</html>