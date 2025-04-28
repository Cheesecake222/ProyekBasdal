<?php 
session_start();

// Cek apakah session sign-in ada
if (!isset($_SESSION["signIn"])) {
    header("Location: ../../sign/member/sign_in.php");
    exit;
}

// Cek apakah npm ada di session
if (!isset($_SESSION["member"]["npm"])) {
    echo "NPM tidak ditemukan di session.";
    exit;
}

require "../../config/config.php"; 
$npmSiswa = $_SESSION["member"]["npm"]; // Ganti dari nisn ke npm

// Query untuk mengambil data denda
$query = "SELECT pengembalian.id_pengembalian, pengembalian.id_peminjaman, pengembalian.id_buku, buku.judul, pengembalian.npm, member.nama, admin.nama_admin, pengembalian.buku_kembali, pengembalian.keterlambatan, pengembalian.denda
FROM pengembalian
INNER JOIN buku ON pengembalian.id_buku = buku.id_buku
INNER JOIN member ON pengembalian.npm = member.npm
INNER JOIN admin ON pengembalian.id_admin = admin.id
WHERE pengembalian.npm = ? AND pengembalian.denda > 0";

$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, 'i', $npmSiswa); // Binding nilai npm sebagai integer
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$dataDenda = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
    <title>Transaksi Denda Buku || Member</title>
</head>
<body>
    <nav class="navbar fixed-top bg-body-tertiary shadow-sm">
        <div class="container-fluid p-3">
            <a class="navbar-brand" href="#">
                <img src="../../assets/logoNav.png" alt="logo" width="120px">
            </a>
            <a class="btn btn-tertiary" href="../dashboardMember.php">Dashboard</a>
        </div>
    </nav>
    
    <div class="p-4 mt-5">
        <div class="mt-5 alert alert-primary" role="alert">
            Riwayat transaksi Denda Anda - <span class="fw-bold text-capitalize"><?= htmlentities($_SESSION["member"]["nama"]); ?></span>
        </div>

        <div class="table-responsive mt-3">
            <table class="table table-striped table-hover">
                <thead class="text-center">
                    <tr>
                        <th class="bg-primary text-light">ID Buku</th>
                        <th class="bg-primary text-light">Judul Buku</th>
                        <th class="bg-primary text-light">NPM</th>
                        <th class="bg-primary text-light">Nama Siswa</th>
                        <th class="bg-primary text-light">Nama Admin</th>
                        <th class="bg-primary text-light">Hari Pengembalian</th>
                        <th class="bg-primary text-light">Keterlambatan</th>
                        <th class="bg-primary text-light">Denda</th>
                        <th class="bg-primary text-light">Action</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <?php if (count($dataDenda) > 0): ?>
                        <?php foreach ($dataDenda as $item) : ?>
                            <tr>
                                <td><?= $item["id_buku"]; ?></td>
                                <td><?= $item["judul"]; ?></td>
                                <td><?= $item["npm"]; ?></td>
                                <td><?= $item["nama"]; ?></td>
                                <td><?= $item["nama_admin"]; ?></td>
                                <td><?= $item["buku_kembali"]; ?></td>
                                <td><?= $item["keterlambatan"]; ?></td>
                                <td><?= $item["denda"]; ?></td>
                                <td>
                                    <a class="btn btn-success" href="formBayarDenda.php?id=<?= $item["id_pengembalian"]; ?>">Bayar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9">Tidak ada transaksi denda yang perlu dibayar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="fixed-bottom shadow-lg bg-subtle p-3">
        <div class="container-fluid d-flex justify-content-between">
            <p class="mt-2">Created by <span class="text-primary">Team1 B2</span> © 2025</p>
            <p class="mt-2">versi 1.0</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
