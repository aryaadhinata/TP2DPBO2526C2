// =====================================================================
// FILE   : InformasiFilm.java
// PERAN  : BASE CLASS 1 (superclass dari class Penayangan)
// ISI    : Menyimpan data yang berhubungan dengan FILM saja:
//          judul, genre, dan durasi.
// ALUR   : Class ini DIWARISI oleh Penayangan (Penayangan extends InformasiFilm).
//
// CATATAN JAVA vs C++:
//   - Di Java tidak ada include guard / #include. Cukup satu class per file,
//     dan nama file HARUS sama dengan nama class public-nya
//     (InformasiFilm.java -> class InformasiFilm).
//   - Semua file .java dalam satu folder otomatis saling mengenali saat
//     dikompilasi bersama (javac *.java).
// =====================================================================

// Merepresentasikan data film yang sedang tayang di bioskop
public class InformasiFilm {

    // 'protected' = tidak bisa diakses dari luar package/class lain,
    // tetapi BISA diakses langsung oleh class turunan (Penayangan dst.).
    protected String judulFilm;   // judul film, contoh: "Zootopia 2"
    protected String genre;       // genre film, contoh: "Animasi"
    protected int durasiMenit;    // lama film dalam menit, contoh: 108

    // ---- Constructor default ----
    // Dipakai saat objek dibuat tanpa argumen; semua atribut diberi
    // nilai awal kosong/0 supaya tidak berisi null.
    public InformasiFilm() {
        this.judulFilm = "";
        this.genre = "";
        this.durasiMenit = 0;
    }

    // ---- Constructor berparameter ----
    // Dipanggil oleh constructor Penayangan lewat super(...)
    // untuk mengisi data film sekaligus saat objek dibuat.
    // 'this.judulFilm' = atribut milik objek; 'judulFilm' = parameter.
    // Dengan 'this.' nama parameter boleh sama dengan nama atribut
    // (di C++ sebelumnya dipakai akhiran 'P' untuk membedakan).
    public InformasiFilm(String judulFilm, String genre, int durasiMenit) {
        this.judulFilm = judulFilm;
        this.genre = genre;
        this.durasiMenit = durasiMenit;
    }

    // ---- Setter & getter ----
    // Setter : mengubah nilai atribut dari luar class.
    // Getter : membaca nilai atribut dari luar class.
    // Di Main.java, getter inilah yang dipakai untuk mengisi baris tabel.
    public void setJudulFilm(String judulFilm) { this.judulFilm = judulFilm; }
    public String getJudulFilm() { return judulFilm; }

    public void setGenre(String genre) { this.genre = genre; }
    public String getGenre() { return genre; }

    public void setDurasiMenit(int durasiMenit) { this.durasiMenit = durasiMenit; }
    public int getDurasiMenit() { return durasiMenit; }
}
