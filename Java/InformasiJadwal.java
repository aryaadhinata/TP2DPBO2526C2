// =====================================================================
// FILE   : InformasiJadwal.java
// PERAN  : BASE CLASS 2 (dalam versi C++) -> di Java dipakai sebagai
//          KOMPONEN yang dimiliki oleh Penayangan (composition).
// ISI    : Menyimpan data yang berhubungan dengan JADWAL & LOKASI:
//          tanggal, jam tayang, dan nama studio.
// ALUR   : Penayangan membuat satu objek InformasiJadwal di dalam dirinya,
//          lalu meneruskan (delegasi) getter/setter-nya.
//
// KENAPA TIDAK 'extends' LAGI?
//   Java TIDAK mengizinkan sebuah class mewarisi DUA class sekaligus
//   (class Penayangan extends InformasiFilm, InformasiJadwal -> error).
//   Alasannya untuk menghindari "diamond problem" (bentrok bila kedua
//   parent punya anggota bernama sama). Solusi yang umum dipakai:
//     - satu parent lewat 'extends'  -> InformasiFilm
//     - parent lainnya lewat composition ("has-a") -> InformasiJadwal
//   Hasil akhirnya sama: objek Penayangan tetap punya data film DAN jadwal.
// =====================================================================

// Merepresentasikan jadwal & lokasi penayangan di bioskop
public class InformasiJadwal {

    // Atribut dibuat 'private' karena class ini dipakai lewat objek
    // (composition), bukan diwarisi. Akses dari luar cukup lewat getter/setter.
    private String tanggal;      // format YYYY-MM-DD, contoh: "2026-12-01"
    private String jam;          // format HH:MM, contoh: "19:00"
    private String namaStudio;   // contoh: "Studio 1"

    // ---- Constructor default ----
    // Mengisi semua atribut dengan string kosong.
    public InformasiJadwal() {
        this.tanggal = "";
        this.jam = "";
        this.namaStudio = "";
    }

    // ---- Constructor berparameter ----
    // Dipanggil oleh constructor Penayangan untuk mengisi data jadwal.
    public InformasiJadwal(String tanggal, String jam, String namaStudio) {
        this.tanggal = tanggal;
        this.jam = jam;
        this.namaStudio = namaStudio;
    }

    // ---- Setter & getter ----
    public void setTanggal(String tanggal) { this.tanggal = tanggal; }
    public String getTanggal() { return tanggal; }

    public void setJam(String jam) { this.jam = jam; }
    public String getJam() { return jam; }

    public void setNamaStudio(String namaStudio) { this.namaStudio = namaStudio; }
    public String getNamaStudio() { return namaStudio; }
}
