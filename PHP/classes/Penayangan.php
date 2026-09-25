<?php
// =====================================================================
// FILE   : classes/Penayangan.php
// PERAN  : Class turunan dari InformasiFilm, sekaligus memakai trait
//          InformasiJadwal, dan menjadi induk untuk 3 jenis tayangan.
//
// HIERARKI KELAS PROGRAM INI:
//
//   InformasiFilm  <--- extends ---+
//   (judul, genre,                 |
//    durasi, fotoFilm)        Penayangan  (+ hargaTiket)
//                             /    |     \
//   InformasiJadwal ---(use, trait)     \
//   (tanggal, jam,            /          \
//    studio)      PenayanganReguler  Penayangan3D  PenayanganIMAX
//
// Padanan dengan versi C++ (multiple inheritance) & Java (composition):
//   - InformasiFilm   : diwarisi langsung lewat 'extends' (sama seperti Java)
//   - InformasiJadwal : "disuntikkan" lewat trait ('use'), fitur khas PHP
//     yang tidak ada di Java/C++, secara efek mirip mewarisi dua sumber
//     sekaligus tanpa perlu composition manual seperti versi Java.
// =====================================================================

class Penayangan extends InformasiFilm {

    // Trait dipakai di sini. Setelah baris ini, seluruh method & properti
    // di InformasiJadwal (tanggal, jam, namaStudio, getTanggal, dst.)
    // menjadi bagian dari class Penayangan, seolah ditulis langsung di sini.
    use InformasiJadwal;

    // Harga tiket dasar (belum termasuk biaya tambahan kacamata/premium).
    // 'protected' supaya class anak (3D, IMAX) bisa memakainya untuk
    // menghitung total harga.
    protected $hargaTiket;

    // ---- Constructor ----
    // Urutan parameter: 4 milik InformasiFilm (termasuk fotoFilm yang baru),
    // lalu 3 milik jadwal, lalu hargaTiket milik Penayangan sendiri.
    public function __construct($judulFilm = "", $genre = "", $durasiMenit = 0, $fotoFilm = "",
                                $tanggal = "", $jam = "", $namaStudio = "", $hargaTiket = 0) {
        // parent::__construct(...) memanggil constructor InformasiFilm
        // -- padanan langsung dari "InformasiFilm(...)" di initializer
        // list C++, atau "super(...)" di Java/Python.
        parent::__construct($judulFilm, $genre, $durasiMenit, $fotoFilm);

        // initJadwal(...) adalah method biasa dari trait InformasiJadwal
        // (lihat InformasiJadwal.php) yang dipanggil manual di sini,
        // karena trait tidak punya constructor tersendiri yang otomatis
        // terpanggil seperti "InformasiJadwal(...)" di C++.
        $this->initJadwal($tanggal, $jam, $namaStudio);

        $this->hargaTiket = $hargaTiket;
    }

    // Setter & getter untuk atribut milik class ini sendiri
    public function setHargaTiket($hargaTiket) { $this->hargaTiket = $hargaTiket; }
    public function getHargaTiket() { return $this->hargaTiket; }

    // ===== Method dasar: nilai default untuk Penayangan itu sendiri =====
    // Tiap class anak (Reguler/3D/IMAX) akan mendefinisikan ulang ketiga
    // method di bawah ini dengan isi masing-masing.
    //
    // CATATAN PHP vs C++: sama seperti Java dan Python, PHP TIDAK
    // mengenal konsep 'virtual' -- method di child class yang punya nama
    // & parameter sama OTOMATIS menimpa (override) method milik parent,
    // dan pemanggilannya selalu berdasarkan tipe objek sebenarnya saat
    // program berjalan. Meski begitu, index.php TETAP TIDAK memanfaatkan
    // hal itu secara sengaja: data tiap jenis tetap disimpan di array/
    // session terpisah (daftarReguler, daftar3D, daftarIMAX), persis
    // seperti desain aslinya.

    public function getJenis() {
        return "Reguler";
    }

    public function getInfoTambahan() {
        return "-";
    }

    public function getTotalHarga() {
        return $this->hargaTiket;
    }

    // Mencetak seluruh detail satu penayangan (versi teks/HTML sederhana).
    // Tidak dipakai langsung di index.php (yang memakai tabel), tapi
    // tersedia bila dibutuhkan, mis. untuk halaman detail.
    public function tampilkan() {
        echo "Jenis         : " . $this->getJenis() . "<br>";
        echo "Judul Film    : " . htmlspecialchars($this->judulFilm) . "<br>";   // dari InformasiFilm
        echo "Genre         : " . htmlspecialchars($this->genre) . "<br>";      // dari InformasiFilm
        echo "Durasi        : " . $this->durasiMenit . " menit<br>";           // dari InformasiFilm
        echo "Tanggal       : " . htmlspecialchars($this->tanggal) . "<br>";    // dari trait InformasiJadwal
        echo "Jam           : " . htmlspecialchars($this->jam) . "<br>";        // dari trait InformasiJadwal
        echo "Studio        : " . htmlspecialchars($this->namaStudio) . "<br>"; // dari trait InformasiJadwal
        echo "Harga Tiket   : " . $this->hargaTiket . "<br>";                  // milik Penayangan
        echo "Info Tambahan : " . htmlspecialchars($this->getInfoTambahan()) . "<br>";
        echo "Total Bayar   : " . $this->getTotalHarga() . "<br>";
    }

    // Catatan: tidak perlu destructor manual seperti ~Penayangan() di C++.
    // PHP membersihkan memori objek otomatis (garbage collector),
    // seperti Java dan Python.
}
