// =====================================================================
// FILE   : PenayanganReguler.cpp
// PERAN  : Class turunan dari Penayangan (jenis tayangan #1)
// ISI    : Tayangan kelas reguler: tidak ada biaya tambahan, sehingga
//          class ini TIDAK punya atribut baru; hanya mendefinisikan
//          ulang tiga method identitas (jenis, info, total harga).
// ALUR   : Di Main.cpp, objek jenis ini disimpan di vector<PenayanganReguler>.
// =====================================================================

// Include guard: mencegah class terdefinisi ganda.
#ifndef PENAYANGAN_REGULER_CPP
#define PENAYANGAN_REGULER_CPP

// Meng-include Penayangan.cpp otomatis membawa juga InformasiFilm.cpp
// dan InformasiJadwal.cpp (karena Penayangan.cpp meng-include keduanya).
#include "Penayangan.cpp"

// Objek nyata: tayangan bioskop kelas reguler (studio biasa, tanpa fasilitas khusus)
class PenayanganReguler : public Penayangan {
    public:
        // Constructor default: langsung meneruskan ke constructor default
        // Penayangan. Badan '{}' kosong karena tidak ada atribut baru.
        PenayanganReguler() : Penayangan() {}

        // Constructor berparameter: menerima 7 nilai dan meneruskan
        // SEMUANYA ke constructor Penayangan (yang lalu membagikannya ke
        // InformasiFilm dan InformasiJadwal). Tidak ada nilai tambahan.
        PenayanganReguler(string judulFilmP, string genreP, int durasiMenitP,
                            string tanggalP, string jamP, string namaStudioP,
                            int hargaTiketP)
            : Penayangan(judulFilmP, genreP, durasiMenitP, tanggalP, jamP, namaStudioP, hargaTiketP) {}

        // Redefinisi method biasa (bukan override virtual) khusus untuk objek bertipe PenayanganReguler
        // Method di bawah ini "menyembunyikan" (name hiding) method
        // bernama sama milik Penayangan. Saat dipanggil pada objek
        // PenayanganReguler, versi inilah yang dijalankan.

        // Jenis untuk kolom "Jenis" di tabel
        string getJenis() {
            return "Reguler";
        }

        // Keterangan untuk kolom "Info Tambahan" di tabel
        string getInfoTambahan() {
            return "Tanpa fasilitas tambahan";
        }

        // Reguler tidak ada biaya tambahan -> total = harga tiket dasar
        int getTotalHarga() {
            return hargaTiket;
        }
};

#endif  // akhir dari include guard PENAYANGAN_REGULER_CPP