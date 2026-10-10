<?php
declare(strict_types=1);
 
session_start();
require_once __DIR__ . '/GuestBook.php';
 
// Konfigurasi koneksi database (sesuaikan dengan lingkungan lokal)
$host = 'localhost';
$db   = 'perpustakaan';
$user = 'root';
$pass = '';
 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $guestBook = new GuestBook($pdo);
    $guestBook->buatTabel();
} catch (PDOException $e) {
    http_response_code(500);
    exit('Koneksi database gagal.');
}
 
// Helper sanitasi keluaran
function e(string $teks): string
{
    return htmlspecialchars($teks, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
 
// Token CSRF
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
 
$errors = [];
$old = ['nama' => '', 'email' => '', 'pesan' => ''];
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        exit('Token CSRF tidak valid.');
    }

    $nama  = trim((string) ($_POST['nama'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $pesan = trim((string) ($_POST['pesan'] ?? ''));
    $old   = ['nama' => $nama, 'email' => $email, 'pesan' => $pesan];
 
    // Validasi masukan
    if ($nama === '') {
        $errors[] = 'Nama tidak boleh kosong.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid.';
    }
    if (mb_strlen($pesan, 'UTF-8') < 5) {
        $errors[] = 'Pesan minimal lima karakter.';
    }
 
    if (!$errors) {
        $guestBook->simpan($nama, $email, $pesan);
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // perbarui token
        $_SESSION['flash'] = 'Pesan berhasil dikirim.';
        header('Location: guestbook.php'); // pola Post/Redirect/Get
        exit;
    }
}
 
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$daftarPesan = $guestBook->semua();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buku Tamu Perpustakaan</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 2rem auto; padding: 0 1rem; }
        label { display: block; margin-top: .8rem; font-weight: bold; }
        input, textarea { width: 100%; padding: .5rem; box-sizing: border-box; }
        button { margin-top: 1rem; padding: .6rem 1.2rem; }
        .error { background: #fdecea; color: #b71c1c; padding: .6rem 1rem; margin-top: 1rem; }
        .success { background: #e8f5e9; color: #1b5e20; padding: .6rem 1rem; margin-top: 1rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; }
        th, td { border: 1px solid #ccc; padding: .5rem; text-align: left; vertical-align: top; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Buku Tamu Perpustakaan</h1>
 
    <?php if ($flash): ?>
        <div class="success"><?= e($flash) ?></div>
    <?php endif; ?>
 
    <?php if ($errors): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= e($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
 
    <form method="post" action="guestbook.php" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
 
        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama" maxlength="100" value="<?= e($old['nama']) ?>">
 
        <label for="email">Email</label>
        <input type="email" id="email" name="email" maxlength="150" value="<?= e($old['email']) ?>">
 
        <label for="pesan">Pesan</label>
        <textarea id="pesan" name="pesan" rows="4"><?= e($old['pesan']) ?></textarea>
 
        <button type="submit">Kirim</button>
    </form>
 
    <h2>Daftar Pesan</h2>
    <table>
        <thead>
            <tr><th>No</th><th>Nama</th><th>Email</th><th>Pesan</th><th>Tanggal</th></tr>
        </thead>
        <tbody>
            <?php if (!$daftarPesan): ?>
                <tr><td colspan="5">Belum ada pesan.</td></tr>
            <?php endif; ?>
            <?php foreach ($daftarPesan as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= e($row['nama']) ?></td>
                    <td><?= e($row['email']) ?></td>
                    <td><?= nl2br(e($row['pesan'])) ?></td>
                    <td><?= e($row['tanggal_kirim']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
