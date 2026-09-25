<?php
// =====================================================================
// FILE   : index.php
// PERAN  : Halaman utama aplikasi -- padanan MENU + main() di versi
//          C++/Java/Python, tapi digabung jadi SATU HALAMAN (form
//          "Tambah" dan tabel "Tampilkan" tampil bersamaan), karena
//          pola web umumnya tidak memakai menu teks bernomor seperti
//          program CLI.
//
// GAMBARAN ALUR HALAMAN INI:
//   1. bootstrap.php disiapkan lebih dulu (autoload class + session +
//      data awal) -- WAJIB sebelum baris HTML apa pun.
//   2. Tampilkan pesan flash (sukses/error) hasil dari proses_tambah.php,
//      jika ada.
//   3. Tampilkan form "Tambah Penayangan" (mengarah/POST ke proses_tambah.php).
//      Field khusus 3D/IMAX disembunyikan/ditampilkan dengan sedikit
//      JavaScript, mengikuti pilihan dropdown jenis.
//   4. Bangun & tampilkan tabel gabungan dari ketiga array session,
//      persis seperti tampilkanTabelPenayangan() di versi lain, hanya
//      saja HTML/CSS yang otomatis mengatur lebar kolom -- TIDAK perlu
//      lagi menghitung lebar kolom manual seperti versi terminal.
// =====================================================================

// =====================================================================
// PERSIAPAN AWAL (dulu di file terpisah includes/bootstrap.php,
// sekarang di-inline langsung di sini agar lebih sederhana/"vanilla" --
// tidak perlu file tambahan).
//
// CATATAN: kata "bootstrap" di komentar sebelumnya TIDAK ADA HUBUNGANNYA
// dengan framework CSS/JS "Bootstrap" (Twitter Bootstrap). Di dunia PHP,
// "bootstrap" hanya istilah umum untuk "kode persiapan yang dijalankan
// paling awal" sebelum halaman diproses -- kebetulan namanya sama saja.
// Halaman ini murni PHP + HTML + CSS + JS biasa, TIDAK memakai
// framework/library front-end apa pun.
// =====================================================================

