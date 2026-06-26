<?php

require_once __DIR__ . '/Mahasiswa.php';

class MahasiswaBidikmisi extends Mahasiswa
{
    /** @var string Kolom: nomor_kip_kuliah */
    protected string $nomorKipKuliah;

    /** @var float Kolom: dana_saku_subsidi */
    protected float $danaSakuSubsidi;

    public function __construct(
        ?int $id_mahasiswa,
        string $nama_mahasiswa,
        string $nim,
        int $semester,
        float $tarifUktNominal,
        string $nomorKipKuliah,
        float $danaSakuSubsidi
    ) {
        parent::__construct(
            $id_mahasiswa,
            $nama_mahasiswa,
            $nim,
            $semester,
            $tarifUktNominal
        );

        $this->nomorKipKuliah = $nomorKipKuliah;
        $this->danaSakuSubsidi = $danaSakuSubsidi;
    }

    public function hitungTagihanSemester(): float
    {
        return $this->tarifUktNominal;
    }

    public function tampilkanSpesifikAkademik(): void
    {
        echo "Nomor KIP Kuliah  : {$this->nomorKipKuliah}" . PHP_EOL;
        echo "Dana Saku Subsidi : {$this->formatRupiah($this->danaSakuSubsidi)}" . PHP_EOL;
    }

    public function getNomorKipKuliah(): string
    {
        return $this->nomorKipKuliah;
    }

    public function getDanaSakuSubsidi(): float
    {
        return $this->danaSakuSubsidi;
    }
}
