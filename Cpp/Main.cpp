// =====================================================================
// FILE   : Main.cpp
// PERAN  : Program utama (titik masuk / entry point) aplikasi
//          "Pengelolaan Penayangan Bioskop".
//
// GAMBARAN ALUR PROGRAM:
//   1. main() membuat 3 vector kosong (Reguler, 3D, IMAX).
//   2. main() mengisi 5 data awal (hardcode) ke vector yang sesuai.
//   3. main() masuk ke loop menu (do-while) sampai user memilih 0:
//        - Pilihan 1 -> tambahPenayangan()          (CREATE)
//        - Pilihan 2 -> tampilkanTabelPenayangan()  (READ)
//        - Pilihan 0 -> keluar dari loop, program selesai
//
// KONSEP UTAMA YANG DIPAKAI:
//   - Multiple inheritance  : Penayangan <- InformasiFilm + InformasiJadwal
//   - Single inheritance    : Reguler / 3D / IMAX <- Penayangan
//   - TANPA polymorphism    : tiap jenis punya vector sendiri, method
//                             tidak virtual (static binding)
// =====================================================================

// ---------- Library standar ----------
#include <iostream>   // cin, cout, endl
#include <string>     // string, to_string
#include <vector>     // vector (penyimpan data dinamis)
#include <limits>     // numeric_limits (dipakai bersihkanBuffer)
#include <iomanip>    // setw (mengatur lebar kolom tabel)
#include <algorithm>  // max (mencari lebar kolom terpanjang)

// ---------- File class buatan sendiri ----------
// Ketiga file ini sudah otomatis membawa Penayangan, InformasiFilm, dan
// InformasiJadwal (lewat rantai #include). Include guard di tiap file
// mencegah error karena terbaca lebih dari sekali.
#include "PenayanganReguler.cpp"
#include "Penayangan3D.cpp"
#include "PenayanganIMAX.cpp"

using namespace std;

// =====================================================================
// FUNGSI BANTU: bersihkanBuffer
// =====================================================================
// Membersihkan buffer input agar getline tidak "terlompat"
//
// MASALAH yang diselesaikan:
//   Setelah "cin >> angka", karakter Enter ('\n') masih tertinggal di
//   buffer. Jika langsung dilanjutkan "getline(cin, teks)", getline akan
//   membaca '\n' itu dan menganggapnya input kosong, sehingga pertanyaan
//   berikutnya seolah-olah terlewati.
//
// CARA KERJA:
//   cin.clear()  -> mereset status error cin (mis. jika user mengetik
//                   huruf saat diminta angka)
//   cin.ignore() -> membuang semua karakter di buffer sampai bertemu '\n'
void bersihkanBuffer() {
    cin.clear();
    cin.ignore(numeric_limits<streamsize>::max(), '\n');
}

