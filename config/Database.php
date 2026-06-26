<?php

/**
 * Class : Database
 * Fungsi : Mengelola koneksi ke database MySQL menggunakan PDO.
 *
 * Menggunakan pola Singleton agar hanya ada satu instance
 * koneksi database yang aktif selama aplikasi berjalan.
 */
class Database
{
    // ─── Konfigurasi Koneksi ───────────────────────────────────────────────
    private static string $host     = 'localhost';
    private static string $port     = '3306';
    private static string $dbName   = 'db_uas_pbo_trpl1a_rifkipramudyapangestu';
    private static string $username = 'root';
    private static string $password = '';
    private static string $charset  = 'utf8mb4';

    // ─── Singleton Instance ────────────────────────────────────────────────
    private static ?PDO $instance = null;

    /**
     * Constructor dibuat private agar tidak bisa diinstansiasi langsung.
     * Gunakan Database::getConnection() untuk mendapatkan koneksi.
     */
    private function __construct() {}

    /**
     * Mengembalikan satu instance koneksi PDO (Singleton Pattern).
     *
     * @return PDO Instance koneksi ke database.
     * @throws PDOException Jika koneksi ke database gagal.
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                self::$host,
                self::$port,
                self::$dbName,
                self::$charset
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, self::$username, self::$password, $options);
            } catch (PDOException $e) {
                // Lempar ulang dengan pesan yang lebih informatif
                throw new PDOException(
                    '[Database Error] Koneksi gagal: ' . $e->getMessage(),
                    (int) $e->getCode()
                );
            }
        }

        return self::$instance;
    }

    /**
     * Menutup / mereset koneksi database yang aktif.
     * Berguna saat ingin me-refresh koneksi.
     */
    public static function closeConnection(): void
    {
        self::$instance = null;
    }
}
