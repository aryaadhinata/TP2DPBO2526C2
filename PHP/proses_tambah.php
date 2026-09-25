<?php
// =====================================================================
// FILE   : proses_tambah.php
// PERAN  : Padanan fungsi tambahPenayangan() di versi C++/Java/Python.
//          Bedanya, di sini input TIDAK datang dari keyboard (cin/
//          input()), melainkan dari FORM HTML yang dikirim browser
//          lewat method POST (lihat form di index.php).
//
// ALUR FUNGSI:
//   1. Pastikan halaman ini memang diakses lewat submit form (POST).
//   2. Ambil & validasi 'jenis' tayangan (1/2/3) dari $_POST.
//   3. Ambil data umum (judul, genre, durasi, tanggal, jam, studio, harga).
//   4. Proses upload file foto (jika ada) -> simpan ke folder uploads/,
//      dapatkan NAMA FILE barunya untuk disimpan sebagai fotoFilm.
//   5. Ambil data khusus sesuai jenis, buat objek, tambahkan ke session.
//   6. Redirect kembali ke index.php (pola "Post/Redirect/Get" agar
//      form tidak "double submit" saat user menekan refresh/kembali).
// =====================================================================

// =====================================================================
// PERSIAPAN AWAL -- persis sama dengan blok di index.php.
// ("bootstrap" di sini cuma istilah PHP untuk "kode persiapan awal",
// TIDAK ADA HUBUNGANNYA dengan framework Bootstrap CSS/JS.)
// =====================================================================
spl_autoload_register(function ($namaClass) {
    $path = __DIR__ . '/classes/' . $namaClass . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function inisialisasiData() {
    if (!isset($_SESSION['daftarReguler'])) {
        $_SESSION['daftarReguler'] = [];
        $_SESSION['daftar3D'] = [];
        $_SESSION['daftarIMAX'] = [];

        $_SESSION['daftarReguler'][] = new PenayanganReguler(
            "Avengers: Doomsday", "Aksi", 180, "", "2026-12-01", "19:00", "Studio 1", 50000
        );
        $_SESSION['daftar3D'][] = new Penayangan3D(
            "Spider-Man: Brand New Day", "Aksi", 150, "", "2026-08-25", "20:00", "Studio 2", 60000, 15000
        );
        $_SESSION['daftarIMAX'][] = new PenayanganIMAX(
            "Dune: Part Three", "Sci-Fi", 165, "", "2026-10-10", "21:00", "Studio 3", 70000, 22.5, 35000
        );
        $_SESSION['daftarReguler'][] = new PenayanganReguler(
            "Zootopia 2", "Animasi", 108, "", "2026-11-15", "16:30", "Studio 4", 45000
        );
        $_SESSION['daftar3D'][] = new Penayangan3D(
            "Avatar: Fire and Ash", "Fantasi", 195, "", "2026-12-19", "18:00", "Studio 5", 65000, 20000
        );
    }
}
inisialisasiData();

// --- Langkah 1: pastikan diakses lewat POST ---
// Jika seseorang membuka proses_tambah.php langsung lewat address bar
// (method GET), tidak ada data yang diproses -- cukup lempar kembali
// ke index.php. Ini padanan validasi input di C++ (mis. cek jenis
// valid) tapi di level "cara mengakses halaman".
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// --- Langkah 2: ambil & validasi jenis tayangan ---
// (int) di depan $_POST[...] mengubah teks dari form menjadi angka --
// padanan konversi otomatis yang dilakukan "cin >> jenis" di C++.
// '?? 0' berarti: jika field 'jenis' tidak terkirim sama sekali (mis.
// form rusak), anggap 0 (otomatis tidak valid -> masuk ke pesan error).
$jenis = (int) ($_POST['jenis'] ?? 0);

if ($jenis < 1 || $jenis > 3) {
    // 'pesan' disimpan di session sebagai FLASH MESSAGE: pesan yang
    // hanya ditampilkan SEKALI di halaman berikutnya, lalu otomatis
    // dihapus (lihat index.php bagian menampilkan pesan). Ini padanan
    // dari "cout << ... ; return;" pada validasi jenis tidak valid.
    $_SESSION['pesan'] = ['tipe' => 'error', 'teks' => 'Jenis tidak valid, data batal ditambahkan!'];
    header('Location: index.php');
    exit;
}

// --- Langkah 3: ambil data umum ---
// trim() membuang spasi di awal/akhir teks (kebiasaan baik saat
// menerima input form). htmlspecialchars TIDAK dipakai di sini karena
// yang disimpan adalah data mentah; nanti index.php-lah yang wajib
// meng-escape saat MENAMPILKAN data ini kembali (mencegah celah XSS).
$judulFilm  = trim($_POST['judulFilm'] ?? '');
$genre      = trim($_POST['genre'] ?? '');
$durasi     = (int) ($_POST['durasi'] ?? 0);
$tanggal    = trim($_POST['tanggal'] ?? '');
$jam        = trim($_POST['jam'] ?? '');
$studio     = trim($_POST['studio'] ?? '');
$hargaTiket = (int) ($_POST['hargaTiket'] ?? 0);

// --- Langkah 4: proses upload foto (opsional) ---
// $_FILES['foto'] otomatis diisi PHP setiap kali form punya
// <input type="file" name="foto"> DAN atribut form enctype=
// "multipart/form-data" (lihat index.php). Tanpa enctype itu, upload
// file TIDAK akan pernah terkirim, walau field-nya kelihatan terisi
// di browser.
$fotoFilm = "";   // default: tidak ada foto

if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $ekstensiDiizinkan = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $namaAsli = $_FILES['foto']['name'];
    $ekstensi = strtolower(pathinfo($namaAsli, PATHINFO_EXTENSION));

    if (in_array($ekstensi, $ekstensiDiizinkan, true)) {
        // Folder tujuan upload. Dibuat otomatis jika belum ada, supaya
        // proyek ini tetap berjalan walau folder uploads/ kosong belum
        // pernah dibuat manual.
        $folderUpload = __DIR__ . '/uploads/';
        if (!is_dir($folderUpload)) {
            mkdir($folderUpload, 0755, true);
        }

        // Nama file baru dibuat UNIK (uniqid()) supaya dua orang yang
        // upload file dengan nama sama (mis. "poster.jpg") tidak saling
        // menimpa file satu sama lain di server.
        $namaFileBaru = 'foto_' . uniqid() . '.' . $ekstensi;

        // move_uploaded_file() memindahkan file dari folder sementara
        // PHP ke folder tujuan kita. Mengembalikan true jika berhasil.
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $folderUpload . $namaFileBaru)) {
            $fotoFilm = $namaFileBaru;   // inilah yang disimpan sebagai atribut fotoFilm
        }
    }
    // Jika ekstensi tidak diizinkan, foto diabaikan (fotoFilm tetap "").
    // Bisa dikembangkan lebih lanjut dengan menampilkan pesan error
    // khusus untuk kasus ini bila diperlukan.
}

