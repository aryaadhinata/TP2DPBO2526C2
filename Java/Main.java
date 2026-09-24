// =====================================================================
// FILE   : Main.java
// PERAN  : Program utama (titik masuk / entry point) aplikasi
//          "Pengelolaan Penayangan Bioskop".
//
// GAMBARAN ALUR PROGRAM:
//   1. main() membuat 3 list kosong (Reguler, 3D, IMAX).
//   2. main() mengisi 5 data awal (hardcode) ke list yang sesuai.
//   3. main() masuk ke loop menu (do-while) sampai user memilih 0:
//        - Pilihan 1 -> tambahPenayangan()          (CREATE)
//        - Pilihan 2 -> tampilkanTabelPenayangan()  (READ)
//        - Pilihan 0 -> keluar dari loop, program selesai
//
// KONSEP UTAMA YANG DIPAKAI:
//   - Inheritance  : Penayangan extends InformasiFilm
//   - Composition  : Penayangan memiliki objek InformasiJadwal
//                    (pengganti multiple inheritance, karena Java tidak
//                     mengizinkan extends dua class)
//   - Class turunan: PenayanganReguler / Penayangan3D / PenayanganIMAX
//                    extends Penayangan
//   - Tiap jenis punya list sendiri (bukan satu List<Penayangan>)
//
// CARA KOMPILASI & MENJALANKAN (Java 11 atau lebih baru):
//   javac *.java
//   java Main
// =====================================================================

import java.util.ArrayList;   // ArrayList = array dinamis (padanan vector di C++)
import java.util.List;        // List = tipe antarmuka untuk ArrayList
import java.util.Scanner;     // Scanner = pembaca input dari keyboard (padanan cin)

public class Main {

    // ---------- Pembaca input ----------
    // Satu Scanner dipakai bersama oleh seluruh method (static) di class ini.
    // 'final' = variabel ini tidak akan diganti dengan objek lain.
    private static final Scanner input = new Scanner(System.in);

    // =================================================================
    // FUNGSI BANTU INPUT: bacaTeks, bacaInt, bacaDouble
    // =================================================================
    // MASALAH di C++ yang tidak muncul di sini:
    //   Di C++, "cin >> angka" meninggalkan '\n' di buffer sehingga perlu
    //   bersihkanBuffer() sebelum getline. Di Java hal itu dihindari dengan
    //   SELALU membaca satu baris penuh memakai nextLine(), lalu mengubahnya
    //   menjadi angka sendiri (Integer.parseInt / Double.parseDouble).
    //   Jadi tidak ada sisa '\n' dan fungsi bersihkanBuffer() tidak diperlukan.

    // Menampilkan pertanyaan, lalu membaca satu baris teks (boleh berspasi).
    static String bacaTeks(String pertanyaan) {
        System.out.print(pertanyaan);
        return input.nextLine();
    }

    // Menampilkan pertanyaan, lalu membaca bilangan bulat.
    // Jika user mengetik selain angka, program TIDAK crash: pesan
    // kesalahan ditampilkan dan pertanyaan diulang sampai input valid.
    static int bacaInt(String pertanyaan) {
        while (true) {
            System.out.print(pertanyaan);
            String teks = input.nextLine().trim();   // trim() membuang spasi di ujung
            try {
                return Integer.parseInt(teks);       // berhasil -> kembalikan angkanya
            } catch (NumberFormatException e) {      // gagal -> ulangi pertanyaan
                System.out.println(">> Input harus berupa bilangan bulat, coba lagi.");
            }
        }
    }

    // Sama seperti bacaInt, tetapi untuk bilangan desimal (mis. 22.5).
    // replace(',', '.') membuat "22,5" (gaya Indonesia) juga diterima.
    // Memakai Double.parseDouble (bukan Scanner.nextDouble) agar tidak
    // bergantung pada pengaturan bahasa/locale komputer.
    static double bacaDouble(String pertanyaan) {
        while (true) {
            System.out.print(pertanyaan);
            String teks = input.nextLine().trim().replace(',', '.');
            try {
                return Double.parseDouble(teks);
            } catch (NumberFormatException e) {
                System.out.println(">> Input harus berupa angka, coba lagi.");
            }
        }
    }

