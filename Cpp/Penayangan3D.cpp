// =====================================================================
// FILE   : Penayangan3D.cpp
// PERAN  : Class turunan dari Penayangan (jenis tayangan #2)
// ISI    : Tayangan 3D. Menambah SATU atribut baru: biayaKacamata.
//          Total harga = harga tiket dasar + biaya kacamata.
// ALUR   : Di Main.cpp, objek jenis ini disimpan di vector<Penayangan3D>.
// =====================================================================

// Include guard: mencegah class terdefinisi ganda.
#ifndef PENAYANGAN_3D_CPP
#define PENAYANGAN_3D_CPP

// Membawa Penayangan, InformasiFilm, dan InformasiJadwal sekaligus.
#include "Penayangan.cpp"

// Objek nyata: tayangan bioskop 3D (penonton wajib pakai kacamata khusus)
class Penayangan3D : public Penayangan {
    private:
        // Atribut khusus 3D: biaya sewa/beli kacamata (dalam Rupiah).
        // 'private' karena tidak ada class lain yang mewarisi Penayangan3D.
        int biayaKacamata;

    public:
        // Constructor default: panggil constructor default parent,
        // lalu beri nilai awal 0 untuk atribut milik class ini.
        Penayangan3D() : Penayangan() {
            biayaKacamata = 0;
        }

        // Constructor berparameter (8 nilai):
        //   7 nilai pertama -> diteruskan ke constructor Penayangan
        //                      (lewat initializer list setelah ':')
        //   nilai ke-8      -> biayaKacamataP, disimpan di class ini
        // Urutan eksekusi: Penayangan dibangun dulu, baru badan
        // constructor ini mengisi biayaKacamata.
        Penayangan3D(string judulFilmP, string genreP, int durasiMenitP,
                    string tanggalP, string jamP, string namaStudioP,
                    int hargaTiketP, int biayaKacamataP)
            : Penayangan(judulFilmP, genreP, durasiMenitP, tanggalP, jamP, namaStudioP, hargaTiketP) {
            biayaKacamata = biayaKacamataP;
        }

        // Setter & getter untuk atribut khusus 3D
        void setBiayaKacamata(int biayaKacamataP) { biayaKacamata = biayaKacamataP; }
        int getBiayaKacamata() { return biayaKacamata; }

        // Redefinisi method biasa (bukan override virtual) khusus untuk objek bertipe Penayangan3D
        // Menyembunyikan versi milik Penayangan; dipakai saat dipanggil
        // pada objek bertipe Penayangan3D (lihat template tambahBaris di Main.cpp).

        // Jenis untuk kolom "Jenis" di tabel
        string getJenis() {
            return "3D";
        }

        // Contoh hasil: "Kacamata 3D (+Rp15000)"
        // to_string() mengubah angka int menjadi teks agar bisa digabung.
        string getInfoTambahan() {
            return "Kacamata 3D (+Rp" + to_string(biayaKacamata) + ")";
        }

        // Total = harga tiket dasar (warisan dari Penayangan) + biaya kacamata
        int getTotalHarga() {
            return hargaTiket + biayaKacamata;
        }
};

#endif  // akhir dari include guard PENAYANGAN_3D_CPP