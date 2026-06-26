<?php

/**
 * Abstract Class : Mahasiswa
 * Representasi   : Data umum dari tabel `tabel_mahasiswa`
 *
 * Kelas ini menjadi cetak biru untuk jenis mahasiswa berdasarkan
 * pembiayaannya: Mandiri, Bidikmisi, dan Prestasi.
 */
abstract class Mahasiswa
{
    /** @var int|null Kolom: id_mahasiswa (Primary Key) */
    protected ?int $id_mahasiswa;

    /** @var string Kolom: nama_mahasiswa */
    protected string $nama_mahasiswa;

    /** @var string Kolom: nim */
    protected string $nim;

    /** @var int Kolom: semester */
    protected int $semester;

    /** @var float Kolom: tarif_ukt_nominal */
    protected float $tarifUktNominal;

    public function __construct(
        ?int $id_mahasiswa,
        string $nama_mahasiswa,
        string $nim,
        int $semester,
        float $tarifUktNominal
    ) {
        $this->id_mahasiswa   = $id_mahasiswa;
        $this->nama_mahasiswa = $nama_mahasiswa;
        $this->nim            = $nim;
        $this->semester       = $semester;
        $this->tarifUktNominal = $tarifUktNominal;
    }

    abstract public function hitungTagihanSemester(): float;

    abstract public function tampilkanSpesifikAkademik(): void;

    public function getIdMahasiswa(): ?int
    {
        return $this->id_mahasiswa;
    }

    public function getNamaMahasiswa(): string
    {
        return $this->nama_mahasiswa;
    }

    public function getNim(): string
    {
        return $this->nim;
    }

    public function getSemester(): int
    {
        return $this->semester;
    }

    public function getTarifUktNominal(): float
    {
        return $this->tarifUktNominal;
    }

    protected function formatRupiah(float $nominal): string
    {
        return 'Rp ' . number_format($nominal, 2, ',', '.');
    }
}
