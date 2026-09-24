// =====================================================================
// FILE   : InformasiJadwal.cpp
// PERAN  : BASE CLASS 2 (orang tua kedua dari class Penayangan)
// ISI    : Menyimpan data yang berhubungan dengan JADWAL & LOKASI:
//          tanggal, jam tayang, dan nama studio.
// ALUR   : File ini di-include oleh Penayangan.cpp
//          -> Penayangan mewarisi class ini BERSAMA InformasiFilm
//             (inilah yang disebut multiple inheritance)
// =====================================================================

// Include guard: mencegah class terdefinisi dua kali jika file ini
// ter-include lewat beberapa jalur sekaligus.
#ifndef INFORMASI_JADWAL_CPP
#define INFORMASI_JADWAL_CPP

#include <string>
using namespace std;

// ================== BASE CLASS 2 ==================
// Merepresentasikan jadwal & lokasi penayangan di bioskop
class InformasiJadwal {
    // 'protected' = tersembunyi dari luar, tetapi bisa dipakai langsung
    // oleh class turunan (Penayangan dan anak-anaknya).
    protected:
        string tanggal;      // format YYYY-MM-DD, contoh: "2026-12-01"
        string jam;          // format HH:MM, contoh: "19:00"
        string namaStudio;   // contoh: "Studio 1"

    public:
        // ---- Constructor default ----
        // Mengisi semua atribut dengan string kosong.
        InformasiJadwal() {
            tanggal = "";
            jam = "";
            namaStudio = "";
        }

        // ---- Constructor berparameter ----
        // Dipanggil oleh constructor Penayangan untuk mengisi data jadwal.
        InformasiJadwal(string tanggalP, string jamP, string namaStudioP) {
            tanggal = tanggalP;
            jam = jamP;
            namaStudio = namaStudioP;
        }

        // ---- Setter & getter ----
        // Setter mengubah nilai, getter membaca nilai atribut.
        void setTanggal(string tanggalP) { tanggal = tanggalP; }
        string getTanggal() { return tanggal; }

        void setJam(string jamP) { jam = jamP; }
        string getJam() { return jam; }

        void setNamaStudio(string namaStudioP) { namaStudio = namaStudioP; }
        string getNamaStudio() { return namaStudio; }
};

#endif  // akhir dari include guard INFORMASI_JADWAL_CPP