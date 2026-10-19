<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode_barang = trim($_POST['kode_barang']);
    $nama_barang = trim($_POST['nama_barang']);
    $merek = trim($_POST['merek']);
    $jumlah = (int) $_POST['jumlah'];
    $kondisi = $_POST['kondisi'];
    $lokasi = trim($_POST['lokasi']);

    $kondisi_valid = ['Baik', 'Rusak Ringan', 'Rusak Berat'];

    if (
        $kode_barang === '' ||
        $nama_barang === '' ||
        $jumlah < 0 ||
        !in_array($kondisi, $kondisi_valid, true)
    ) {
        $pesan = 'Periksa kembali data yang kamu masukkan.';
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO barang (kode_barang, nama_barang, merek, jumlah, kondisi, lokasi)
             VALUES (?, ?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssiss",
            $kode_barang,
            $nama_barang,
            $merek,
            $jumlah,
            $kondisi,
            $lokasi
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: index.php");
            exit;
        } else {
            $pesan = mysqli_stmt_errno($stmt) === 1062
                ? 'Kode barang sudah digunakan.'
                : 'Gagal menyimpan data barang.';
        }

        mysqli_stmt_close($stmt);
    }
}
?><!DOCTYPE html><html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang - Diskominfo Probolinggo</title>
</head>
<body><h1>Tambah Barang Inventaris</h1><?php if (isset($pesan)) { ?><p><?= htmlspecialchars($pesan); ?></p>

<?php } ?><form method="POST">
    <label>Kode Barang:</label><br>
    <input type="text" name="kode_barang" required><br><br><label>Nama Barang:</label><br>
<input type="text" name="nama_barang" required><br><br>

<label>Merek:</label><br>
<input type="text" name="merek"><br><br>

<label>Jumlah:</label><br>
<input type="number" name="jumlah" min="0" required><br><br>

<label>Kondisi:</label><br>
<select name="kondisi" required>
    <option value="Baik">Baik</option>
    <option value="Rusak Ringan">Rusak Ringan</option>
    <option value="Rusak Berat">Rusak Berat</option>
</select><br><br>

<label>Lokasi:</label><br>
<input type="text" name="lokasi"><br><br>

<button type="submit">Simpan Barang</button>
<a href="index.php">Kembali</a>

</form></body>
</html>