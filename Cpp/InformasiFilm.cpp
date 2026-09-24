// =====================================================================
// FILE   : InformasiFilm.cpp
// PERAN  : BASE CLASS 1 (salah satu "orang tua" dari class Penayangan)
// ISI    : Menyimpan data yang berhubungan dengan FILM saja:
//          judul, genre, dan durasi.
// ALUR   : File ini di-include oleh Penayangan.cpp
//          -> Penayangan mewarisi class ini (multiple inheritance)
// =====================================================================

// ---- Include guard ----
// Mencegah file ini terbaca dua kali oleh compiler. Kalau tidak ada guard,
// dan file ini ter-include lewat beberapa jalur (mis. Main -> Penayangan3D
// -> Penayangan -> InformasiFilm, lalu Main -> PenayanganIMAX -> ...),
// class akan terdefinisi ganda dan terjadi error "redefinition".
#ifndef INFORMASI_FILM_CPP
#define INFORMASI_FILM_CPP

#include <string>   // dibutuhkan untuk tipe data string
using namespace std;

// ================== BASE CLASS 1 ==================
// Merepresentasikan data film yang sedang tayang di bioskop
class InformasiFilm {
    // 'protected' = atribut TIDAK bisa diakses dari luar class,
    // tetapi BISA diakses langsung oleh class turunan (Penayangan dst.).
    // Karena itu Penayangan::tampilkan() bisa memakai judulFilm, genre,
    // dan durasiMenit tanpa lewat getter.
    protected:
        string judulFilm;   // judul film, contoh: "Zootopia 2"
        string genre;       // genre film, contoh: "Animasi"
        int durasiMenit;    // lama film dalam menit, contoh: 108

    public:
        // ---- Constructor default ----
        // Dipakai saat objek dibuat tanpa argumen; semua atribut diberi
        // nilai awal kosong/0 supaya tidak berisi nilai sampah.
        InformasiFilm() {
            judulFilm = "";
            genre = "";
            durasiMenit = 0;
        }

        // ---- Constructor berparameter ----
        // Dipanggil oleh constructor Penayangan (lewat initializer list)
        // untuk mengisi data film sekaligus saat objek dibuat.
        // Akhiran 'P' pada nama parameter = "Parameter", agar tidak
        // bentrok dengan nama atribut.
        InformasiFilm(string judulFilmP, string genreP, int durasiMenitP) {
            judulFilm = judulFilmP;
            genre = genreP;
            durasiMenit = durasiMenitP;
        }

        // ---- Setter & getter ----
        // Setter : mengubah nilai atribut dari luar class.
        // Getter : membaca nilai atribut dari luar class.
        // Di Main.cpp, getter inilah yang dipakai untuk mengisi baris tabel.
        void setJudulFilm(string judulFilmP) { judulFilm = judulFilmP; }
        string getJudulFilm() { return judulFilm; }

        void setGenre(string genreP) { genre = genreP; }
        string getGenre() { return genre; }

        void setDurasiMenit(int durasiMenitP) { durasiMenit = durasiMenitP; }
        int getDurasiMenit() { return durasiMenit; }
};

#endif  // akhir dari include guard INFORMASI_FILM_CPP