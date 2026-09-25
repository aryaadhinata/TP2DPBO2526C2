# =====================================================================
# FILE   : Main.py
# PERAN  : Program utama (titik masuk / entry point) aplikasi
#          "Pengelolaan Penayangan Bioskop".
#
# GAMBARAN ALUR PROGRAM:
#   1. main() membuat 3 list kosong (Reguler, 3D, IMAX).
#   2. main() mengisi 5 data awal (hardcode) ke list yang sesuai.
#   3. main() masuk ke loop menu (while True + break) sampai user
#      memilih 0:
#        - Pilihan 1 -> tambah_penayangan()            (CREATE)
#        - Pilihan 2 -> tampilkan_tabel_penayangan()   (READ)
#        - Pilihan 0 -> keluar dari loop, program selesai
#
# KONSEP UTAMA YANG DIPAKAI:
#   - Multiple inheritance : Penayangan(InformasiFilm, InformasiJadwal)
#                            (Python mengizinkan ini langsung, tidak
#                             seperti Java)
#   - Single inheritance   : Reguler / 3D / IMAX mewarisi Penayangan
#   - Tiap jenis punya list sendiri (bukan satu list gabungan), meski
#     Python selalu memakai dynamic dispatch untuk semua method
#
# CARA MENJALANKAN:
#   python3 Main.py
# (Pastikan Main.py berada satu folder dengan file .py lain, karena
#  Python mengimpor class-class itu dari file terpisah.)
# =====================================================================

from PenayanganReguler import PenayanganReguler
from Penayangan3D import Penayangan3D
from PenayanganIMAX import PenayanganIMAX


# =========================================================================
# FUNGSI BANTU INPUT: baca_int, baca_float
# =========================================================================
# CATATAN PYTHON vs C++:
#   Di C++, "cin >> angka" meninggalkan '\n' di buffer sehingga
#   getline() berikutnya perlu dibersihkan dulu (bersihkanBuffer()).
#   Di Python, fungsi bawaan input() SELALU membaca satu baris penuh
#   dan mengembalikannya sebagai string, sehingga masalah semacam itu
#   tidak pernah muncul -- tidak perlu fungsi pembersih buffer sama sekali.
#   Fungsi teks biasa cukup dipanggil langsung: judul = input("...")

def baca_int(pertanyaan):
    """Menampilkan pertanyaan, lalu membaca bilangan bulat.
    Jika user mengetik selain angka, pertanyaan diulang (tidak crash)."""
    while True:
        teks = input(pertanyaan).strip()
        try:
            return int(teks)
        except ValueError:
            print(">> Input harus berupa bilangan bulat, coba lagi.")


def baca_float(pertanyaan):
    """Sama seperti baca_int, tetapi untuk bilangan desimal (mis. 22.5).
    Koma ',' (gaya Indonesia) juga diterima dan diubah jadi titik '.'."""
    while True:
        teks = input(pertanyaan).strip().replace(',', '.')
        try:
            return float(teks)
        except ValueError:
            print(">> Input harus berupa angka, coba lagi.")


