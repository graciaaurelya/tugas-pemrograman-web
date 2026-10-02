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

## 2. Desain ERD

### 2.1 Entitas dan Atribut

| Entitas | Atribut | Primary Key | Foreign Key |
|---|---|---|---|
| `penerbit` | penerbit_id, nama_penerbit, kota | penerbit_id | tidak ada |
| `buku` | buku_id, isbn, judul, tahun_terbit, stok, penerbit_id | buku_id | penerbit_id → penerbit.penerbit_id |
| `mahasiswa` | nim, nama_mhs, prodi | nim | tidak ada |
| `peminjaman` | peminjaman_id, nim, buku_id, tgl_pinjam, tgl_jatuh_tempo, tgl_kembali | peminjaman_id | nim → mahasiswa.nim, buku_id → buku.buku_id |

### 2.2 Relasi Antar Entitas

| Relasi | Kardinalitas | Keterangan |
|---|---|---|
| penerbit ke buku | 1 : N | Satu penerbit menerbitkan banyak buku |
| mahasiswa ke peminjaman | 1 : N | Satu mahasiswa dapat melakukan banyak peminjaman |
| buku ke peminjaman | 1 : N | Satu buku dapat muncul pada banyak riwayat peminjaman |
| mahasiswa ke buku | M : N | Diurai oleh tabel `peminjaman` sebagai tabel penghubung |

# 3. Simulasi Normalisasi

### 3.1 Bentuk Tidak Normal (UNF)

Seluruh data dicatat dalam satu tabel. Kolom terakhir berisi kelompok data berulang (lebih dari satu buku dalam satu sel).

| NIM | Nama_Mhs | Prodi | Buku Dipinjam {ISBN, Judul, Tahun, Stok, Penerbit, Kota_Penerbit, Tgl_Pinjam, Jatuh_Tempo, Tgl_Kembali} |
|---|---|---|---|
| D121241001 | Rian | Teknik Informatika | {9786020001001, Basis Data, 2020, 5, Informatika, Bandung, 01 Sep 2026, 08 Sep 2026, 07 Sep 2026}, {9786020002002, Algoritma, 2019, 3, Gramedia, Jakarta, 01 Sep 2026, 08 Sep 2026, 07 Sep 2026} |
| D121241002 | Akbar | Teknik Elektro | {9786020001001, Basis Data, 2020, 5, Informatika, Bandung, 03 Sep 2026, 10 Sep 2026, NULL} |

Masalah: kolom terakhir memuat banyak nilai dalam satu sel, sehingga tabel belum memenuhi syarat paling dasar.
