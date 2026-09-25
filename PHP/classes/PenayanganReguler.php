<?php
// =====================================================================
// FILE   : classes/PenayanganReguler.php
// PERAN  : Class turunan dari Penayangan (jenis tayangan #1)
// ISI    : Tayangan kelas reguler: tidak ada biaya tambahan, sehingga
//          class ini TIDAK punya atribut baru; hanya mendefinisikan
//          ulang tiga method identitas (jenis, info, total harga).
// ALUR   : Di index.php, objek jenis ini disimpan di
//          $_SESSION['daftarReguler'].
// =====================================================================

class PenayanganReguler extends Penayangan {

    // Tidak perlu __construct sendiri: karena tidak ada atribut baru,
    // constructor milik Penayangan (parent) otomatis dipakai apa adanya
    // -- padanan constructor kosong "{}" yang cuma memanggil parent
    // di versi C++/Java.

    // Redefinisi (override) method khusus untuk objek bertipe PenayanganReguler.
    // PHP tidak punya penanda wajib seperti '@Override' di Java; method
    // dengan nama sama otomatis menimpa milik parent.

    public function getJenis() {
        return "Reguler";
    }

    public function getInfoTambahan() {
        return "Tanpa fasilitas tambahan";
    }

    public function getTotalHarga() {
        // Reguler tidak ada biaya tambahan -> total = harga tiket dasar
        return $this->hargaTiket;
    }
}