// =====================================================================
// FUNGSI CREATE: tambahPenayangan
// =====================================================================
// CREATE: menambahkan object baru ke vector sesuai jenisnya masing-masing
// (TIDAK memakai polymorphism: setiap jenis punya vector objek sendiri,
//  bukan satu vector pointer base class)
//
// PARAMETER: tiga vector diterima secara REFERENCE (tanda '&') agar
//            data yang ditambahkan di sini benar-benar tersimpan di
//            vector milik main(), bukan di salinan lokal.
//
// ALUR FUNGSI:
//   1. Tanya jenis tayangan (1/2/3) -> jika di luar rentang, batalkan.
//   2. Tanya data UMUM yang sama untuk semua jenis
//      (judul, genre, durasi, tanggal, jam, studio, harga tiket).
//   3. Tanya data KHUSUS sesuai jenis (kacamata untuk 3D; ukuran layar
//      & biaya premium untuk IMAX), lalu buat objek dan push_back ke
//      vector yang sesuai.
void tambahPenayangan(vector<PenayanganReguler>& daftarReguler,
                        vector<Penayangan3D>& daftar3D,
                        vector<PenayanganIMAX>& daftarIMAX) {
    int jenis;   // menyimpan pilihan jenis tayangan (1=Reguler, 2=3D, 3=IMAX)

    // --- Langkah 1: pilih jenis tayangan ---
    cout << "\n--- Tambah Penayangan Baru ---" << endl;
    cout << "Pilih jenis tayangan:" << endl;
    cout << "1. Reguler" << endl;
    cout << "2. 3D" << endl;
    cout << "3. IMAX" << endl;
    cout << "Pilihan jenis            : ";
    cin >> jenis;
    bersihkanBuffer();   // buang '\n' sisa cin >> agar getline di bawah aman

    // Validasi: hanya 1, 2, atau 3 yang diterima. 'return' langsung
    // keluar dari fungsi sehingga tidak ada data yang ditambahkan.
    if (jenis < 1 || jenis > 3) {
        cout << ">> Jenis tidak valid, data batal ditambahkan!" << endl;
        return;
    }

    // --- Langkah 2: input data umum (berlaku untuk semua jenis) ---
    string judulFilm, genre, tanggal, jam, studio;
    int durasi, hargaTiket;

    // getline dipakai untuk teks karena bisa memuat spasi
    // (mis. "Avengers: Doomsday"). cin >> biasa berhenti di spasi.
    cout << "\nJudul Film               : ";
    getline(cin, judulFilm);
    cout << "\nGenre                    : ";
    getline(cin, genre);

    // cin >> dipakai untuk angka; setelahnya WAJIB bersihkanBuffer()
    // sebelum getline berikutnya (lihat penjelasan di atas).
    cout << "\nDurasi (menit)           : ";
    cin >> durasi;
    bersihkanBuffer();

    cout << "\nTanggal (YYYY-MM-DD)     : ";
    getline(cin, tanggal);
    cout << "\nJam (HH:MM)              : ";
    getline(cin, jam);
    cout << "\nStudio                   : ";
    getline(cin, studio);
    cout << "\nHarga Tiket Dasar        : ";
    cin >> hargaTiket;
    bersihkanBuffer();

    // --- Langkah 3: input data khusus + buat objek sesuai jenis ---
    if (jenis == 1) {
        // REGULER: tidak ada data tambahan.
        // Objek dibuat langsung di dalam push_back (objek sementara)
        // lalu disalin ke dalam vector daftarReguler.
        daftarReguler.push_back(PenayanganReguler(judulFilm, genre, durasi, tanggal, jam, studio, hargaTiket));
    } else if (jenis == 2) {
        // 3D: butuh satu data tambahan, yaitu biaya kacamata.
        int biayaKacamata;
        cout << "\nBiaya Kacamata 3D        : ";
        cin >> biayaKacamata;
        bersihkanBuffer();
        daftar3D.push_back(Penayangan3D(judulFilm, genre, durasi, tanggal, jam, studio, hargaTiket, biayaKacamata));
    } else {
        // IMAX (jenis == 3): butuh dua data tambahan.
        // ukuranLayar bertipe double karena boleh desimal (mis. 22.5).
        double ukuranLayar;
        int biayaPremium;
        cout << "\nUkuran Layar (meter)     : ";
        cin >> ukuranLayar;
        cout << "\nBiaya Premium IMAX       : ";
        cin >> biayaPremium;
        bersihkanBuffer();   // cukup sekali, setelah dua cin >> berturut-turut
        daftarIMAX.push_back(PenayanganIMAX(judulFilm, genre, durasi, tanggal, jam, studio, hargaTiket, ukuranLayar, biayaPremium));
    }

    cout << "\n>> Data berhasil ditambahkan!" << endl;
}

// =====================================================================
// FUNGSI BANTU (TEMPLATE): tambahBaris
// =====================================================================
// Helper: ubah satu object (tipe apapun, ditentukan saat compile / static binding)
// menjadi satu baris string untuk tabel. Dipanggil terpisah untuk tiap jenis
// sehingga TIDAK butuh pointer base class ataupun virtual function.
//
// PENJELASAN TEMPLATE:
//   'template <typename T>' membuat satu fungsi yang bisa dipakai untuk
//   banyak tipe. Compiler otomatis membuat 3 versi fungsi ini:
//     T = PenayanganReguler, T = Penayangan3D, T = PenayanganIMAX.
//   Di tiap versi, obj.getJenis() / getInfoTambahan() / getTotalHarga()
//   otomatis memanggil method milik class yang sesuai (static binding),
//   jadi tidak perlu 'virtual'.
//
// PARAMETER:
//   baris : tabel penampung seluruh baris (reference, ditambah di sini)
//   obj   : objek penayangan yang akan dijadikan satu baris (reference)
//   nomor : nomor urut baris (reference, agar naik terus di antara ketiga
//           pemanggilan dari 3 vector berbeda)
template <typename T>
void tambahBaris(vector<vector<string>>& baris, T& obj, int& nomor) {
    vector<string> row;   // satu baris tabel = kumpulan sel berupa teks

    // 'nomor++' (post-increment): nilai yang dipakai adalah nilai SEBELUM
    // ditambah, lalu nomor naik 1 untuk baris berikutnya.
    // Karena nomor awal di pemanggil = 0, baris pertama bernomor 0.
    row.push_back(to_string(nomor++));

    // Urutan push_back di bawah HARUS sama dengan urutan judul kolom
    // pada vector 'header' di tampilkanTabelPenayangan().
    row.push_back(obj.getJenis());                                   // kolom Jenis
    row.push_back(obj.getJudulFilm());                               // kolom Judul Film  (dari InformasiFilm)
    row.push_back(obj.getGenre());                                   // kolom Genre       (dari InformasiFilm)
    row.push_back(to_string(obj.getDurasiMenit()) + " mnt");         // kolom Durasi      (dari InformasiFilm)
    row.push_back(obj.getTanggal());                                 // kolom Tanggal     (dari InformasiJadwal)
    row.push_back(obj.getJam());                                     // kolom Jam         (dari InformasiJadwal)
    row.push_back(obj.getNamaStudio());                              // kolom Studio      (dari InformasiJadwal)
    row.push_back(to_string(obj.getHargaTiket()));                   // kolom Harga Tiket (dari Penayangan)
    row.push_back(obj.getInfoTambahan());                            // kolom Info Tambahan (beda tiap jenis)
    row.push_back(to_string(obj.getTotalHarga()));                   // kolom Total Bayar   (beda tiap jenis)

    baris.push_back(row);   // masukkan baris jadi ke tabel penampung
}

