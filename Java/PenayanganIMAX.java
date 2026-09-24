// =====================================================================
// FILE   : PenayanganIMAX.java
// PERAN  : Class turunan dari Penayangan (jenis tayangan #3)
// ISI    : Tayangan IMAX. Menambah DUA atribut baru: ukuran layar dan
//          biaya premium. Total harga = harga tiket dasar + biaya premium.
// ALUR   : Di Main.java, objek jenis ini disimpan di
//          ArrayList<PenayanganIMAX>.
// =====================================================================

// Objek nyata: tayangan bioskop IMAX (layar raksasa, harga tiket premium)
public class PenayanganIMAX extends Penayangan {

    private double ukuranLayarMeter;   // lebar layar dalam meter (boleh desimal, mis. 22.5)
    private int biayaPremium;          // biaya tambahan khusus IMAX (dalam Rupiah)

    // Constructor default: panggil constructor default parent,
    // lalu beri nilai awal 0 untuk atribut milik class ini.
    public PenayanganIMAX() {
        super();
        this.ukuranLayarMeter = 0;
        this.biayaPremium = 0;
    }

    // Constructor berparameter (9 nilai):
    //   7 nilai pertama -> diteruskan ke constructor Penayangan lewat super(...)
    //   ukuranLayarMeter dan biayaPremium -> disimpan di class ini
    public PenayanganIMAX(String judulFilm, String genre, int durasiMenit,
                            String tanggal, String jam, String namaStudio,
                            int hargaTiket, double ukuranLayarMeter, int biayaPremium) {
        super(judulFilm, genre, durasiMenit, tanggal, jam, namaStudio, hargaTiket);
        this.ukuranLayarMeter = ukuranLayarMeter;
        this.biayaPremium = biayaPremium;
    }

    // Setter & getter untuk atribut khusus IMAX
    public void setUkuranLayarMeter(double ukuranLayarMeter) { this.ukuranLayarMeter = ukuranLayarMeter; }
    public double getUkuranLayarMeter() { return ukuranLayarMeter; }

    public void setBiayaPremium(int biayaPremium) { this.biayaPremium = biayaPremium; }
    public int getBiayaPremium() { return biayaPremium; }

    // Redefinisi method khusus untuk objek bertipe PenayanganIMAX
    // (@Override = menggantikan versi milik Penayangan).

    // Jenis untuk kolom "Jenis" di tabel
    @Override
    public String getJenis() {
        return "IMAX";
    }

    // Contoh hasil: "Layar 22m (+Rp35000)"
    // (int) ukuranLayarMeter membuang bagian desimal: 22.5 tampil sebagai 22,
    // karena menggabungkan double langsung akan menampilkan "22.5".
    @Override
    public String getInfoTambahan() {
        return "Layar " + (int) ukuranLayarMeter + "m (+Rp" + biayaPremium + ")";
    }

    // Total = harga tiket dasar (warisan dari Penayangan) + biaya premium
    @Override
    public int getTotalHarga() {
        return hargaTiket + biayaPremium;
    }
}