// Autoload class: setiap kali kode memakai nama class/trait yang belum
// pernah di-require, PHP otomatis mencarinya di folder classes/.
// Padanan sederhana dari #include (C++) / import (Java, Python), tapi
// otomatis dan hanya sekali daftar untuk semua class.
spl_autoload_register(function ($namaClass) {
    $path = __DIR__ . '/classes/' . $namaClass . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

// Mulai session. WAJIB dipanggil SEBELUM ada output apa pun (echo,
// HTML, dll), makanya diletakkan paling atas file.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Isi 5 data awal, HANYA SEKALI per session (isset mencegah data
// user yang sudah ditambahkan tertimpa ulang tiap halaman dibuka).
// Padanan 5 baris hardcode di awal main() versi C++/Java/Python.
function inisialisasiData() {
    if (!isset($_SESSION['daftarReguler'])) {
        $_SESSION['daftarReguler'] = [];
        $_SESSION['daftar3D'] = [];
        $_SESSION['daftarIMAX'] = [];

        // fotoFilm cukup diisi NAMA FILE saja (JANGAN diberi awalan
        // "uploads/" atau "uploads\") -- prefix folder uploads/ sudah
        // otomatis ditambahkan saat menampilkan <img> di tabel (lihat
        // bagian render tabel di bawah). Backslash "\" juga tidak boleh
        // dipakai karena itu pemisah folder gaya Windows, sedangkan
        // path di HTML/URL selalu memakai forward slash "/".
        // Agar foto-foto ini benar-benar tampil, taruh file dengan nama
        // PERSIS berikut di dalam folder uploads/:
        //   AvengersDoomsday.jpg, SpidermanBrandNewDayPoster.jpg,
        //   DunePartThree.jpg, Zootopia2.jpg, AvatarFireAndAsh.jpg
        $_SESSION['daftarReguler'][] = new PenayanganReguler(
            "Avengers: Doomsday", "Aksi", 180, "AvengersDoomsday.jpg", "2026-12-01", "19:00", "Studio 1", 50000
        );
        $_SESSION['daftar3D'][] = new Penayangan3D(
            "Spider-Man: Brand New Day", "Aksi", 150, "SpidermanBrandNewDayPoster.jpg", "2026-08-25", "20:00", "Studio 2", 60000, 15000
        );
        $_SESSION['daftarIMAX'][] = new PenayanganIMAX(
            "Dune: Part Three", "Sci-Fi", 165, "DunePartThree.jpg", "2026-10-10", "21:00", "Studio 3", 70000, 22.5, 35000
        );
        $_SESSION['daftarReguler'][] = new PenayanganReguler(
            "Zootopia 2", "Animasi", 108, "Zootopia2.jpg", "2026-11-15", "16:30", "Studio 4", 45000
        );
        $_SESSION['daftar3D'][] = new Penayangan3D(
            "Avatar: Fire and Ash", "Fantasi", 195, "AvatarFireAndAsh.jpg", "2026-12-19", "18:00", "Studio 5", 65000, 20000
        );
    }
}
inisialisasiData();

// --- Langkah 2: ambil & hapus pesan flash ---
// Pola "ambil lalu hapus" adalah inti flash message: pesan ini hanya
// boleh tampil SEKALI (di halaman setelah redirect), lalu tidak muncul
// lagi walau halaman di-refresh berkali-kali setelahnya.
$pesan = $_SESSION['pesan'] ?? null;
unset($_SESSION['pesan']);

// --- Langkah 4 (bagian data): kumpulkan baris tabel ---
// Padanan langsung dari tambahBaris() + tampilkanTabelPenayangan() di
// versi C++/Java/Python: kumpulkan objek dari ketiga array, lalu ubah
// jadi baris-baris data untuk ditampilkan. 'foreach' di PHP setara
// 'for (auto& obj : ...)' di C++ atau 'for (var obj : ...)' di Java.
$semuaBaris = [];
$nomor = 1;   // nomor urut dimulai dari 1

foreach ($_SESSION['daftarReguler'] as $obj) {
    $semuaBaris[] = ['nomor' => $nomor++, 'obj' => $obj];
}
foreach ($_SESSION['daftar3D'] as $obj) {
    $semuaBaris[] = ['nomor' => $nomor++, 'obj' => $obj];
}
foreach ($_SESSION['daftarIMAX'] as $obj) {
    $semuaBaris[] = ['nomor' => $nomor++, 'obj' => $obj];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Pengelolaan Penayangan Bioskop</title>
<style>
    /* Catatan: berbeda dari tabel teks di terminal (C++/Java/Python)
        yang lebar kolomnya dihitung manual karakter demi karakter,
        di web CSS-lah yang otomatis mengatur lebar kolom mengikuti
       isinya -- tidak perlu fungsi cetakGaris()/cetakBaris() lagi. */
    body { font-family: Arial, Helvetica, sans-serif; margin: 24px; background: #f5f5f5; color: #222; }
    h1 { font-size: 1.4rem; }
    h2 { font-size: 1.1rem; margin-top: 32px; }
    .kotak { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 16px 20px; max-width: 720px; }
    .pesan { padding: 10px 14px; border-radius: 6px; margin-bottom: 16px; max-width: 720px; }
    .pesan.sukses { background: #e6f4ea; color: #1e7e34; border: 1px solid #b7e1c1; }
    .pesan.error  { background: #fdecea; color: #a32626; border: 1px solid #f5c2be; }
    label { display: block; margin-top: 10px; font-size: 0.9rem; font-weight: bold; }
    input, select { width: 100%; padding: 6px 8px; margin-top: 4px; box-sizing: border-box; }
    .field-khusus { display: none; }
    button { margin-top: 16px; padding: 8px 18px; background: #2c6fbb; color: #fff; border: none; border-radius: 6px; cursor: pointer; }
    button:hover { background: #24588f; }
    table { border-collapse: collapse; width: 100%; margin-top: 12px; background: #fff; font-size: 0.9rem; }
    th, td { border: 1px solid #ddd; padding: 8px 10px; text-align: left; vertical-align: middle; }
    th { background: #2c6fbb; color: #fff; }
    tr:nth-child(even) { background: #fafafa; }
    img.poster { width: 60px; height: auto; border-radius: 4px; display: block; }
    .tanpa-foto { color: #999; font-style: italic; font-size: 0.85rem; }
    .table-wrap { overflow-x: auto; } /* agar tabel bisa discroll di layar sempit */
</style>
</head>
<body>

<h1>Menu Pengelolaan Penayangan Bioskop</h1>

<?php if ($pesan): ?>
    <!-- htmlspecialchars() WAJIB dipakai setiap kali menampilkan data
        yang berasal dari input pengguna, untuk mencegah celah keamanan
        XSS (kalau tidak, orang bisa memasukkan potongan HTML/JS jahat
        lewat form dan itu akan ikut dijalankan browser pengunjung lain). -->
    <div class="pesan <?= htmlspecialchars($pesan['tipe']) ?>">
        <?= htmlspecialchars($pesan['teks']) ?>
    </div>
<?php endif; ?>

<div class="kotak">
    <h2>1. Tambah Penayangan (Create)</h2>

    <!-- enctype="multipart/form-data" WAJIB ada agar file foto ikut
        terkirim. Tanpa atribut ini, $_FILES di proses_tambah.php akan
        selalu kosong walau user sudah memilih file. -->
    <form action="proses_tambah.php" method="POST" enctype="multipart/form-data">

        <label for="jenis">Jenis Tayangan</label>
        <select name="jenis" id="jenis" onchange="tampilkanFieldKhusus()">
            <option value="1">Reguler</option>
            <option value="2">3D</option>
            <option value="3">IMAX</option>
        </select>

        <label for="judulFilm">Judul Film</label>
        <input type="text" name="judulFilm" id="judulFilm" required>

        <label for="genre">Genre</label>
        <input type="text" name="genre" id="genre" required>

        <label for="durasi">Durasi (menit)</label>
        <input type="number" name="durasi" id="durasi" min="1" required>

        <label for="foto">Foto / Poster Film (opsional)</label>
        <input type="file" name="foto" id="foto" accept="image/*">

        <label for="tanggal">Tanggal</label>
        <input type="date" name="tanggal" id="tanggal" required>

        <label for="jam">Jam</label>
        <input type="time" name="jam" id="jam" required>

        <label for="studio">Studio</label>
        <input type="text" name="studio" id="studio" required>

        <label for="hargaTiket">Harga Tiket Dasar</label>
        <input type="number" name="hargaTiket" id="hargaTiket" min="0" required>

        <!-- Field khusus 3D: disembunyikan lewat CSS (class "field-khusus"),
            dimunculkan oleh JavaScript hanya saat jenis = 3D. -->
        <div id="fieldReguler3D" class="field-khusus">
            <label for="biayaKacamata">Biaya Kacamata 3D</label>
            <input type="number" name="biayaKacamata" id="biayaKacamata" min="0">
        </div>

        <!-- Field khusus IMAX -->
        <div id="fieldIMAX" class="field-khusus">
            <label for="ukuranLayar">Ukuran Layar (meter)</label>
            <input type="number" step="0.1" name="ukuranLayar" id="ukuranLayar" min="0">

            <label for="biayaPremium">Biaya Premium IMAX</label>
            <input type="number" name="biayaPremium" id="biayaPremium" min="0">
        </div>

        <button type="submit">Tambah Penayangan</button>
    </form>
</div>

<h2>2. Tampilkan Semua Penayangan (Tabel)</h2>

<?php if (empty($semuaBaris)): ?>
    <!-- Padanan "(Belum ada data penayangan)" di versi C++/Java/Python -->
    <p>(Belum ada data penayangan)</p>
<?php else: ?>
    <div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Jenis</th>
                <th>Judul Film</th>
                <th>Genre</th>
                <th>Durasi</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Studio</th>
                <th>Harga Tiket</th>
                <th>Info Tambahan</th>
                <th>Total Bayar</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($semuaBaris as $item): ?>
            <?php $obj = $item['obj']; ?>
            <tr>
                <td><?= $item['nomor'] ?></td>
                <td>
                    <?php
                        // Selain adaFoto() (cek nama filenya diisi atau tidak),
                        // di sini juga dicek file_exists() -- apakah file itu
                        // BENAR-BENAR ada secara fisik di folder uploads/.
                        // Tanpa pengecekan ini, jika fotoFilm terisi nama file
                        // tapi filenya belum ditaruh di uploads/ (mis. data awal
                        // yang posternya belum diupload manual), browser akan
                        // menampilkan ikon "gambar rusak", bukan placeholder rapi.
                        $adaFileFoto = $obj->adaFoto() && file_exists(__DIR__ . '/uploads/' . $obj->getFotoFilm());
                    ?>
                    <?php if ($adaFileFoto): ?>
                        <!-- Path gambar: folder uploads/ + nama file yang
                            disimpan saat proses upload di proses_tambah.php -->
                        <img class="poster" src="uploads/<?= htmlspecialchars($obj->getFotoFilm()) ?>" alt="Poster <?= htmlspecialchars($obj->getJudulFilm()) ?>">
                    <?php else: ?>
                        <span class="tanpa-foto">Tidak ada foto</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($obj->getJenis()) ?></td>
                <td><?= htmlspecialchars($obj->getJudulFilm()) ?></td>
                <td><?= htmlspecialchars($obj->getGenre()) ?></td>
                <td><?= $obj->getDurasiMenit() ?> mnt</td>
                <td><?= htmlspecialchars($obj->getTanggal()) ?></td>
                <td><?= htmlspecialchars($obj->getJam()) ?></td>
                <td><?= htmlspecialchars($obj->getNamaStudio()) ?></td>
                <td><?= $obj->getHargaTiket() ?></td>
                <td><?= htmlspecialchars($obj->getInfoTambahan()) ?></td>
                <td><?= $obj->getTotalHarga() ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
<?php endif; ?>

<script>
// Menampilkan/menyembunyikan field khusus (biaya kacamata / ukuran
// layar & biaya premium) sesuai jenis tayangan yang dipilih user --
// padanan percabangan if(jenis==1)/else if(jenis==2)/else di
// tambahPenayangan() versi C++/Java/Python, tapi dijalankan di sisi
// BROWSER (bukan server) agar tampilan berubah seketika tanpa reload.
function tampilkanFieldKhusus() {
    const jenis = document.getElementById('jenis').value;
    const field3D = document.getElementById('fieldReguler3D');
    const fieldIMAX = document.getElementById('fieldIMAX');

    field3D.style.display = (jenis === '2') ? 'block' : 'none';
    fieldIMAX.style.display = (jenis === '3') ? 'block' : 'none';
}
// Jalankan sekali saat halaman pertama dimuat, supaya kondisi awal
// (jenis = Reguler) sudah benar menyembunyikan kedua field khusus.
tampilkanFieldKhusus();
</script>

</body>
</html>