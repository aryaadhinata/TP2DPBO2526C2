# =====================================================================
# FILE   : PenayanganIMAX.py
# PERAN  : Class turunan dari Penayangan (jenis tayangan #3)
# ISI    : Tayangan IMAX. Menambah DUA atribut baru: ukuran layar dan
#          biaya premium. Total harga = harga tiket dasar + biaya premium.
# ALUR   : Di Main.py, objek jenis ini disimpan di list Python biasa
#          (daftar_imax).
# =====================================================================

from Penayangan import Penayangan


class PenayanganIMAX(Penayangan):
    """Tayangan bioskop IMAX (layar raksasa, harga tiket premium)."""

    # ---- Constructor ----
    # Menerima 7 parameter milik Penayangan DITAMBAH dua parameter baru:
    # ukuran_layar_meter (boleh desimal, mis. 22.5) dan biaya_premium.
    def __init__(self, judul_film="", genre="", durasi_menit=0,
                 tanggal="", jam="", nama_studio="", harga_tiket=0,
                 ukuran_layar_meter=0.0, biaya_premium=0):
        # super().__init__(...) membangun bagian Penayangan (dan otomatis
        # InformasiFilm + InformasiJadwal di baliknya) lebih dulu.
        super().__init__(judul_film, genre, durasi_menit,
                          tanggal, jam, nama_studio, harga_tiket)
        self.ukuran_layar_meter = ukuran_layar_meter
        self.biaya_premium = biaya_premium

    # Setter & getter untuk atribut khusus IMAX
    def setUkuranLayarMeter(self, v):
        self.ukuran_layar_meter = v

    def getUkuranLayarMeter(self):
        return self.ukuran_layar_meter

    def setBiayaPremium(self, v):
        self.biaya_premium = v

    def getBiayaPremium(self):
        return self.biaya_premium

    # Redefinisi (override) method khusus untuk objek bertipe PenayanganIMAX.

    def getJenis(self):
        return "IMAX"

    def getInfoTambahan(self):
        # Contoh hasil: "Layar 22m (+Rp35000)"
        # int(self.ukuran_layar_meter) membuang bagian desimal: 22.5 tampil
        # sebagai 22, karena menuliskan float apa adanya akan menghasilkan
        # "22.5" yang formatnya beda dari versi C++/Java.
        return f"Layar {int(self.ukuran_layar_meter)}m (+Rp{self.biaya_premium})"

    def getTotalHarga(self):
        # Total = harga tiket dasar (warisan dari Penayangan) + biaya premium
        return self.harga_tiket + self.biaya_premium
