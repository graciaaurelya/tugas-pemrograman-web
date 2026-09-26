<?php

declare(strict_types=1);

require_once './Transaction.php';

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$_SESSION['balance'] ??= 0.0;
$_SESSION['transactions'] ??= [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postToken = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $postToken)) {
        die('Kesalahan Keamanan: Token CSRF tidak cocok.');
    }
}

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
    <form method="POST" action="finance.php">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        <label for="type">Jenis Transaksi</label>
        <select name="type" id="type">
            <option value="deposit">Deposit</option>
            <option value="withdraw">Penarikan</option>
        </select>

        <label for="amount">Jumlah</label>
        <input type="text" name="amount" id="amount">

        <button type="submit">Proses Transaksi</button>
    </form>

</body>

</html>