    // =================================================================
    // FUNGSI CREATE: tambahPenayangan
    // =================================================================
    // CREATE: menambahkan object baru ke list sesuai jenisnya masing-masing
    // (setiap jenis punya list objek sendiri, bukan satu List<Penayangan>)
    //
    // PARAMETER: tiga list dikirim ke sini. Di Java, objek (termasuk list)
    //            dikirim sebagai referensi, jadi perubahan (add) di dalam
    //            method ini langsung terlihat oleh main() -- padanan tanda
    //            '&' (reference) di C++.
    //
    // ALUR FUNGSI:
    //   1. Tanya jenis tayangan (1/2/3) -> jika di luar rentang, batalkan.
    //   2. Tanya data UMUM yang sama untuk semua jenis
    //      (judul, genre, durasi, tanggal, jam, studio, harga tiket).
    //   3. Tanya data KHUSUS sesuai jenis (kacamata untuk 3D; ukuran layar
    //      & biaya premium untuk IMAX), lalu buat objek dan add ke
    //      list yang sesuai.
    static void tambahPenayangan(List<PenayanganReguler> daftarReguler,
                                 List<Penayangan3D> daftar3D,
                                 List<PenayanganIMAX> daftarIMAX) {

        // --- Langkah 1: pilih jenis tayangan ---
        System.out.println("\n--- Tambah Penayangan Baru ---");
        System.out.println("Pilih jenis tayangan:");
        System.out.println("1. Reguler");
        System.out.println("2. 3D");
        System.out.println("3. IMAX");
        int jenis = bacaInt("Pilihan jenis            : ");

        // Validasi: hanya 1, 2, atau 3 yang diterima. 'return' langsung
        // keluar dari method sehingga tidak ada data yang ditambahkan.
        if (jenis < 1 || jenis > 3) {
            System.out.println(">> Jenis tidak valid, data batal ditambahkan!");
            return;
        }

        // --- Langkah 2: input data umum (berlaku untuk semua jenis) ---
        String judulFilm  = bacaTeks("\nJudul Film               : ");
        String genre      = bacaTeks("\nGenre                    : ");
        int durasi        = bacaInt("\nDurasi (menit)           : ");
        String tanggal    = bacaTeks("\nTanggal (YYYY-MM-DD)     : ");
        String jam        = bacaTeks("\nJam (HH:MM)              : ");
        String studio     = bacaTeks("\nStudio                   : ");
        int hargaTiket    = bacaInt("\nHarga Tiket Dasar        : ");

        // --- Langkah 3: input data khusus + buat objek sesuai jenis ---
        if (jenis == 1) {
            // REGULER: tidak ada data tambahan.
            // 'new' membuat objek, lalu add() memasukkannya ke ArrayList.
            daftarReguler.add(new PenayanganReguler(judulFilm, genre, durasi, tanggal, jam, studio, hargaTiket));
        } else if (jenis == 2) {
            // 3D: butuh satu data tambahan, yaitu biaya kacamata.
            int biayaKacamata = bacaInt("\nBiaya Kacamata 3D        : ");
            daftar3D.add(new Penayangan3D(judulFilm, genre, durasi, tanggal, jam, studio, hargaTiket, biayaKacamata));
        } else {
            // IMAX (jenis == 3): butuh dua data tambahan.
            // ukuranLayar bertipe double karena boleh desimal (mis. 22.5).
            double ukuranLayar = bacaDouble("\nUkuran Layar (meter)     : ");
            int biayaPremium   = bacaInt("\nBiaya Premium IMAX       : ");
            daftarIMAX.add(new PenayanganIMAX(judulFilm, genre, durasi, tanggal, jam, studio, hargaTiket, ukuranLayar, biayaPremium));
        }

        System.out.println("\n>> Data berhasil ditambahkan!");
    }

    // =================================================================
    // FUNGSI BANTU (GENERIC): tambahBaris
    // =================================================================
    // Helper: ubah satu object penayangan menjadi satu baris String[] untuk tabel.
    // Dipanggil terpisah untuk tiap jenis (Reguler, 3D, IMAX).
    //
    // PENJELASAN GENERIC:
    //   '<T extends Penayangan>' adalah padanan 'template <typename T>' di C++:
    //   satu method yang dapat menerima PenayanganReguler, Penayangan3D,
    //   maupun PenayanganIMAX (semuanya turunan Penayangan).
    //   Catatan: di Java, pemanggilan obj.getJenis() dll. dipilih saat program
    //   berjalan berdasarkan tipe objek sebenarnya (dynamic binding),
    //   bukan saat kompilasi seperti template C++.
    //
    // PARAMETER:
    //   baris : tabel penampung seluruh baris (list dikirim sebagai referensi)
    //   obj   : objek penayangan yang akan dijadikan satu baris
    //   nomor : nomor urut baris ini (int dikirim sebagai NILAI/salinan;
    //           penambahan nomor dilakukan oleh pemanggil, lihat 'nomor++'
    //           di tampilkanTabelPenayangan)
    static <T extends Penayangan> void tambahBaris(List<String[]> baris, T obj, int nomor) {
        // Urutan isi array di bawah HARUS sama dengan urutan judul kolom
        // pada array 'header' di tampilkanTabelPenayangan().
        String[] row = {
            String.valueOf(nomor),                        // kolom No
            obj.getJenis(),                               // kolom Jenis
            obj.getJudulFilm(),                           // kolom Judul Film    (dari InformasiFilm)
            obj.getGenre(),                               // kolom Genre         (dari InformasiFilm)
            obj.getDurasiMenit() + " mnt",                // kolom Durasi        (dari InformasiFilm)
            obj.getTanggal(),                             // kolom Tanggal       (dari komponen jadwal)
            obj.getJam(),                                 // kolom Jam           (dari komponen jadwal)
            obj.getNamaStudio(),                          // kolom Studio        (dari komponen jadwal)
            String.valueOf(obj.getHargaTiket()),          // kolom Harga Tiket   (dari Penayangan)
            obj.getInfoTambahan(),                        // kolom Info Tambahan (beda tiap jenis)
            String.valueOf(obj.getTotalHarga())           // kolom Total Bayar   (beda tiap jenis)
        };
        baris.add(row);   // masukkan baris jadi ke tabel penampung
    }

