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
