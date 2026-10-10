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
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function buatTabel(): void
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS buku_tamu (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                nama VARCHAR(100) NOT NULL,
                email VARCHAR(150) NOT NULL,
                pesan TEXT NOT NULL,
                tanggal_kirim DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );
    }

    public function simpan(string $nama, string $email, string $pesan): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO buku_tamu (nama, email, pesan, tanggal_kirim)
             VALUES (:nama, :email, :pesan, NOW())'
        );
 
        return $stmt->execute([
            ':nama'  => $nama,
            ':email' => $email,
            ':pesan' => $pesan,
        ]);
    }

    public function semua(): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, nama, email, pesan, tanggal_kirim
             FROM buku_tamu
             ORDER BY tanggal_kirim DESC, id DESC'
        );
        $stmt->execute();
 
        return $stmt->fetchAll();
    }
}