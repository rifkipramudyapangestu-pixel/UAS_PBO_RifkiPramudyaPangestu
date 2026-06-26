<?php

require_once __DIR__ . '/Mahasiswa.php';

class MahasiswaPrestasi extends Mahasiswa
{
    /** @var string Kolom: nama_instansi_beasiswa */
    protected string $namaInstansiBeasiswa;

    /** @var float Kolom: minimal_ipk_syarat */
    protected float $minimalIpkSyarat;

    public function __construct(
        ?int $id_mahasiswa,
        string $nama_mahasiswa,
        string $nim,
        int $semester,
        float $tarifUktNominal,
        string $namaInstansiBeasiswa,
        float $minimalIpkSyarat
    ) {
        parent::__construct(
            $id_mahasiswa,
            $nama_mahasiswa,
            $nim,
            $semester,
            $tarifUktNominal
        );

        $this->namaInstansiBeasiswa = $namaInstansiBeasiswa;
        $this->minimalIpkSyarat     = $minimalIpkSyarat;
    }

    public function hitungTagihanSemester(): float
    {
        return $this->tarifUktNominal * 0.25;
    }

    public function tampilkanSpesifikAkademik(): void
    {
        echo "Instansi Beasiswa : {$this->namaInstansiBeasiswa}" . PHP_EOL;
        echo "Minimal IPK       : {$this->minimalIpkSyarat}" . PHP_EOL;
    }

    public function getNamaInstansiBeasiswa(): string
    {
        return $this->namaInstansiBeasiswa;
    }

    public function getMinimalIpkSyarat(): float
    {
        return $this->minimalIpkSyarat;
    }
}
