<?php
include 'koneksi.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    exit('ID barang tidak valid.');
}

$stmt = mysqli_prepare($conn, "SELECT * FROM barang WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$data) {
    exit('Barang tidak ditemukan.');
}

$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = trim($_POST['kode_barang']);
    $nama = trim($_POST['nama_barang']);
    $merek = trim($_POST['merek']);
    $jumlah = filter_input(INPUT_POST, 'jumlah', FILTER_VALIDATE_INT);
    $kondisi = $_POST['kondisi'];
    $lokasi = trim($_POST['lokasi']);

    $kondisi_valid = ['Baik', 'Rusak Ringan', 'Rusak Berat'];

    if (
        $kode === '' ||
        $nama === '' ||
        $jumlah === false ||
        $jumlah === null ||
        $jumlah < 0 ||
        !in_array($kondisi, $kondisi_valid, true)
    ) {
        $pesan = 'Periksa kembali data yang dimasukkan.';
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "UPDATE barang
             SET kode_barang = ?, nama_barang = ?, merek = ?,
                 jumlah = ?, kondisi = ?, lokasi = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssissi",
            $kode, $nama, $merek, $jumlah, $kondisi, $lokasi, $id
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header('Location: index.php');
            exit;
        } else {
            $pesan = mysqli_stmt_errno($stmt) === 1062
                ? 'Kode barang sudah digunakan.'
                : 'Gagal memperbarui data.';
        }

        mysqli_stmt_close($stmt);
    }

    $data['kode_barang'] = $kode;
    $data['nama_barang'] = $nama;
    $data['merek'] = $merek;
    $data['jumlah'] = $jumlah;
    $data['kondisi'] = $kondisi;
    $data['lokasi'] = $lokasi;
}
?><!DOCTYPE html><html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Barang Inventaris</title>
</head>
<body><h1>Edit Barang Inventaris</h1><?php if ($pesan !== ''): ?><p><?= htmlspecialchars($pesan); ?></p>

<?php endif; ?><form method="POST">
    <label>Kode Barang:</label><br>
    <input type="text" name="kode_barang"
           value="<?= htmlspecialchars($data['kode_barang']); ?>" required><br><br><label>Nama Barang:</label><br>
<input type="text" name="nama_barang"
       value="<?= htmlspecialchars($data['nama_barang']); ?>" required><br><br>

<label>Merek:</label><br>
<input type="text" name="merek"
       value="<?= htmlspecialchars($data['merek'] ?? ''); ?>"><br><br>

<label>Jumlah:</label><br>
<input type="number" name="jumlah" min="0"
       value="<?= htmlspecialchars((string)$data['jumlah']); ?>" required><br><br>

<label>Kondisi:</label><br>
<select name="kondisi" required>
    <?php foreach (['Baik', 'Rusak Ringan', 'Rusak Berat'] as $k): ?>
        <option value="<?= $k; ?>"
            <?= $data['kondisi'] === $k ? 'selected' : ''; ?>>
            <?= $k; ?>
        </option>
    <?php endforeach; ?>
</select><br><br>

<label>Lokasi:</label><br>
<input type="text" name="lokasi"
       value="<?= htmlspecialchars($data['lokasi'] ?? ''); ?>"><br><br>

<button type="submit">Simpan Perubahan</button>
<a href="index.php">Kembali</a>

</form></body>
</html>