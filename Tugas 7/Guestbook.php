<?php
declare(strict_types=1);
 
/**
 * Kelas GuestBook: menyimpan dan mengambil pesan buku tamu lewat PDO.
 *
 * Skema tabel:
 *
 * CREATE TABLE IF NOT EXISTS buku_tamu (
 *     id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *     nama          VARCHAR(100) NOT NULL,
 *     email         VARCHAR(150) NOT NULL,
 *     pesan         TEXT NOT NULL,
 *     tanggal_kirim DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 */
class GuestBook
{
    private PDO $pdo;
 
    public function __construct(PDO $pdo)
    {

    }

    public function buatTabel(): void
    {
    }
}