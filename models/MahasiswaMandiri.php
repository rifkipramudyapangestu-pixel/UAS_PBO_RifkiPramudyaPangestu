<?php

require_once __DIR__ . '/Mahasiswa.php';

class MahasiswaMandiri extends Mahasiswa
{
    /** @var string Kolom: golongan_ukt */
    protected string $golonganUkt;

    /** @var string Kolom: nama_wali */
    protected string $namaWali;

    public function __construct(
        ?int $id_mahasiswa,
        string $nama_mahasiswa,
        string $nim,
        int $semester,
        float $tarifUktNominal,
        string $golonganUkt,
        string $namaWali
    ) {
        parent::__construct(
            $id_mahasiswa,
            $nama_mahasiswa,
            $nim,
            $semester,
            $tarifUktNominal
        );

        $this->golonganUkt = $golonganUkt;
        $this->namaWali    = $namaWali;
    }

    public function hitungTagihanSemester(): float
    {
        return $this->tarifUktNominal + 100000;
    }

    public function tampilkanSpesifikAkademik(): void
    {
        echo "Golongan UKT : {$this->golonganUkt}" . PHP_EOL;
        echo "Nama Wali    : {$this->namaWali}" . PHP_EOL;
    }

    public function getGolonganUkt(): string
    {
        return $this->golonganUkt;
    }

    public function getNamaWali(): string
    {
        return $this->namaWali;
    }
}
