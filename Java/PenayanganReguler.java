// =====================================================================
// FILE   : PenayanganReguler.java
// PERAN  : Class turunan dari Penayangan (jenis tayangan #1)
// ISI    : Tayangan kelas reguler: tidak ada biaya tambahan, sehingga
//          class ini TIDAK punya atribut baru; hanya mendefinisikan
//          ulang tiga method identitas (jenis, info, total harga).
// ALUR   : Di Main.java, objek jenis ini disimpan di
//          ArrayList<PenayanganReguler>.
// =====================================================================

// Objek nyata: tayangan bioskop kelas reguler (studio biasa, tanpa fasilitas khusus)
public class PenayanganReguler extends Penayangan {

    // Constructor default: langsung meneruskan ke constructor default
    // Penayangan lewat super(). Tidak ada atribut baru untuk diisi.
    public PenayanganReguler() {
        super();
    }

    // Constructor berparameter: menerima 7 nilai dan meneruskan
    // SEMUANYA ke constructor Penayangan (yang lalu membagikannya ke
    // InformasiFilm dan komponen InformasiJadwal). Tidak ada nilai tambahan.
    public PenayanganReguler(String judulFilm, String genre, int durasiMenit,
                            String tanggal, String jam, String namaStudio,
                            int hargaTiket) {
        super(judulFilm, genre, durasiMenit, tanggal, jam, namaStudio, hargaTiket);
    }

    // Redefinisi method khusus untuk objek bertipe PenayanganReguler.
    // '@Override' = penanda bagi compiler bahwa method ini menggantikan
    // method bernama sama milik Penayangan; jika salah ketik nama/parameter,
    // compiler langsung memberi error.

    // Jenis untuk kolom "Jenis" di tabel
    @Override
    public String getJenis() {
        return "Reguler";
    }

    // Keterangan untuk kolom "Info Tambahan" di tabel
    @Override
    public String getInfoTambahan() {
        return "Tanpa fasilitas tambahan";
    }

    // Reguler tidak ada biaya tambahan -> total = harga tiket dasar
    @Override
    public int getTotalHarga() {
        return hargaTiket;
    }
}
