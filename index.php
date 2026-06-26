<?php

/**
 * =========================================================
 *  Entry Point : index.php
 *  Sistem      : UAS Pemrograman Berorientasi Objek
 *  Nama        : Rifki Pramudya Pangestu
 *  Kelas       : TRPL 1A
 * =========================================================
 *
 * File ini menjadi titik masuk utama sistem.
 * Mendemonstrasikan penggunaan koneksi database (PDO)
 * dan polimorfisme pada class Mahasiswa beserta subclass-nya.
 */

// ─── Load Konfigurasi & Model ─────────────────────────────────────────────
require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/models/Mahasiswa.php';
require_once __DIR__ . '/models/MahasiswaMandiri.php';
require_once __DIR__ . '/models/MahasiswaBidikmisi.php';
require_once __DIR__ . '/models/MahasiswaPrestasi.php';

// ─── Separator Helper ─────────────────────────────────────────────────────
function separator(string $judul = ''): void
{
    echo PHP_EOL . str_repeat('=', 55) . PHP_EOL;
    if ($judul !== '') {
        echo "  {$judul}" . PHP_EOL;
        echo str_repeat('-', 55) . PHP_EOL;
    }
}

// ─── 1. Uji Koneksi Database ──────────────────────────────────────────────
separator('UJI KONEKSI DATABASE');

try {
    $pdo = Database::getConnection();
    echo "✔ Koneksi ke database berhasil!" . PHP_EOL;
    echo "  DSN : MySQL | db_uas_pbo_trpl1a_rifkipramudyapangestu" . PHP_EOL;
} catch (PDOException $e) {
    echo "✘ " . $e->getMessage() . PHP_EOL;
    exit(1);
}

// ─── 2. Ambil Data dari Database & Instansiasi Objek ─────────────────────
separator('DATA MAHASISWA DARI DATABASE');

$stmt = $pdo->query("SELECT * FROM tabel_mahasiswa ORDER BY id_mahasiswa ASC");
$rows = $stmt->fetchAll();

/** @var Mahasiswa[] $daftarMahasiswa */
$daftarMahasiswa = [];

foreach ($rows as $row) {
    switch ($row['jenis_pembayaran']) {
        case 'Mandiri':
            $daftarMahasiswa[] = new MahasiswaMandiri(
                (int) $row['id_mahasiswa'],
                $row['nama_mahasiswa'],
                $row['nim'],
                (int) $row['semester'],
                (float) $row['tarif_ukt_nominal'],
                $row['golongan_ukt'],
                $row['nama_wali']
            );
            break;

        case 'Bidikmisi':
            $daftarMahasiswa[] = new MahasiswaBidikmisi(
                (int) $row['id_mahasiswa'],
                $row['nama_mahasiswa'],
                $row['nim'],
                (int) $row['semester'],
                (float) $row['tarif_ukt_nominal'],
                $row['nomor_kip_kuliah'],
                (float) $row['dana_saku_subsidi']
            );
            break;

        case 'Prestasi':
            $daftarMahasiswa[] = new MahasiswaPrestasi(
                (int) $row['id_mahasiswa'],
                $row['nama_mahasiswa'],
                $row['nim'],
                (int) $row['semester'],
                (float) $row['tarif_ukt_nominal'],
                $row['nama_instansi_beasiswa'],
                (float) $row['minimal_ipk_syarat']
            );
            break;
    }
}

echo "✔ Total " . count($daftarMahasiswa) . " data mahasiswa berhasil dimuat." . PHP_EOL;

// ─── 3. Tampilkan Detail Setiap Mahasiswa (Polimorfisme) ──────────────────
separator('DETAIL MAHASISWA (POLIMORFISME)');

foreach ($daftarMahasiswa as $mhs) {
    echo PHP_EOL;
    echo "ID          : " . $mhs->getIdMahasiswa()    . PHP_EOL;
    echo "Nama        : " . $mhs->getNamaMahasiswa()  . PHP_EOL;
    echo "NIM         : " . $mhs->getNim()            . PHP_EOL;
    echo "Semester    : " . $mhs->getSemester()       . PHP_EOL;
    echo "Jenis       : " . get_class($mhs)           . PHP_EOL;

    // Polimorfisme: tampilkan info spesifik tiap subclass
    $mhs->tampilkanSpesifikAkademik();

    // Polimorfisme: hitung tagihan sesuai jenis mahasiswa
    $tagihan = $mhs->hitungTagihanSemester();
    echo "Tagihan     : Rp " . number_format($tagihan, 2, ',', '.') . PHP_EOL;
    echo str_repeat('-', 55) . PHP_EOL;
}

// ─── 4. Tutup Koneksi ─────────────────────────────────────────────────────
Database::closeConnection();
separator('PROGRAM SELESAI');
echo "  Koneksi database telah ditutup." . PHP_EOL . PHP_EOL;
