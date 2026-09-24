// =====================================================================
// FILE   : Penayangan3D.java
// PERAN  : Class turunan dari Penayangan (jenis tayangan #2)
// ISI    : Tayangan 3D. Menambah SATU atribut baru: biayaKacamata.
//          Total harga = harga tiket dasar + biaya kacamata.
// ALUR   : Di Main.java, objek jenis ini disimpan di
//          ArrayList<Penayangan3D>.
// =====================================================================

// Objek nyata: tayangan bioskop 3D (penonton wajib pakai kacamata khusus)
public class Penayangan3D extends Penayangan {

    // Atribut khusus 3D: biaya sewa/beli kacamata (dalam Rupiah).
    // 'private' karena tidak ada class lain yang mewarisi Penayangan3D.
    private int biayaKacamata;

    // Constructor default: panggil constructor default parent,
    // lalu beri nilai awal 0 untuk atribut milik class ini.
    public Penayangan3D() {
        super();
        this.biayaKacamata = 0;
    }

    // Constructor berparameter (8 nilai):
    //   7 nilai pertama -> diteruskan ke constructor Penayangan lewat super(...)
    //   nilai ke-8      -> biayaKacamata, disimpan di class ini
    // Urutan eksekusi: super(...) dijalankan dulu (Penayangan dibangun),
    // baru baris berikutnya mengisi biayaKacamata.
    public Penayangan3D(String judulFilm, String genre, int durasiMenit,
                        String tanggal, String jam, String namaStudio,
                        int hargaTiket, int biayaKacamata) {
        super(judulFilm, genre, durasiMenit, tanggal, jam, namaStudio, hargaTiket);
        this.biayaKacamata = biayaKacamata;
    }

    // Setter & getter untuk atribut khusus 3D
    public void setBiayaKacamata(int biayaKacamata) { this.biayaKacamata = biayaKacamata; }
    public int getBiayaKacamata() { return biayaKacamata; }

    // Redefinisi method khusus untuk objek bertipe Penayangan3D
    // (@Override = menggantikan versi milik Penayangan).

    // Jenis untuk kolom "Jenis" di tabel
    @Override
    public String getJenis() {
        return "3D";
    }

    // Contoh hasil: "Kacamata 3D (+Rp15000)"
    // Di Java, angka (int) otomatis diubah jadi teks saat digabung dengan
    // String memakai '+', jadi tidak perlu to_string() seperti di C++.
    @Override
    public String getInfoTambahan() {
        return "Kacamata 3D (+Rp" + biayaKacamata + ")";
    }

    // Total = harga tiket dasar (warisan dari Penayangan) + biaya kacamata
    @Override
    public int getTotalHarga() {
        return hargaTiket + biayaKacamata;
    }
}