    // =================================================================
    // FUNGSI BANTU CETAK TABEL: cetakGaris & cetakBaris
    // =================================================================
    // Di C++ keduanya berupa lambda di dalam fungsi tampilkan; di Java
    // dibuat sebagai method static biasa dan menerima 'lebar' sebagai parameter.

    // Mencetak garis pemisah, contoh: +----+-------+------+
    static void cetakGaris(int[] lebar) {
        System.out.print("+");
        for (int c = 0; c < lebar.length; c++) {
            System.out.print("-".repeat(lebar[c]) + "+");   // repeat(n) = ulangi teks n kali (Java 11+)
        }
        System.out.println();
    }

    // Mencetak satu baris data, contoh: | 1 | Reguler | ... |
    // String.format("%-Ns", teks) = teks rata kiri dengan lebar N karakter
    // (padanan 'left << setw(N)' di C++). (lebar - 1) karena sudah ada
    // 1 spasi di depan (" ") sebagai padding kiri.
    static void cetakBaris(int[] lebar, String[] row) {
        System.out.print("|");
        for (int c = 0; c < lebar.length; c++) {
            System.out.print(" " + String.format("%-" + (lebar[c] - 1) + "s", row[c]) + "|");
        }
        System.out.println();
    }

    // =================================================================
    // FUNGSI READ: tampilkanTabelPenayangan
    // =================================================================
    // READ: menampilkan seluruh data (dari 3 list berbeda) dalam SATU TABEL
    // yang lebar kolomnya menyesuaikan isi data (dinamis)
    //
    // ALUR FUNGSI:
    //   1. Jika ketiga list kosong -> tampilkan pesan lalu keluar.
    //   2. Siapkan judul kolom (header).
    //   3. Kumpulkan semua data menjadi "tabel teks" (List<String[]>)
    //      dengan memanggil tambahBaris() untuk tiap objek di tiap list.
    //   4. Hitung lebar tiap kolom = teks terpanjang di kolom itu.
    //   5. Cetak: garis -> header -> garis -> semua baris -> garis.
    static void tampilkanTabelPenayangan(List<PenayanganReguler> daftarReguler,
                                        List<Penayangan3D> daftar3D,
                                        List<PenayanganIMAX> daftarIMAX) {
        System.out.println("\n--- Daftar Seluruh Penayangan ---");

        // --- Langkah 1: cek apakah ada data ---
        if (daftarReguler.isEmpty() && daftar3D.isEmpty() && daftarIMAX.isEmpty()) {
            System.out.println("(Belum ada data penayangan)");
            return;   // tidak ada yang perlu dicetak
        }

        // --- Langkah 2: judul kolom ---
        String[] header = {"No", "Jenis", "Judul Film", "Genre", "Durasi",
                            "Tanggal", "Jam", "Studio", "Harga Tiket",
                            "Info Tambahan", "Total Bayar"};
        int kolom = header.length;   // jumlah kolom (11)

        // --- Langkah 3: kumpulkan baris dari ketiga list ---
        // Urutan tampil dikelompokkan per jenis: semua Reguler dulu, lalu 3D,
        // lalu IMAX (BUKAN berdasarkan urutan data dimasukkan).
        List<String[]> baris = new ArrayList<>();
        int nomor = 1;   // nomor urut dimulai dari 1
        // 'nomor++' (post-increment): nilai yang dikirim ke tambahBaris adalah
        // nilai SEBELUM ditambah, lalu nomor naik 1 untuk baris berikutnya.
        // Jadi baris pertama bernomor 1, kedua 2, dst., meski datang dari 3 list berbeda.
        for (PenayanganReguler obj : daftarReguler) tambahBaris(baris, obj, nomor++);
        for (Penayangan3D obj : daftar3D)           tambahBaris(baris, obj, nomor++);
        for (PenayanganIMAX obj : daftarIMAX)       tambahBaris(baris, obj, nomor++);

        // --- Langkah 4: hitung lebar tiap kolom secara dinamis ---
        // Untuk tiap kolom c: mulai dari panjang judul kolom, lalu bandingkan
        // dengan panjang teks di setiap baris; ambil yang paling besar.
        int[] lebar = new int[kolom];
        for (int c = 0; c < kolom; c++) {
            lebar[c] = header[c].length();
            for (String[] row : baris) {
                lebar[c] = Math.max(lebar[c], row[c].length());
            }
            lebar[c] += 2;   // padding (ruang kosong 1 spasi kiri dan 1 spasi kanan)
        }

        // --- Langkah 5: cetak seluruh tabel ---
        cetakGaris(lebar);                 // garis atas
        cetakBaris(lebar, header);         // baris judul kolom
        cetakGaris(lebar);                 // garis pemisah header dan isi
        for (String[] row : baris) {       // semua baris data
            cetakBaris(lebar, row);
        }
        cetakGaris(lebar);                 // garis bawah
    }

