<?php
// =====================================================================
// FILE   : classes/Penayangan3D.php
// PERAN  : Class turunan dari Penayangan (jenis tayangan #2)
// ISI    : Tayangan 3D. Menambah SATU atribut baru: biayaKacamata.
//          Total harga = harga tiket dasar + biaya kacamata.
// ALUR   : Di index.php, objek jenis ini disimpan di
//          $_SESSION['daftar3D'].
// =====================================================================

class Penayangan3D extends Penayangan {

    // 'private' karena tidak ada class lain yang mewarisi Penayangan3D.
    private $biayaKacamata;

    // ---- Constructor ----
    // Menerima 8 parameter milik Penayangan (parent) DITAMBAH satu
    // parameter baru: biayaKacamata (default 0).
    public function __construct($judulFilm = "", $genre = "", $durasiMenit = 0, $fotoFilm = "",
                                $tanggal = "", $jam = "", $namaStudio = "", $hargaTiket = 0,
                                $biayaKacamata = 0) {
        // parent::__construct(...) membangun bagian Penayangan (dan otomatis
        // InformasiFilm + trait InformasiJadwal di baliknya) lebih dulu.
        parent::__construct($judulFilm, $genre, $durasiMenit, $fotoFilm, $tanggal, $jam, $namaStudio, $hargaTiket);
        $this->biayaKacamata = $biayaKacamata;
    }

    // Setter & getter untuk atribut khusus 3D
    public function setBiayaKacamata($biayaKacamata) { $this->biayaKacamata = $biayaKacamata; }
    public function getBiayaKacamata() { return $this->biayaKacamata; }

    // Redefinisi (override) method khusus untuk objek bertipe Penayangan3D.

    public function getJenis() {
        return "3D";
    }

    public function getInfoTambahan() {
        // Contoh hasil: "Kacamata 3D (+Rp15000)"
        // Di PHP, angka digabung ke string otomatis diubah jadi teks
        // saat memakai operator '.' (concatenation), jadi tidak perlu
        // fungsi seperti to_string() di C++.
        return "Kacamata 3D (+Rp" . $this->biayaKacamata . ")";
    }

    public function getTotalHarga() {
        // Total = harga tiket dasar (warisan dari Penayangan) + biaya kacamata
        return $this->hargaTiket + $this->biayaKacamata;
    }
}
