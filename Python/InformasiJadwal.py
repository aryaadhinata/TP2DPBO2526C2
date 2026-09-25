# =====================================================================
# FILE   : InformasiJadwal.py
# PERAN  : BASE CLASS 2 (orang tua kedua dari class Penayangan)
# ISI    : Menyimpan data yang berhubungan dengan JADWAL & LOKASI:
#          tanggal, jam tayang, dan nama studio.
# ALUR   : Class ini diimpor dan diwarisi oleh Penayangan.py BERSAMA
#          InformasiFilm -> inilah multiple inheritance.
# =====================================================================

class InformasiJadwal:
    """Merepresentasikan jadwal & lokasi penayangan di bioskop."""

    # ---- Constructor ----
    # Nilai default "" untuk ketiganya meniru constructor default C++/Java.
    def __init__(self, tanggal="", jam="", nama_studio=""):
        self.tanggal = tanggal
        self.jam = jam
        self.nama_studio = nama_studio

    # ---- Setter & getter ----
    def setTanggal(self, tanggal):
        self.tanggal = tanggal

    def getTanggal(self):
        return self.tanggal

    def setJam(self, jam):
        self.jam = jam

    def getJam(self):
        return self.jam

    def setNamaStudio(self, nama_studio):
        self.nama_studio = nama_studio

    def getNamaStudio(self):
        return self.nama_studio
