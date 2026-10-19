<?php
session_start();
include 'koneksi.php';

if (empty($_SESSION['token_hapus'])) {
    $_SESSION['token_hapus'] = bin2hex(random_bytes(32));
}

$query = mysqli_query($conn, "SELECT * FROM barang ORDER BY id DESC");
?><!DOCTYPE html><html lang="id">
<head>
    <style>
    body {
        font-family: Arial, sans-serif;
        background: #f1f5f9;
        margin: 0;
        color: #1e293b;
    }

    .header {
        background: #123b66;
        color: white;
        padding: 25px;
        text-align: center;
    }

    .container {
        margin: 30px auto;
        max-width: 1100px;
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.08);
    }

    h1 {
        margin: 0 0 8px;
    }

    .tombol {
        display: inline-block;
        padding: 10px 15px;
        background: #167d52;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        margin-bottom: 15px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    th {
        background: #e2e8f0;
    }

    th, td {
        padding: 12px;
        border: 1px solid #cbd5e1;
        text-align: left;
    }

    tr:hover {
        background: #f8fafc;
    }

    button {
        padding: 6px 10px;
        cursor: pointer;
    }

    @media (max-width: 700px) {
        .container {
            margin: 10px;
            padding: 12px;
            overflow-x: auto;
        }

        table {
            min-width: 750px;
        }
    }
    /* Tombol utama */
.tombol {
    display: inline-block;
    background-color: #175cd3;
    color: white;
    padding: 10px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    margin: 10px 0;
}

.tombol:hover {
    background-color: #1849a9;
}

/* Tabel inventaris */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 16px;
    background-color: white;
}

th {
    background-color: #175cd3;
    color: white;
    text-align: left;
    padding: 12px;
}

td {
    padding: 12px;
    border-bottom: 1px solid #eaecf0;
}

tr:hover {
    background-color: #f2f4f7;
}
</style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris Barang Diskominfo Probolinggo</title>
</head>
<body><h1>INVENTARIS BARANG</h1>
<h2>DISKOMINFO PROBOLINGGO</h2>
<h2> Sistem Informasi Pengelolaan Inventaris Barang</h2>

<hr>
<div class="container"> 
<h3>Data Barang</h3>

<a href="tambah.php"class="tombol">+ Tambah Barang</a>

<br><br>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>No.</th>
        <th>Kode Barang</th>
        <th>Nama Barang</th>
        <th>Merek</th>
        <th>Jumlah</th>
        <th>Kondisi</th>
        <th>Lokasi</th>
        <th>Aksi</th>
    </tr>

    <?php
    $no = 1;

    while ($data = mysqli_fetch_assoc($query)) {
    ?>

    <tr>
        <td><?= $no++; ?></td>
        <td><?= htmlspecialchars($data['kode_barang']); ?></td>
        <td><?= htmlspecialchars($data['nama_barang']); ?></td>
        <td><?= htmlspecialchars($data['merek'] ?? ''); ?></td>
        <td><?= (int)$data['jumlah']; ?></td>
        <td><?= htmlspecialchars($data['kondisi']); ?></td>
        <td><?= htmlspecialchars($data['lokasi'] ?? ''); ?></td>
        <td>
    <a href="edit.php?id=<?= (int)$data['id']; ?>">Edit</a><form action="hapus.php" method="POST"
      onsubmit="return confirm('Yakin ingin menghapus barang ini?');"
      style="display:inline;">
    <input type="hidden" name="id" value="<?= (int)$data['id']; ?>">
    <input type="hidden" name="token"
           value="<?= htmlspecialchars($_SESSION['token_hapus'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
    <button type="submit">Hapus</button>
</form>

</td>
    </tr>

    <?php } ?>

</table>
</div>
</body>
</html>