// --- Langkah 5: buat objek sesuai jenis, simpan ke session ---
if ($jenis === 1) {
    // REGULER: tidak ada data tambahan.
    $_SESSION['daftarReguler'][] = new PenayanganReguler(
        $judulFilm, $genre, $durasi, $fotoFilm, $tanggal, $jam, $studio, $hargaTiket
    );
} elseif ($jenis === 2) {
    // 3D: butuh satu data tambahan, yaitu biaya kacamata.
    $biayaKacamata = (int) ($_POST['biayaKacamata'] ?? 0);
    $_SESSION['daftar3D'][] = new Penayangan3D(
        $judulFilm, $genre, $durasi, $fotoFilm, $tanggal, $jam, $studio, $hargaTiket, $biayaKacamata
    );
} else {
    // IMAX (jenis == 3): butuh dua data tambahan.
    // (float) dipakai karena ukuran layar boleh desimal (mis. 22.5).
    $ukuranLayar  = (float) ($_POST['ukuranLayar'] ?? 0);
    $biayaPremium = (int) ($_POST['biayaPremium'] ?? 0);
    $_SESSION['daftarIMAX'][] = new PenayanganIMAX(
        $judulFilm, $genre, $durasi, $fotoFilm, $tanggal, $jam, $studio, $hargaTiket, $ukuranLayar, $biayaPremium
    );
}

$_SESSION['pesan'] = ['tipe' => 'sukses', 'teks' => 'Data berhasil ditambahkan!'];

// --- Langkah 6: redirect (pola Post/Redirect/Get) ---
// Setelah data disimpan, browser diarahkan BALIK ke index.php dengan
// method GET (bukan menampilkan hasil langsung dari sini). Ini mencegah
// data "terkirim dua kali" kalau user menekan tombol refresh/kembali
// pada browser setelah submit form -- masalah yang tidak pernah muncul
// di versi CLI (C++/Java/Python) karena di sana tidak ada konsep
// "refresh halaman".
header('Location: index.php');
exit;