# =========================================================================
# FUNGSI CREATE: tambah_penayangan
# =========================================================================
# CREATE: menambahkan object baru ke list sesuai jenisnya masing-masing
# (setiap jenis punya list objek sendiri, bukan satu list gabungan)
#
# PARAMETER: ketiga list Python dikirim ke sini. Di Python, list adalah
#            objek mutable yang dikirim sebagai referensi, sehingga
#            .append(...) di dalam fungsi ini langsung terlihat oleh
#            pemanggilnya (main()) -- padanan tanda '&' (reference) di
#            C++ / referensi objek di Java. TIDAK perlu 'return' list
#            yang sudah diubah.
#
# ALUR FUNGSI:
#   1. Tanya jenis tayangan (1/2/3) -> jika di luar rentang, batalkan.
#   2. Tanya data UMUM yang sama untuk semua jenis
#      (judul, genre, durasi, tanggal, jam, studio, harga tiket).
#   3. Tanya data KHUSUS sesuai jenis (kacamata untuk 3D; ukuran layar
#      & biaya premium untuk IMAX), lalu buat objek dan append ke
#      list yang sesuai.
def tambah_penayangan(daftar_reguler, daftar_3d, daftar_imax):
    # --- Langkah 1: pilih jenis tayangan ---
    print("\n--- Tambah Penayangan Baru ---")
    print("Pilih jenis tayangan:")
    print("1. Reguler")
    print("2. 3D")
    print("3. IMAX")
    jenis = baca_int("Pilihan jenis            : ")

    # Validasi: hanya 1, 2, atau 3 yang diterima. 'return' langsung
    # keluar dari fungsi sehingga tidak ada data yang ditambahkan.
    if jenis < 1 or jenis > 3:
        print(">> Jenis tidak valid, data batal ditambahkan!")
        return

    # --- Langkah 2: input data umum (berlaku untuk semua jenis) ---
    judul_film = input("\nJudul Film               : ")
    genre = input("\nGenre                    : ")
    durasi = baca_int("\nDurasi (menit)           : ")
    tanggal = input("\nTanggal (YYYY-MM-DD)     : ")
    jam = input("\nJam (HH:MM)              : ")
    studio = input("\nStudio                   : ")
    harga_tiket = baca_int("\nHarga Tiket Dasar        : ")

    # --- Langkah 3: input data khusus + buat objek sesuai jenis ---
    if jenis == 1:
        # REGULER: tidak ada data tambahan.
        daftar_reguler.append(
            PenayanganReguler(judul_film, genre, durasi, tanggal, jam, studio, harga_tiket)
        )
    elif jenis == 2:
        # 3D: butuh satu data tambahan, yaitu biaya kacamata.
        biaya_kacamata = baca_int("\nBiaya Kacamata 3D        : ")
        daftar_3d.append(
            Penayangan3D(judul_film, genre, durasi, tanggal, jam, studio, harga_tiket, biaya_kacamata)
        )
    else:
        # IMAX (jenis == 3): butuh dua data tambahan.
        # ukuran_layar bertipe float karena boleh desimal (mis. 22.5).
        ukuran_layar = baca_float("\nUkuran Layar (meter)     : ")
        biaya_premium = baca_int("\nBiaya Premium IMAX       : ")
        daftar_imax.append(
            PenayanganIMAX(judul_film, genre, durasi, tanggal, jam, studio,
                            harga_tiket, ukuran_layar, biaya_premium)
        )

    print("\n>> Data berhasil ditambahkan!")


# =========================================================================
# FUNGSI BANTU: tambah_baris
# =========================================================================
# Helper: ubah satu object (tipe apapun turunan Penayangan) menjadi satu
# baris list-of-string untuk tabel. Dipanggil terpisah untuk tiap jenis.
#
# CATATAN PYTHON vs C++: di C++ dipakai template <typename T> agar satu
# fungsi bisa menerima 3 tipe objek berbeda tanpa butuh pointer base
# class / virtual function. Python TIDAK memerlukan template maupun
# generic sama sekali -- Python bersifat "duck typing": fungsi ini
# menerima objek APAPUN, asalkan objek itu punya method getJenis(),
# getJudulFilm(), dst. (tidak peduli tipe persisnya apa).
#
# PARAMETER:
#   baris : tabel penampung seluruh baris (list, diisi lewat .append())
#   obj   : objek penayangan yang akan dijadikan satu baris
#   nomor : nomor urut baris ini (dikirim sebagai nilai oleh pemanggil,
#           yang menaikkan nomor sendiri di antara ketiga list)
def tambah_baris(baris, obj, nomor):
    # Urutan elemen di bawah HARUS sama dengan urutan judul kolom
    # pada list 'header' di tampilkan_tabel_penayangan().
    row = [
        str(nomor),                              # kolom No
        obj.getJenis(),                          # kolom Jenis
        obj.getJudulFilm(),                      # kolom Judul Film    (dari InformasiFilm)
        obj.getGenre(),                          # kolom Genre         (dari InformasiFilm)
        f"{obj.getDurasiMenit()} mnt",           # kolom Durasi        (dari InformasiFilm)
        obj.getTanggal(),                        # kolom Tanggal       (dari InformasiJadwal)
        obj.getJam(),                            # kolom Jam           (dari InformasiJadwal)
        obj.getNamaStudio(),                     # kolom Studio        (dari InformasiJadwal)
        str(obj.getHargaTiket()),                # kolom Harga Tiket   (dari Penayangan)
        obj.getInfoTambahan(),                   # kolom Info Tambahan (beda tiap jenis)
        str(obj.getTotalHarga()),                # kolom Total Bayar   (beda tiap jenis)
    ]
    baris.append(row)   # masukkan baris jadi ke tabel penampung