    // =================================================================
    // FUNGSI UTAMA: main
    // =================================================================
    // 'public static void main(String[] args)' adalah pintu masuk program Java:
    // JVM mencari method dengan tanda tangan persis seperti ini untuk memulai.
    public static void main(String[] args) {

        // ---- Tahap 1: siapkan penyimpanan data ----
        // Tiga list terpisah sesuai jenis tayangan (bukan satu List<Penayangan>).
        // ArrayList = array dinamis yang ukurannya bertambah otomatis saat add().
        List<PenayanganReguler> daftarReguler = new ArrayList<>();
        List<Penayangan3D> daftar3D = new ArrayList<>();
        List<PenayanganIMAX> daftarIMAX = new ArrayList<>();

        // ---- Tahap 2: isi data awal ----
        // 5 data awal (hardcode), tersebar ke ketiga list sesuai jenisnya
        // Urutan argumen: judul, genre, durasi, tanggal, jam, studio, harga
        // (+ biaya kacamata untuk 3D; + ukuran layar & biaya premium untuk IMAX)
        daftarReguler.add(new PenayanganReguler("Avengers: Doomsday", "Aksi", 180, "2026-12-01", "19:00", "Studio 1", 50000));
        daftar3D.add(new Penayangan3D("Spider-Man: Brand New Day", "Aksi", 150, "2026-08-25", "20:00", "Studio 2", 60000, 15000));
        daftarIMAX.add(new PenayanganIMAX("Dune: Part Three", "Sci-Fi", 165, "2026-10-10", "21:00", "Studio 3", 70000, 22.5, 35000));
        daftarReguler.add(new PenayanganReguler("Zootopia 2", "Animasi", 108, "2026-11-15", "16:30", "Studio 4", 45000));
        daftar3D.add(new Penayangan3D("Avatar: Fire and Ash", "Fantasi", 195, "2026-12-19", "18:00", "Studio 5", 65000, 20000));

        // ---- Tahap 3: loop menu utama ----
        // do-while dipilih karena menu harus tampil MINIMAL SEKALI sebelum
        // kondisi (pilihan != 0) dicek.
        int pilihan;
        do {
            // Tampilkan menu
            System.out.println("\n===== MENU PENGELOLAAN PENAYANGAN BIOSKOP =====");
            System.out.println("1. Tambah Penayangan (Create)");
            System.out.println("2. Tampilkan Semua Penayangan (Tabel)");
            System.out.println("0. Keluar");
            pilihan = bacaInt("Pilihan Anda: ");

            // Arahkan ke method sesuai pilihan user
            switch (pilihan) {
                case 1:
                    // CREATE: kirim ketiga list agar data baru tersimpan
                    tambahPenayangan(daftarReguler, daftar3D, daftarIMAX);
                    break;
                case 2:
                    // READ: tampilkan gabungan isi ketiga list dalam satu tabel
                    tampilkanTabelPenayangan(daftarReguler, daftar3D, daftarIMAX);
                    break;
                case 0:
                    // Pesan penutup; loop akan berhenti karena pilihan == 0
                    System.out.println("Terima kasih, program selesai.");
                    break;
                default:
                    // Angka selain 0/1/2 -> menu ditampilkan lagi
                    System.out.println(">> Pilihan tidak valid, coba lagi.");
            }
        } while (pilihan != 0);   // ulangi menu selama user belum memilih 0

        input.close();   // menutup Scanner setelah selesai dipakai
    }
}
