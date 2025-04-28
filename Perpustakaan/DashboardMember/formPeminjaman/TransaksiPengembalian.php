<?php 
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION["signIn"])) {
    header("Location: ../../sign/member/sign_in.php");
    exit;
}

// Panggil koneksi database
require "../../config/config.php"; 

// Cek koneksi ke database
if (!$connection) {
    die("Koneksi ke database gagal.");
}

// Pastikan npm ada di session
if (!isset($_SESSION["member"]["npm"])) {
    echo "NPM tidak ditemukan. Silakan login ulang.";
    exit;
}

$akunMember = $_SESSION["member"]["npm"];

// Query untuk mengambil data pengembalian
$query = "SELECT pengembalian.id_pengembalian, pengembalian.id_buku, buku.judul, buku.kategori, pengembalian.npm, member.nama, admin.nama_admin, pengembalian.buku_kembali, pengembalian.keterlambatan, pengembalian.denda
FROM pengembalian
INNER JOIN buku ON pengembalian.id_buku = buku.id_buku
INNER JOIN member ON pengembalian.npm = member.npm
INNER JOIN admin ON pengembalian.id_admin = admin.id
WHERE pengembalian.npm = ?";

// Menggunakan prepare statement
$stmt = mysqli_prepare($connection, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $akunMember);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $dataPengembalian = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
} else {
    die("Query gagal disiapkan: " . mysqli_error($connection));
}

// Kalau ada search (NOTE: fungsi search() belum ada di kode ini)
if (isset($_POST["search"])) {
    $dataPengembalian = search($_POST["keyword"]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
    <title>Transaksi Pengembalian Buku || Member</title>
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
        Riwayat transaksi Pengembalian Buku Anda - <span class="fw-bold text-capitalize"><?php echo htmlentities($_SESSION["member"]["nama"]); ?></span>
      </div>

      <div class="table-responsive mt-3">
        <table class="table table-striped table-hover">
          <thead class="text-center">
            <tr>
              <th class="bg-primary text-light">Id Pengembalian</th>
              <th class="bg-primary text-light">Id Buku</th>
              <th class="bg-primary text-light">Judul Buku</th>
              <th class="bg-primary text-light">Kategori</th>
              <th class="bg-primary text-light">NPM</th>
              <th class="bg-primary text-light">Nama</th>
              <th class="bg-primary text-light">Nama Admin</th>
              <th class="bg-primary text-light">Tanggal Pengembalian</th>
              <th class="bg-primary text-light">Keterlambatan</th>
              <th class="bg-primary text-light">Denda</th>
            </tr>
          </thead>
          <tbody>
          <?php if (!empty($dataPengembalian)) : ?>
            <?php foreach ($dataPengembalian as $item) : ?>
              <tr class="text-center">
                <td><?= htmlentities($item["id_pengembalian"]); ?></td>
                <td><?= htmlentities($item["id_buku"]); ?></td>
                <td><?= htmlentities($item["judul"]); ?></td>
                <td><?= htmlentities($item["kategori"]); ?></td>
                <td><?= htmlentities($item["npm"]); ?></td>
                <td><?= htmlentities($item["nama"]); ?></td>
                <td><?= htmlentities($item["nama_admin"]); ?></td>
                <td><?= htmlentities($item["buku_kembali"]); ?></td>
                <td><?= htmlentities($item["keterlambatan"]); ?></td>
                <td><?= htmlentities($item["denda"]); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
              <tr class="text-center">
                <td colspan="10" class="text-danger">Belum ada data pengembalian.</td>
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