# =========================================================================
# FUNGSI BANTU CETAK TABEL: cetak_garis & cetak_baris
# =========================================================================
# Di C++ keduanya berupa lambda di dalam fungsi tampilkan; di Python
# dibuat sebagai fungsi biasa dan menerima 'lebar' sebagai parameter.

def cetak_garis(lebar):
    """Mencetak garis pemisah, contoh: +----+-------+------+"""
    baris_garis = "+"
    for lb in lebar:
        baris_garis += "-" * lb + "+"   # "-" * n = ulangi karakter n kali
    print(baris_garis)


def cetak_baris(lebar, row):
    """Mencetak satu baris data, contoh: | 1 | Reguler | ... |
    f"{teks:<{n}}" = teks rata kiri dengan lebar n karakter
    (padanan 'left << setw(n)' di C++ / String.format("%-Ns", ...) di Java).
    (lebar - 1) karena sudah ada 1 spasi di depan (" ") sebagai padding kiri."""
    baris_teks = "|"
    for c in range(len(lebar)):
        baris_teks += " " + f"{row[c]:<{lebar[c] - 1}}" + "|"
    print(baris_teks)


# =========================================================================
# FUNGSI READ: tampilkan_tabel_penayangan
# =========================================================================
# READ: menampilkan seluruh data (dari 3 list berbeda) dalam SATU TABEL
# yang lebar kolomnya menyesuaikan isi data (dinamis)
#
# ALUR FUNGSI:
#   1. Jika ketiga list kosong -> tampilkan pesan lalu keluar.
#   2. Siapkan judul kolom (header).
#   3. Kumpulkan semua data menjadi "tabel teks" (list of list of string)
#      dengan memanggil tambah_baris() untuk tiap objek di tiap list.
#   4. Hitung lebar tiap kolom = teks terpanjang di kolom itu.
#   5. Cetak: garis -> header -> garis -> semua baris -> garis.
def tampilkan_tabel_penayangan(daftar_reguler, daftar_3d, daftar_imax):
    print("\n--- Daftar Seluruh Penayangan ---")

    # --- Langkah 1: cek apakah ada data ---
    if not daftar_reguler and not daftar_3d and not daftar_imax:
        # 'not list_kosong' bernilai True di Python -- list kosong
        # dianggap "falsy", padanan .empty() di C++/Java.
        print("(Belum ada data penayangan)")
        return   # tidak ada yang perlu dicetak

    # --- Langkah 2: judul kolom ---
    header = ["No", "Jenis", "Judul Film", "Genre", "Durasi",
              "Tanggal", "Jam", "Studio", "Harga Tiket",
              "Info Tambahan", "Total Bayar"]
    kolom = len(header)   # jumlah kolom (11)

    # --- Langkah 3: kumpulkan baris dari ketiga list ---
    # Urutan tampil dikelompokkan per jenis: semua Reguler dulu, lalu 3D,
    # lalu IMAX (BUKAN berdasarkan urutan data dimasukkan).
    baris = []
    nomor = 1   # nomor urut dimulai dari 1
    for obj in daftar_reguler:
        tambah_baris(baris, obj, nomor)
        nomor += 1
    for obj in daftar_3d:
        tambah_baris(baris, obj, nomor)
        nomor += 1
    for obj in daftar_imax:
        tambah_baris(baris, obj, nomor)
        nomor += 1

    # --- Langkah 4: hitung lebar tiap kolom secara dinamis ---
    # Untuk tiap kolom c: mulai dari panjang judul kolom, lalu bandingkan
    # dengan panjang teks di setiap baris; ambil yang paling besar.
    lebar = [len(h) for h in header]
    for row in baris:
        for c in range(kolom):
            lebar[c] = max(lebar[c], len(row[c]))
    for c in range(kolom):
        lebar[c] += 2   # padding (ruang kosong 1 spasi kiri dan 1 spasi kanan)

    # --- Langkah 5: cetak seluruh tabel ---
    cetak_garis(lebar)              # garis atas
    cetak_baris(lebar, header)      # baris judul kolom
    cetak_garis(lebar)              # garis pemisah header dan isi
    for row in baris:               # semua baris data
        cetak_baris(lebar, row)
    cetak_garis(lebar)              # garis bawah


