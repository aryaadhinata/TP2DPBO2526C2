# =====================================================================
# FILE   : InformasiFilm.py
# PERAN  : BASE CLASS 1 (salah satu "orang tua" dari class Penayangan)
# ISI    : Menyimpan data yang berhubungan dengan FILM saja:
#          judul, genre, dan durasi.
# ALUR   : Class ini diimpor dan diwarisi oleh Penayangan.py
#          (class Penayangan(InformasiFilm, InformasiJadwal): ...)
#
# CATATAN PYTHON vs C++/JAVA:
#   - Python TIDAK butuh include guard / import guard: Python otomatis
#     hanya menjalankan isi sebuah file (module) satu kali walau
#     di-import dari banyak tempat.
#   - Python (berbeda dari Java) mengizinkan multiple inheritance secara
#     langsung, jadi struktur pewarisannya bisa persis sama seperti versi
#     C++ (Penayangan mewarisi InformasiFilm DAN InformasiJadwal sekaligus).
# =====================================================================

class InformasiFilm:
    """Merepresentasikan data film yang sedang tayang di bioskop."""

    # ---- Constructor ----
    # Python hanya punya SATU constructor per class, bernama __init__.
    # Untuk meniru "constructor default" (C++/Java) DAN "constructor
    # berparameter" sekaligus, ketiga parameter diberi nilai default
    # ("" dan 0). Jadi InformasiFilm() dan InformasiFilm("A","B",1)
    # sama-sama valid, seperti overloading constructor di bahasa lain.
    def __init__(self, judul_film="", genre="", durasi_menit=0):
        # Semua atribut di Python otomatis bisa diakses layaknya
        # 'protected' di C++ (konvensi: tidak ada penanda private/
        # protected/public seperti C++/Java; garis bawah tunggal di depan
        # nama, mis. "_x", HANYA konvensi "jangan diakses dari luar",
        # bukan larangan sesungguhnya). Di sini nama dibiarkan polos
        # karena class turunan memang perlu mengaksesnya langsung.
        self.judul_film = judul_film
        self.genre = genre
        self.durasi_menit = durasi_menit

    # ---- Setter & getter ----
    # Sebenarnya di Python atribut bisa diakses langsung (obj.judul_film),
    # tetapi getter/setter tetap dibuat di sini agar strukturnya sama
    # persis dengan versi C++ dan Java, dan agar Main.py bisa memanggil
    # obj.getJudulFilm() dst. seperti pada kedua versi sebelumnya.
    def setJudulFilm(self, judul_film):
        self.judul_film = judul_film

    def getJudulFilm(self):
        return self.judul_film

    def setGenre(self, genre):
        self.genre = genre

    def getGenre(self):
        return self.genre

    def setDurasiMenit(self, durasi_menit):
        self.durasi_menit = durasi_menit

    def getDurasiMenit(self):
        return self.durasi_menit
