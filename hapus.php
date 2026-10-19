<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$token = $_POST['token'] ?? '';

if (
    !$id ||
    empty($_SESSION['token_hapus']) ||
    !hash_equals($_SESSION['token_hapus'], $token)
) {
    exit('Permintaan tidak valid. Silakan kembali ke halaman utama.');
}

$stmt = mysqli_prepare($conn, "DELETE FROM barang WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header('Location: index.php');
    exit;
}

mysqli_stmt_close($stmt);
exit('Data barang gagal dihapus.');
?>