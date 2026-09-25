# =====================================================================
# FILE   : Penayangan.py
# PERAN  : DERIVED CLASS (turunan) sekaligus BASE CLASS untuk 3 jenis
#          tayangan (Reguler, 3D, IMAX).
#
# HIERARKI PEWARISAN PROGRAM INI (sama seperti versi C++):
#
#      InformasiFilm      InformasiJadwal
#       (judul, genre,     (tanggal, jam,
#        durasi)            studio)
#              \              /
#               \            /        <- MULTIPLE INHERITANCE
#                 Penayangan            (+ harga_tiket)
#                /     |      \
#               /      |       \      <- SINGLE INHERITANCE
#   PenayanganReguler Penayangan3D PenayanganIMAX
#
# CATATAN PENTING (Python vs Java):
#   Java TIDAK mengizinkan "class X extends A, B" (harus disiasati dengan
#   composition, seperti versi Java sebelumnya). Python JUSTRU mengizinkan
#   multiple inheritance secara langsung, sehingga struktur di sini bisa
#   dibuat identik dengan versi C++:
#       class Penayangan(InformasiFilm, InformasiJadwal): ...
# =====================================================================

from InformasiFilm import InformasiFilm
from InformasiJadwal import InformasiJadwal


class Penayangan(InformasiFilm, InformasiJadwal):
    """Gabungan data film + jadwal, ditambah harga tiket dasar."""

    # ---- Constructor ----
    # Semua parameter diberi nilai default agar Penayangan() (tanpa
    # argumen) tetap valid, meniru constructor default C++/Java.
    def __init__(self, judul_film="", genre="", durasi_menit=0,
                 tanggal="", jam="", nama_studio="", harga_tiket=0):
        # PENTING: karena InformasiFilm.__init__ dan InformasiJadwal.__init__
        # menerima parameter yang BERBEDA (bukan rantai yang sama), kedua
        # constructor parent dipanggil secara EKSPLISIT satu per satu
        # (bukan lewat super(), yang biasanya dipakai kalau tanda tangan
        # constructor semua parent sama/berantai). Ini padanan langsung
        # dari initializer list C++:
        #   Penayangan(...) : InformasiFilm(...), InformasiJadwal(...) { ... }
        InformasiFilm.__init__(self, judul_film, genre, durasi_menit)
        InformasiJadwal.__init__(self, tanggal, jam, nama_studio)

        # Atribut milik class ini sendiri
        self.harga_tiket = harga_tiket

    # Setter & getter untuk atribut milik class ini sendiri
    def setHargaTiket(self, harga_tiket):
        self.harga_tiket = harga_tiket

    def getHargaTiket(self):
        return self.harga_tiket

    # ===== Method dasar: nilai default untuk Penayangan itu sendiri =====
    # Tiap class anak (Reguler/3D/IMAX) akan mendefinisikan ulang ketiga
    # method di bawah ini dengan isi masing-masing.
    #
    # CATATAN PYTHON vs C++: di C++, method tidak diberi kata kunci
    # 'virtual', sehingga pemanggilan lewat pointer/reference base class
    # akan memilih versi base class (static binding). Python TIDAK
    # mengenal konsep 'virtual' sama sekali -- SEMUA method di Python
    # selalu dipilih berdasarkan tipe objek sebenarnya saat program
    # berjalan (perilaku ini otomatis, mirip 'dynamic binding'/polymorphism
    # bawaan Java). Meski begitu, program ini TETAP TIDAK memanfaatkan hal
    # itu secara sengaja: objek tiap jenis tetap disimpan di list masing-
    # masing (bukan satu list gabungan), persis seperti desain aslinya.

    def getJenis(self):
        """Mengembalikan nama jenis tayangan (untuk kolom 'Jenis' di tabel)."""
        return "Reguler"

    def getInfoTambahan(self):
        """Mengembalikan keterangan fasilitas tambahan (kolom 'Info Tambahan')."""
        return "-"

    def getTotalHarga(self):
        """Mengembalikan total yang harus dibayar (kolom 'Total Bayar')."""
        return self.harga_tiket

    def tampilkan(self):
        """Mencetak seluruh detail satu penayangan dalam bentuk daftar
        (bukan tabel). Method ini tidak dipanggil di Main.py saat ini,
        tetapi tersedia bila dibutuhkan."""
        print(f"Jenis         : {self.getJenis()}")
        print(f"Judul Film    : {self.judul_film}")      # dari InformasiFilm
        print(f"Genre         : {self.genre}")           # dari InformasiFilm
        print(f"Durasi        : {self.durasi_menit} menit")  # dari InformasiFilm
        print(f"Tanggal       : {self.tanggal}")         # dari InformasiJadwal
        print(f"Jam           : {self.jam}")             # dari InformasiJadwal
        print(f"Studio        : {self.nama_studio}")     # dari InformasiJadwal
        print(f"Harga Tiket   : {self.harga_tiket}")     # milik Penayangan
        print(f"Info Tambahan : {self.getInfoTambahan()}")
        print(f"Total Bayar   : {self.getTotalHarga()}")

    # Catatan: tidak perlu destructor seperti ~Penayangan() di C++.
    # Python memiliki garbage collector yang membebaskan memori otomatis.
