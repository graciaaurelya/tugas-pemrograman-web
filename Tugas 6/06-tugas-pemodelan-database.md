# Tugas Modul 6: Perancangan ERD E-Library Kampus

**Nama:** Gracia Aurelya
**NIM:** D121241095
**Mata Kuliah:** Pemrograman Website

## 1. Skenario

Basis data relasional dirancang untuk sistem peminjaman buku perpustakaan kampus. Sistem mencatat data mahasiswa, buku, penerbit, serta riwayat peminjaman dan pengembalian.

Asumsi perancangan:

1. Satu mahasiswa dapat meminjam banyak buku, dan satu buku dapat dipinjam banyak mahasiswa pada waktu berbeda (relasi many to many, diurai oleh tabel `peminjaman`).
2. Satu penerbit menerbitkan banyak buku, tetapi satu buku hanya memiliki satu penerbit.
3. Satu baris `peminjaman` mewakili satu buku yang dipinjam oleh satu mahasiswa pada satu tanggal pinjam.
4. Kolom `tgl_kembali` bernilai NULL selama buku belum dikembalikan.
5. Penamaan mengikuti standar modul: huruf kecil dengan garis bawah, nama tabel berupa kata benda tunggal, dan nama Foreign Key sama dengan Primary Key yang dirujuk.

