// =====================================================================
// FILE   : PenayanganIMAX.cpp
// PERAN  : Class turunan dari Penayangan (jenis tayangan #3)
// ISI    : Tayangan IMAX. Menambah DUA atribut baru: ukuran layar dan
//          biaya premium. Total harga = harga tiket dasar + biaya premium.
// ALUR   : Di Main.cpp, objek jenis ini disimpan di vector<PenayanganIMAX>.
// =====================================================================

// Include guard: mencegah class terdefinisi ganda.
#ifndef PENAYANGAN_IMAX_CPP
#define PENAYANGAN_IMAX_CPP

// Membawa Penayangan, InformasiFilm, dan InformasiJadwal sekaligus.
#include "Penayangan.cpp"

// Objek nyata: tayangan bioskop IMAX (layar raksasa, harga tiket premium)
class PenayanganIMAX : public Penayangan {
    private:
        double ukuranLayarMeter;   // lebar layar dalam meter (boleh desimal, mis. 22.5)
        int biayaPremium;          // biaya tambahan khusus IMAX (dalam Rupiah)

    public:
        // Constructor default: panggil constructor default parent,
        // lalu beri nilai awal 0 untuk atribut milik class ini.
        PenayanganIMAX() : Penayangan() {
            ukuranLayarMeter = 0;
            biayaPremium = 0;
        }

        // Constructor berparameter (9 nilai):
        //   7 nilai pertama -> diteruskan ke constructor Penayangan
        //   ukuranLayarMeterP dan biayaPremiumP -> disimpan di class ini
        PenayanganIMAX(string judulFilmP, string genreP, int durasiMenitP,
                        string tanggalP, string jamP, string namaStudioP,
                        int hargaTiketP, double ukuranLayarMeterP, int biayaPremiumP)
            : Penayangan(judulFilmP, genreP, durasiMenitP, tanggalP, jamP, namaStudioP, hargaTiketP) {
            ukuranLayarMeter = ukuranLayarMeterP;
            biayaPremium = biayaPremiumP;
        }

        // Setter & getter untuk atribut khusus IMAX
        void setUkuranLayarMeter(double v) { ukuranLayarMeter = v; }
        double getUkuranLayarMeter() { return ukuranLayarMeter; }

        void setBiayaPremium(int v) { biayaPremium = v; }
        int getBiayaPremium() { return biayaPremium; }

        // Redefinisi method biasa (bukan override virtual) khusus untuk objek bertipe PenayanganIMAX
        // Menyembunyikan versi milik Penayangan; dipakai saat dipanggil
        // pada objek bertipe PenayanganIMAX (lihat template tambahBaris di Main.cpp).

        // Jenis untuk kolom "Jenis" di tabel
        string getJenis() {
            return "IMAX";
        }

        // Contoh hasil: "Layar 22m (+Rp35000)"
        // (int)ukuranLayarMeter membuang bagian desimal: 22.5 tampil sebagai 22,
        // karena to_string(double) akan menghasilkan "22.500000" yang terlalu panjang.
        string getInfoTambahan() {
            return "Layar " + to_string((int)ukuranLayarMeter) + "m (+Rp" + to_string(biayaPremium) + ")";
        }

        // Total = harga tiket dasar (warisan dari Penayangan) + biaya premium
        int getTotalHarga() {
            return hargaTiket + biayaPremium;
        }
};

#endif  // akhir dari include guard PENAYANGAN_IMAX_CPP