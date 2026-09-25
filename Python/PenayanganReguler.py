# =====================================================================
# FILE   : PenayanganReguler.py
# PERAN  : Class turunan dari Penayangan (jenis tayangan #1)
# ISI    : Tayangan kelas reguler: tidak ada biaya tambahan, sehingga
#          class ini TIDAK punya atribut baru; hanya mendefinisikan
#          ulang tiga method identitas (jenis, info, total harga).
# ALUR   : Di Main.py, objek jenis ini disimpan di list Python biasa
#          (daftar_reguler).
# =====================================================================

from Penayangan import Penayangan


class PenayanganReguler(Penayangan):
    """Tayangan bioskop kelas reguler (studio biasa, tanpa fasilitas khusus)."""

    # Tidak perlu __init__ sendiri: karena tidak ada atribut baru,
    # constructor milik Penayangan (parent) otomatis dipakai apa adanya
    # -- ini padanan langsung dari constructor kosong "{}" yang cuma
    # memanggil parent di versi C++/Java.

    # Redefinisi (override) method khusus untuk objek bertipe PenayanganReguler.
    # Di Python, method dengan nama sama di class anak OTOMATIS menimpa
    # (override) method sama di class induk -- tidak perlu kata kunci
    # khusus seperti '@Override' di Java.

    def getJenis(self):
        return "Reguler"

    def getInfoTambahan(self):
        return "Tanpa fasilitas tambahan"

    def getTotalHarga(self):
        # Reguler tidak ada biaya tambahan -> total = harga tiket dasar
        return self.harga_tiket
