# =====================================================================
# FILE   : Penayangan3D.py
# PERAN  : Class turunan dari Penayangan (jenis tayangan #2)
# ISI    : Tayangan 3D. Menambah SATU atribut baru: biaya_kacamata.
#          Total harga = harga tiket dasar + biaya kacamata.
# ALUR   : Di Main.py, objek jenis ini disimpan di list Python biasa
#          (daftar_3d).
# =====================================================================

from Penayangan import Penayangan


class Penayangan3D(Penayangan):
    """Tayangan bioskop 3D (penonton wajib pakai kacamata khusus)."""

    # ---- Constructor ----
    # Menerima 7 parameter milik Penayangan (parent) DITAMBAH satu
    # parameter baru: biaya_kacamata (default 0, meniru constructor
    # default C++/Java).
    def __init__(self, judul_film="", genre="", durasi_menit=0,
                 tanggal="", jam="", nama_studio="", harga_tiket=0,
                 biaya_kacamata=0):
        # super().__init__(...) memanggil constructor Penayangan (parent
        # langsung), yang lalu meneruskan data film & jadwal ke
        # InformasiFilm dan InformasiJadwal. 7 nilai pertama diteruskan
        # apa adanya; biaya_kacamata TIDAK ikut diteruskan karena hanya
        # dikenal oleh class ini sendiri.
        super().__init__(judul_film, genre, durasi_menit,
                          tanggal, jam, nama_studio, harga_tiket)
        self.biaya_kacamata = biaya_kacamata

    # Setter & getter untuk atribut khusus 3D
    def setBiayaKacamata(self, biaya_kacamata):
        self.biaya_kacamata = biaya_kacamata

    def getBiayaKacamata(self):
        return self.biaya_kacamata

    # Redefinisi (override) method khusus untuk objek bertipe Penayangan3D.

    def getJenis(self):
        return "3D"

    def getInfoTambahan(self):
        # Contoh hasil: "Kacamata 3D (+Rp15000)"
        # f-string ("f\"...{x}...\"") otomatis mengubah angka jadi teks
        # saat disisipkan, jadi tidak perlu fungsi seperti to_string()
        # di C++ atau konversi manual seperti di Java.
        return f"Kacamata 3D (+Rp{self.biaya_kacamata})"

    def getTotalHarga(self):
        # Total = harga tiket dasar (warisan dari Penayangan) + biaya kacamata
        return self.harga_tiket + self.biaya_kacamata