// =====================================================================
// FUNGSI READ: tampilkanTabelPenayangan
// =====================================================================
// READ: menampilkan seluruh data (dari 3 vector berbeda) dalam SATU TABEL
// yang lebar kolomnya menyesuaikan isi data (dinamis)
//
// ALUR FUNGSI:
//   1. Jika ketiga vector kosong -> tampilkan pesan lalu keluar.
//   2. Siapkan judul kolom (header).
//   3. Kumpulkan semua data menjadi "tabel teks" (vector<vector<string>>)
//      dengan memanggil tambahBaris() untuk tiap objek di tiap vector.
//   4. Hitung lebar tiap kolom = teks terpanjang di kolom itu.
//   5. Cetak: garis -> header -> garis -> semua baris -> garis.
void tampilkanTabelPenayangan(vector<PenayanganReguler>& daftarReguler,
                                vector<Penayangan3D>& daftar3D,
                                vector<PenayanganIMAX>& daftarIMAX) {
    cout << "\n--- Daftar Seluruh Penayangan ---" << endl;

    // --- Langkah 1: cek apakah ada data ---
    if (daftarReguler.empty() && daftar3D.empty() && daftarIMAX.empty()) {
        cout << "(Belum ada data penayangan)" << endl;
        return;   // tidak ada yang perlu dicetak
    }

    // --- Langkah 2: judul kolom ---
    vector<string> header = {"No", "Jenis", "Judul Film", "Genre", "Durasi",
                                "Tanggal", "Jam", "Studio", "Harga Tiket",
                                "Info Tambahan", "Total Bayar"};
    int kolom = header.size();   // jumlah kolom (11)

    // --- Langkah 3: kumpulkan baris dari ketiga vector ---
    // Kumpulkan baris dari ketiga vector jenis tayangan (dipanggil terpisah per jenis)
    // Urutan tampil dikelompokkan per jenis: semua Reguler dulu, lalu 3D,
    // lalu IMAX (BUKAN berdasarkan urutan data dimasukkan).
    // 'auto&' = alias ke elemen asli vector (tanpa menyalin objek).
    vector<vector<string>> baris;
    int nomor = 0;   // nomor urut, akan dinaikkan di dalam tambahBaris
    for (auto& obj : daftarReguler) tambahBaris(baris, obj, nomor);
    for (auto& obj : daftar3D)      tambahBaris(baris, obj, nomor);
    for (auto& obj : daftarIMAX)    tambahBaris(baris, obj, nomor);

    // --- Langkah 4: hitung lebar tiap kolom secara dinamis ---
    // Hitung lebar tiap kolom secara dinamis = panjang teks terpanjang di kolom itu
    // Untuk tiap kolom c: mulai dari panjang judul kolom, lalu bandingkan
    // dengan panjang teks di setiap baris; ambil yang paling besar.
    vector<size_t> lebar(kolom);
    for (int c = 0; c < kolom; c++) {
        lebar[c] = header[c].size();
        for (size_t r = 0; r < baris.size(); r++) {
            lebar[c] = max(lebar[c], baris[r][c].size());
        }
        lebar[c] += 2; // padding (ruang kosong 1 spasi kiri dan 1 spasi kanan)
    }

    // --- Langkah 5: siapkan dua "fungsi kecil" (lambda) untuk mencetak ---
    // Lambda [&] = boleh memakai variabel lokal di atas (lebar, kolom)
    // tanpa harus dikirim sebagai parameter.

    // Mencetak garis pemisah, contoh: +----+-------+------+
    auto cetakGaris = [&]() {
        cout << "+";
        for (int c = 0; c < kolom; c++) cout << string(lebar[c], '-') << "+";
        cout << endl;
    };

    // Mencetak satu baris data, contoh: | 0 | Reguler | ... |
    // left + setw(...) = rata kiri dengan lebar tetap; (lebar - 1) karena
    // sudah ada 1 spasi di depan (" ") sebagai padding kiri.
    auto cetakBaris = [&](vector<string>& row) {
        cout << "|";
        for (int c = 0; c < kolom; c++) {
            cout << " " << left << setw((int)lebar[c] - 1) << row[c] << "|";
        }
        cout << endl;
    };

    // --- Langkah 6: cetak seluruh tabel ---
    cetakGaris();                       // garis atas
    cetakBaris(header);                 // baris judul kolom
    cetakGaris();                       // garis pemisah header dan isi
    for (auto& row : baris) cetakBaris(row);   // semua baris data
    cetakGaris();                       // garis bawah
}

