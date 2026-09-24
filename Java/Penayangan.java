// =====================================================================
// FILE   : Penayangan.java
// PERAN  : Class turunan dari InformasiFilm sekaligus class induk untuk
//          3 jenis tayangan (Reguler, 3D, IMAX).
//
// HIERARKI KELAS PROGRAM INI:
//
//      InformasiFilm  <---------- (extends)  ----------+
//      (judul, genre,                                  |
//       durasi)                                    Penayangan  (+ hargaTiket)
//                                                  /    |     \
//      InformasiJadwal  <-- (composition: has-a) --+     |      \
//      (tanggal, jam,                    PenayanganReguler |  PenayanganIMAX
//       studio)                                       Penayangan3D
//
//   Padanan dengan versi C++ (multiple inheritance):
//     - InformasiFilm   : diwarisi langsung (extends)
//     - InformasiJadwal : dimiliki sebagai objek di dalam Penayangan
//       (karena Java tidak mengizinkan extends dua class)
//
// ISI    : Gabungan data film + jadwal, ditambah harga tiket dasar,
//          dan method dasar untuk jenis, info tambahan, total harga.
// =====================================================================

public class Penayangan extends InformasiFilm {

    // ---- Komponen jadwal (composition) ----
    // Objek InformasiJadwal ini dibuat di constructor. Semua akses ke
    // tanggal/jam/studio diteruskan (didelegasikan) ke objek ini.
    private InformasiJadwal jadwal;

    // Harga tiket dasar (belum termasuk biaya tambahan kacamata/premium).
    // 'protected' supaya class anak (3D, IMAX) bisa memakainya untuk
    // menghitung total harga.
    protected int hargaTiket;

    // ---- Constructor default ----
    // super() memanggil constructor default InformasiFilm (parent).
    // Untuk jadwal, dibuat objek kosong; hargaTiket diisi 0.
    public Penayangan() {
        super();
        this.jadwal = new InformasiJadwal();
        this.hargaTiket = 0;
    }

    // ---- Constructor berparameter ----
    // ALUR: 7 nilai diterima dari class anak -> dibagi ke dua sumber data:
    //   3 nilai pertama  -> super(...)             => InformasiFilm (judul, genre, durasi)
    //   3 nilai berikut  -> new InformasiJadwal(...) => komponen jadwal (tanggal, jam, studio)
    //   1 nilai terakhir -> disimpan sendiri di hargaTiket
    // Aturan Java: pemanggilan super(...) HARUS menjadi baris pertama
    // di dalam constructor, jadi parent selalu dibangun lebih dulu.
    public Penayangan(String judulFilm, String genre, int durasiMenit,
                        String tanggal, String jam, String namaStudio,
                        int hargaTiket) {
        super(judulFilm, genre, durasiMenit);
        this.jadwal = new InformasiJadwal(tanggal, jam, namaStudio);
        this.hargaTiket = hargaTiket;
    }

    // ---- Method delegasi untuk data jadwal ----
    // Agar dari luar, objek Penayangan tetap bisa dipanggil seperti punya
    // getTanggal(), getJam(), dst. secara langsung (sama seperti di C++).
    public void setTanggal(String tanggal) { jadwal.setTanggal(tanggal); }
    public String getTanggal() { return jadwal.getTanggal(); }

    public void setJam(String jam) { jadwal.setJam(jam); }
    public String getJam() { return jadwal.getJam(); }

    public void setNamaStudio(String namaStudio) { jadwal.setNamaStudio(namaStudio); }
    public String getNamaStudio() { return jadwal.getNamaStudio(); }

    // Setter & getter untuk atribut milik class ini sendiri
    public void setHargaTiket(int hargaTiket) { this.hargaTiket = hargaTiket; }
    public int getHargaTiket() { return hargaTiket; }

    // ===== Method dasar: nilai default untuk Penayangan itu sendiri =====
    // Tiap class anak mendefinisikan ulang method dengan nama & parameter sama.
    //
    // CATATAN JAVA vs C++: di C++ method tidak 'virtual', sehingga yang
    // dipanggil ditentukan oleh TIPE VARIABEL (static binding). Di Java,
    // redefinisi method dengan tanda tangan sama otomatis menjadi OVERRIDE
    // dan yang dipanggil ditentukan oleh TIPE OBJEK SEBENARNYA saat program
    // berjalan (dynamic binding) -- ini perilaku bawaan Java dan tidak bisa
    // dimatikan. Program ini tetap tidak memanfaatkannya secara sengaja:
    // objek tiap jenis disimpan di list terpisah (bukan List<Penayangan>).

    // Mengembalikan nama jenis tayangan (untuk kolom "Jenis" di tabel)
    public String getJenis() {
        return "Reguler";
    }

    // Mengembalikan keterangan fasilitas tambahan (kolom "Info Tambahan")
    public String getInfoTambahan() {
        return "-";
    }

    // Mengembalikan total yang harus dibayar (kolom "Total Bayar").
    // Versi dasar: total = harga tiket saja.
    public int getTotalHarga() {
        return hargaTiket;
    }

    // Mencetak seluruh detail satu penayangan dalam bentuk daftar
    // (bukan tabel). Method ini tidak dipanggil di Main.java saat ini,
    // tetapi tersedia bila dibutuhkan.
    public void tampilkan() {
        System.out.println("Jenis         : " + getJenis());
        System.out.println("Judul Film    : " + judulFilm);          // dari InformasiFilm
        System.out.println("Genre         : " + genre);              // dari InformasiFilm
        System.out.println("Durasi        : " + durasiMenit + " menit");  // dari InformasiFilm
        System.out.println("Tanggal       : " + getTanggal());       // dari komponen jadwal
        System.out.println("Jam           : " + getJam());           // dari komponen jadwal
        System.out.println("Studio        : " + getNamaStudio());    // dari komponen jadwal
        System.out.println("Harga Tiket   : " + hargaTiket);         // milik Penayangan
        System.out.println("Info Tambahan : " + getInfoTambahan());
        System.out.println("Total Bayar   : " + getTotalHarga());
    }

    // Catatan: tidak perlu destructor seperti di C++ (~Penayangan).
    // Java memiliki Garbage Collector yang membebaskan memori otomatis.
}
