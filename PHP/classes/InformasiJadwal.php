<?php
// =====================================================================
// FILE   : classes/InformasiJadwal.php
// PERAN  : Padanan BASE CLASS 2 di versi C++ -- tetapi di PHP dibuat
//          sebagai TRAIT, bukan class biasa.
// ISI    : Data jadwal & lokasi penayangan: tanggal, jam, nama studio.
//
// KENAPA TRAIT, BUKAN CLASS ATAU extends KEDUA?
//   Sama seperti Java, PHP TIDAK mengizinkan "class X extends A, B"
//   (satu class hanya boleh punya SATU parent). Tapi PHP punya fitur
//   KHUSUS bernama TRAIT yang tidak dimiliki Java: sekumpulan method
//   (dan boleh juga atribut) yang bisa "disalin-tempel" ke dalam class
//   manapun memakai kata kunci 'use'. Efeknya sangat mirip multiple
//   inheritance versi C++:
//
//       class Penayangan extends InformasiFilm {
//           use InformasiJadwal;   // <- trait "ditambahkan" ke sini
//       }
//
//   Bedanya dengan pewarisan biasa: trait TIDAK punya hubungan "is-a"
//   dan tidak muncul di rantai extends -- ia hanya menyalin method
//   & propertinya ke dalam class pemakainya, seolah-olah kode di
//   trait ini ditulis ulang langsung di dalam class Penayangan.
// =====================================================================

trait InformasiJadwal {

    // Properti trait ini akan menjadi properti milik Penayangan setelah
    // dipakai dengan 'use InformasiJadwal;' -- persis seperti properti
    // 'protected' yang diwariskan lewat extends di C++.
    protected $tanggal = "";
    protected $jam = "";
    protected $namaStudio = "";

    // ---- "Constructor" trait ----
    // PHP TIDAK mengizinkan trait punya constructor yang otomatis
    // dipanggil terpisah dari constructor class pemakainya (tidak ada
    // "InformasiJadwal(...)" yang bisa dipanggil sendiri seperti
    // constructor parent di C++). Solusinya: dibuat SATU method biasa
    // (bukan __construct) yang dipanggil secara eksplisit dari dalam
    // constructor Penayangan -- lihat Penayangan.php.
    protected function initJadwal($tanggal, $jam, $namaStudio) {
        $this->tanggal = $tanggal;
        $this->jam = $jam;
        $this->namaStudio = $namaStudio;
    }

    // ---- Setter & getter ----
    // Setelah trait ini dipakai (use), method-method ini otomatis
    // menjadi method milik Penayangan (dan seluruh turunannya), sama
    // seperti method dari InformasiJadwal di C++.
    public function setTanggal($tanggal) { $this->tanggal = $tanggal; }
    public function getTanggal() { return $this->tanggal; }

    public function setJam($jam) { $this->jam = $jam; }
    public function getJam() { return $this->jam; }

    public function setNamaStudio($namaStudio) { $this->namaStudio = $namaStudio; }
    public function getNamaStudio() { return $this->namaStudio; }
}