// =====================================================================
// FUNGSI UTAMA: main
// =====================================================================
int main() {
    // ---- Tahap 1: siapkan penyimpanan data ----
    // Tiga vector terpisah sesuai jenis tayangan (bukan satu vector pointer base class)
    // Vector = array dinamis yang ukurannya bertambah otomatis saat push_back.
    vector<PenayanganReguler> daftarReguler;
    vector<Penayangan3D> daftar3D;
    vector<PenayanganIMAX> daftarIMAX;

    // ---- Tahap 2: isi data awal ----
    // 5 data awal (hardcode), tersebar ke ketiga vector sesuai jenisnya
    // Urutan argumen: judul, genre, durasi, tanggal, jam, studio, harga
    // (+ biaya kacamata untuk 3D; + ukuran layar & biaya premium untuk IMAX)
    daftarReguler.push_back(PenayanganReguler("Avengers: Doomsday", "Aksi", 180, "2026-12-01", "19:00", "Studio 1", 50000));
    daftar3D.push_back(Penayangan3D("Spider-Man: Brand New Day", "Aksi", 150, "2026-08-25", "20:00", "Studio 2", 60000, 15000));
    daftarIMAX.push_back(PenayanganIMAX("Dune: Part Three", "Sci-Fi", 165, "2026-10-10", "21:00", "Studio 3", 70000, 22.5, 35000));
    daftarReguler.push_back(PenayanganReguler("Zootopia 2", "Animasi", 108, "2026-11-15", "16:30", "Studio 4", 45000));
    daftar3D.push_back(Penayangan3D("Avatar: Fire and Ash", "Fantasi", 195, "2026-12-19", "18:00", "Studio 5", 65000, 20000));

    // ---- Tahap 3: loop menu utama ----
    // do-while dipilih karena menu harus tampil MINIMAL SEKALI sebelum
    // kondisi (pilihan != 0) dicek.
    int pilihan;
    do {
        // Tampilkan menu
        cout << "\n===== MENU PENGELOLAAN PENAYANGAN BIOSKOP =====" << endl;
        cout << "1. Tambah Penayangan (Create)" << endl;
        cout << "2. Tampilkan Semua Penayangan (Tabel)" << endl;
        cout << "0. Keluar" << endl;
        cout << "Pilihan Anda: ";
        cin >> pilihan;
        bersihkanBuffer();   // buang sisa '\n' agar input berikutnya tidak terganggu

        // Arahkan ke fungsi sesuai pilihan user
        switch (pilihan) {
            case 1:
                // CREATE: kirim ketiga vector (by reference) agar data baru tersimpan
                tambahPenayangan(daftarReguler, daftar3D, daftarIMAX);
                break;
            case 2:
                // READ: tampilkan gabungan isi ketiga vector dalam satu tabel
                tampilkanTabelPenayangan(daftarReguler, daftar3D, daftarIMAX);
                break;
            case 0:
                // Pesan penutup; loop akan berhenti karena pilihan == 0
                cout << "Terima kasih, program selesai." << endl;
                break;
            default:
                // Angka selain 0/1/2 -> menu ditampilkan lagi
                cout << ">> Pilihan tidak valid, coba lagi." << endl;
        }
    } while (pilihan != 0);   // ulangi menu selama user belum memilih 0

    return 0;   // 0 = program berakhir normal
}