# =========================================================================
# FUNGSI UTAMA: main
# =========================================================================
def main():
    # ---- Tahap 1: siapkan penyimpanan data ----
    # Tiga list Python terpisah sesuai jenis tayangan (bukan satu list
    # gabungan). List Python sudah otomatis dinamis (tidak perlu
    # deklarasi ukuran seperti array biasa).
    daftar_reguler = []
    daftar_3d = []
    daftar_imax = []

    # ---- Tahap 2: isi data awal ----
    # 5 data awal (hardcode), tersebar ke ketiga list sesuai jenisnya
    # Urutan argumen: judul, genre, durasi, tanggal, jam, studio, harga
    # (+ biaya kacamata untuk 3D; + ukuran layar & biaya premium untuk IMAX)
    daftar_reguler.append(
        PenayanganReguler("Avengers: Doomsday", "Aksi", 180, "2026-12-01", "19:00", "Studio 1", 50000)
    )
    daftar_3d.append(
        Penayangan3D("Spider-Man: Brand New Day", "Aksi", 150, "2026-08-25", "20:00", "Studio 2", 60000, 15000)
    )
    daftar_imax.append(
        PenayanganIMAX("Dune: Part Three", "Sci-Fi", 165, "2026-10-10", "21:00", "Studio 3", 70000, 22.5, 35000)
    )
    daftar_reguler.append(
        PenayanganReguler("Zootopia 2", "Animasi", 108, "2026-11-15", "16:30", "Studio 4", 45000)
    )
    daftar_3d.append(
        Penayangan3D("Avatar: Fire and Ash", "Fantasi", 195, "2026-12-19", "18:00", "Studio 5", 65000, 20000)
    )

    # ---- Tahap 3: loop menu utama ----
    # Python tidak punya do-while bawaan, jadi polanya ditiru dengan
    # "while True" + "break" saat pilihan == 0. Efeknya sama: menu
    # tampil MINIMAL SEKALI sebelum kondisi keluar dicek.
    while True:
        # Tampilkan menu
        print("\n===== MENU PENGELOLAAN PENAYANGAN BIOSKOP =====")
        print("1. Tambah Penayangan (Create)")
        print("2. Tampilkan Semua Penayangan (Tabel)")
        print("0. Keluar")
        pilihan = baca_int("Pilihan Anda: ")

        # Python tidak punya switch/case bawaan (baru ada 'match' sejak
        # Python 3.10), tetapi struktur if/elif berikut fungsinya identik.
        if pilihan == 1:
            # CREATE: kirim ketiga list agar data baru tersimpan
            tambah_penayangan(daftar_reguler, daftar_3d, daftar_imax)
        elif pilihan == 2:
            # READ: tampilkan gabungan isi ketiga list dalam satu tabel
            tampilkan_tabel_penayangan(daftar_reguler, daftar_3d, daftar_imax)
        elif pilihan == 0:
            # Pesan penutup, lalu keluar dari loop
            print("Terima kasih, program selesai.")
            break
        else:
            # Angka selain 0/1/2 -> menu ditampilkan lagi
            print(">> Pilihan tidak valid, coba lagi.")


# =====================================================================
# Titik masuk program.
# Blok ini memastikan main() hanya dijalankan saat Main.py dieksekusi
# langsung (python3 Main.py), BUKAN saat file ini di-import dari file
# lain -- padanan konsep "int main()" yang otomatis dijalankan JVM/OS
# di C++ dan Java.
# =====================================================================
if __name__ == "__main__":
    main()
