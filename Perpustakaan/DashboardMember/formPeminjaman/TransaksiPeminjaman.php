<?php 
session_start();

if (!isset($_SESSION["signIn"])) {
    header("Location: ../../sign/member/sign_in.php");
    exit;
}

require "../../config/config.php";

// Cek dan ambil NPM dari session
if (!isset($_SESSION["member"]["npm"])) {
    echo "<script>alert('Session NPM tidak ditemukan. Silakan login ulang.'); window.location.href='../../sign/member/sign_in.php';</script>";
    exit;
}

// Escape untuk keamanan query
$akunMember = mysqli_real_escape_string($connection, $_SESSION["member"]["npm"]);

// Jalankan query
$query = "SELECT 
    peminjaman.id_peminjaman, 
    peminjaman.id_buku, 
    buku.judul, 
    peminjaman.npm, 
    member.nama, 
    admin.nama_admin, 
    peminjaman.tgl_peminjaman, 
    peminjaman.tgl_pengembalian
  FROM peminjaman
  INNER JOIN buku ON peminjaman.id_buku = buku.id_buku
  INNER JOIN member ON peminjaman.npm = member.npm
  INNER JOIN admin ON peminjaman.id_admin = admin.id
  WHERE peminjaman.npm = '$akunMember'";

$result = mysqli_query($connection, $query);

if (!$result) {
    die("Query Error: " . mysqli_error($connection));
}

$dataPinjam = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
    <title>Transaksi Peminjaman Buku || Member</title>
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
        Riwayat transaksi Peminjaman Buku Anda - 
        <span class="fw-bold text-capitalize">
          <?= htmlentities($_SESSION["member"]["nama"]); ?>
        </span>
      </div>

      <div class="table-responsive mt-3">
        <table class="table table-striped table-hover">
          <thead class="text-center">
            <tr>
              <th class="bg-primary text-light">Id Peminjaman</th>
              <th class="bg-primary text-light">Id Buku</th>
              <th class="bg-primary text-light">Judul Buku</th>
              <th class="bg-primary text-light">NPM</th>
              <th class="bg-primary text-light">Nama</th>
              <th class="bg-primary text-light">Nama Admin</th>
              <th class="bg-primary text-light">Tanggal Peminjaman</th>
              <th class="bg-primary text-light">Tanggal Pengembalian</th>
              <th class="bg-primary text-light">Aksi</th>
            </tr>
          </thead>
          <tbody class="text-center">
            <?php if (!empty($dataPinjam)): ?>
              <?php foreach ($dataPinjam as $item): ?>
                <tr>
                  <td><?= htmlentities($item["id_peminjaman"]); ?></td>
                  <td><?= htmlentities($item["id_buku"]); ?></td>
                  <td><?= htmlentities($item["judul"]); ?></td>
                  <td><?= htmlentities($item["npm"]); ?></td>
                  <td><?= htmlentities($item["nama"]); ?></td>
                  <td><?= htmlentities($item["nama_admin"]); ?></td>
                  <td><?= htmlentities($item["tgl_peminjaman"]); ?></td>
                  <td><?= htmlentities($item["tgl_pengembalian"]); ?></td>
                  <td>
                    <a class="btn btn-success" href="pengembalianBuku.php?id=<?= $item["id_peminjaman"]; ?>">Kembalikan</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="9" class="text-danger">Belum ada transaksi peminjaman.</td>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
