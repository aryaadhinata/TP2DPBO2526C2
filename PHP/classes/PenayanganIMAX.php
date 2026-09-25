<?php
// =====================================================================
// FILE   : classes/PenayanganIMAX.php
// PERAN  : Class turunan dari Penayangan (jenis tayangan #3)
// ISI    : Tayangan IMAX. Menambah DUA atribut baru: ukuran layar dan
//          biaya premium. Total harga = harga tiket dasar + biaya premium.
// ALUR   : Di index.php, objek jenis ini disimpan di
//          $_SESSION['daftarIMAX'].
// =====================================================================

class PenayanganIMAX extends Penayangan {

    private $ukuranLayarMeter;   // lebar layar dalam meter (boleh desimal, mis. 22.5)
    private $biayaPremium;       // biaya tambahan khusus IMAX (dalam Rupiah)

    // ---- Constructor ----
    // Menerima 8 parameter milik Penayangan DITAMBAH dua parameter baru.
    public function __construct($judulFilm = "", $genre = "", $durasiMenit = 0, $fotoFilm = "",
                                $tanggal = "", $jam = "", $namaStudio = "", $hargaTiket = 0,
                                $ukuranLayarMeter = 0.0, $biayaPremium = 0) {
        parent::__construct($judulFilm, $genre, $durasiMenit, $fotoFilm, $tanggal, $jam, $namaStudio, $hargaTiket);
        $this->ukuranLayarMeter = $ukuranLayarMeter;
        $this->biayaPremium = $biayaPremium;
    }

    // Setter & getter untuk atribut khusus IMAX
    public function setUkuranLayarMeter($v) { $this->ukuranLayarMeter = $v; }
    public function getUkuranLayarMeter() { return $this->ukuranLayarMeter; }

    public function setBiayaPremium($v) { $this->biayaPremium = $v; }
    public function getBiayaPremium() { return $this->biayaPremium; }

    // Redefinisi (override) method khusus untuk objek bertipe PenayanganIMAX.

    public function getJenis() {
        return "IMAX";
    }

    public function getInfoTambahan() {
        // Contoh hasil: "Layar 22m (+Rp35000)"
        // (int) $this->ukuranLayarMeter membuang bagian desimal: 22.5
        // tampil sebagai 22, karena angka desimal apa adanya akan
        // menghasilkan "22.5" seperti versi C++/Java/Python.
        return "Layar " . (int) $this->ukuranLayarMeter . "m (+Rp" . $this->biayaPremium . ")";
    }

    public function getTotalHarga() {
        // Total = harga tiket dasar (warisan dari Penayangan) + biaya premium
        return $this->hargaTiket + $this->biayaPremium;
    }
}
