// =====================================================================
// FILE   : Penayangan.cpp
// PERAN  : DERIVED CLASS (turunan) sekaligus BASE CLASS untuk 3 jenis
//          tayangan (Reguler, 3D, IMAX).
//
// ISI    : Gabungan data film + jadwal, ditambah harga tiket dasar,
//          dan method dasar untuk jenis, info tambahan, total harga.
// =====================================================================

// Include guard: mencegah class ini terdefinisi ganda, karena file ini
// di-include oleh tiga file anak (Reguler, 3D, IMAX) yang semuanya
// di-include lagi oleh Main.cpp.
#ifndef PENAYANGAN_CPP
#define PENAYANGAN_CPP

#include <string>
#include <iostream>   // dibutuhkan untuk cout di method tampilkan()
#include "InformasiFilm.cpp"     // base class 1
#include "InformasiJadwal.cpp"   // base class 2
using namespace std;

// ================== MULTIPLE INHERITANCE ==================
// Penayangan mewarisi DUA class sekaligus: InformasiFilm dan InformasiJadwal
// 'public' inheritance: anggota public kedua parent tetap public di sini,
// sehingga getJudulFilm(), getTanggal(), dst. bisa dipanggil dari luar
// pada objek Penayangan maupun turunannya.
class Penayangan : public InformasiFilm, public InformasiJadwal {
    protected:
        // Harga tiket dasar (belum termasuk biaya tambahan kacamata/premium).
        // 'protected' supaya class anak (3D, IMAX) bisa memakainya untuk
        // menghitung total harga.
        int hargaTiket;

    public:
        // ---- Constructor default ----
        // Memanggil constructor default KEDUA parent lewat initializer list
        // (setelah tanda ':'), lalu mengisi hargaTiket dengan 0.
        Penayangan() : InformasiFilm(), InformasiJadwal() {
            hargaTiket = 0;
        }

        // ---- Constructor berparameter ----
        // ALUR: 7 nilai diterima dari class anak -> dibagi ke dua parent:
        //   3 nilai pertama  -> InformasiFilm   (judul, genre, durasi)
        //   3 nilai berikut  -> InformasiJadwal (tanggal, jam, studio)
        //   1 nilai terakhir -> disimpan sendiri di hargaTiket
        // Urutan eksekusi: constructor parent dijalankan lebih dulu
        // (sesuai urutan pewarisan: InformasiFilm lalu InformasiJadwal),
        // baru badan constructor ini dijalankan.
        Penayangan(string judulFilmP, string genreP, int durasiMenitP,
                    string tanggalP, string jamP, string namaStudioP,
                    int hargaTiketP): 
                InformasiFilm(judulFilmP, genreP, durasiMenitP),
                InformasiJadwal(tanggalP, jamP, namaStudioP) {
            hargaTiket = hargaTiketP;
        }

        // Setter & getter untuk atribut milik class ini sendiri
        void setHargaTiket(int hargaTiketP) { hargaTiket = hargaTiketP; }
        int getHargaTiket() { return hargaTiket; }

        // ===== Method biasa (BUKAN virtual, tidak ada polymorphism) =====
        // Nilai default untuk kelas Penayangan itu sendiri; tiap turunan
        // mendefinisikan ulang (redefinisi biasa) method dengan nama sama.
        //
        // PENTING: karena tidak memakai kata kunci 'virtual', C++ memilih
        // method yang dipanggil berdasarkan TIPE VARIABEL saat kompilasi
        // (static binding), bukan tipe objek sebenarnya. Contoh:
        //   Penayangan3D p;  p.getJenis();  -> versi milik Penayangan3D
        //   Penayangan*  q = &p; q->getJenis(); -> versi milik Penayangan
        // Itulah alasan Main.cpp memakai 3 vector terpisah, bukan satu
        // vector<Penayangan*>.

        // Mengembalikan nama jenis tayangan (untuk kolom "Jenis" di tabel)
        string getJenis() {
            return "Reguler";
        }

        // Mengembalikan keterangan fasilitas tambahan (kolom "Info Tambahan")
        string getInfoTambahan() {
            return "-";
        }

        // Mengembalikan total yang harus dibayar (kolom "Total Bayar").
        // Versi dasar: total = harga tiket saja.
        int getTotalHarga() {
            return hargaTiket;
        }

        // Mencetak seluruh detail satu penayangan dalam bentuk daftar
        // (bukan tabel). Method ini tidak dipanggil di Main.cpp saat ini.
        // Catatan: karena static binding, pemanggilan getJenis(),
        // getInfoTambahan(), dan getTotalHarga() DI DALAM method ini
        // selalu memakai versi milik Penayangan, walaupun tampilkan()
        // dipanggil dari objek Penayangan3D/PenayanganIMAX.
        void tampilkan() {
            cout << "Jenis         : " << getJenis() << endl;
            cout << "Judul Film    : " << judulFilm << endl;      // dari InformasiFilm
            cout << "Genre         : " << genre << endl;          // dari InformasiFilm
            cout << "Durasi        : " << durasiMenit << " menit" << endl;  // dari InformasiFilm
            cout << "Tanggal       : " << tanggal << endl;        // dari InformasiJadwal
            cout << "Jam           : " << jam << endl;            // dari InformasiJadwal
            cout << "Studio        : " << namaStudio << endl;     // dari InformasiJadwal
            cout << "Harga Tiket   : " << hargaTiket << endl;     // milik Penayangan
            cout << "Info Tambahan : " << getInfoTambahan() << endl;
            cout << "Total Bayar   : " << getTotalHarga() << endl;
        }

        // Destructor kosong: tidak ada memori dinamis yang perlu dibebaskan
        // (semua atribut berupa string/int yang dikelola otomatis).
        ~Penayangan() {}
};

#endif  // akhir dari include guard PENAYANGAN_CPP