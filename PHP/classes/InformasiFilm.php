<?php
// =====================================================================
// FILE   : classes/InformasiFilm.php
// PERAN  : BASE CLASS 1 (parent dari class Penayangan)
// ISI    : Menyimpan data yang berhubungan dengan FILM saja:
//          judul, genre, durasi, DAN (baru) foto/poster film.
// ALUR   : Class ini di-extends oleh Penayangan.php.
//
// ATRIBUT BARU: fotoFilm
//   Menyimpan NAMA FILE poster film yang sudah diupload user (bukan
//   data gambar itu sendiri). File asli disimpan fisik di folder
//   uploads/, sedangkan yang disimpan di objek/session hanyalah nama
//   filenya, contoh: "foto_65f2a1.jpg". Saat ditampilkan, tag <img>
//   di index.php akan membaca file itu dari folder uploads/.
//   Ditempatkan di InformasiFilm (bukan di Penayangan) karena secara
//   konsep, poster adalah atribut milik FILM-nya, bukan milik jadwal
//   tayangnya (satu film yang sama tayang di banyak jadwal tetap
//   pakai poster yang sama).
// =====================================================================

class InformasiFilm {

    // 'protected' = tidak bisa diakses dari luar class, tetapi BISA
    // diakses langsung oleh class turunan (Penayangan dst.), sama
    // seperti versi C++ dan Java.
    protected $judulFilm;
    protected $genre;
    protected $durasiMenit;
    protected $fotoFilm;   // nama file poster, contoh: "foto_65f2a1.jpg" atau "" jika tidak ada

    // ---- Constructor ----
    // PHP hanya punya SATU constructor per class (__construct), sama
    // seperti Python. Nilai default (= "", = 0) meniru constructor
    // default C++/Java sekaligus constructor berparameter dalam satu method.
    public function __construct($judulFilm = "", $genre = "", $durasiMenit = 0, $fotoFilm = "") {
        $this->judulFilm = $judulFilm;
        $this->genre = $genre;
        $this->durasiMenit = $durasiMenit;
        $this->fotoFilm = $fotoFilm;
    }

    // ---- Setter & getter ----
    public function setJudulFilm($judulFilm) { $this->judulFilm = $judulFilm; }
    public function getJudulFilm() { return $this->judulFilm; }

    public function setGenre($genre) { $this->genre = $genre; }
    public function getGenre() { return $this->genre; }

    public function setDurasiMenit($durasiMenit) { $this->durasiMenit = $durasiMenit; }
    public function getDurasiMenit() { return $this->durasiMenit; }

    // Setter/getter khusus foto. getFotoFilm() dipakai di index.php untuk
    // menentukan path gambar yang ditampilkan (lihat komentar di index.php).
    public function setFotoFilm($fotoFilm) { $this->fotoFilm = $fotoFilm; }
    public function getFotoFilm() { return $this->fotoFilm; }

    // Method bantu: true jika film ini punya foto yang tersimpan.
    // Dipakai index.php untuk memilih antara <img> asli atau placeholder.
    public function adaFoto() {
        return $this->fotoFilm !== "" && $this->fotoFilm !== null;
    }
}
