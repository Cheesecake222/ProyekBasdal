<?php 
require "../../config/config.php"; 
$dataDenda = queryReadData("SELECT 
    pengembalian.id_pengembalian, 
    pengembalian.id_buku, 
    buku.judul, 
    pengembalian.npm, 
    member.nama, 
    member.jurusan, 
    admin.nama_admin, 
    pengembalian.buku_kembali, 
    pengembalian.keterlambatan, 
    pengembalian.denda
FROM pengembalian
INNER JOIN buku ON pengembalian.id_buku = buku.id_buku
INNER JOIN member ON pengembalian.npm = member.npm
INNER JOIN admin ON pengembalian.id_admin = admin.id
WHERE pengembalian.denda > 0");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
    <title>Kelola denda buku || admin</title>
  </head>
  <body>
    <nav class="navbar fixed-top bg-body-tertiary shadow-sm">
      <div class="container-fluid p-3">
        <a class="navbar-brand" href="#">
          <img src="../../assets/logoNav.png" alt="logo" width="120px">
        </a>
        <a class="btn btn-tertiary" href="../dashboardAdmin.php">Dashboard</a>
      </div>
    </nav>
    
    <div class="p-4 mt-5">
      <div class="mt-5">
        <caption>List of Denda</caption>
        <div class="table-responsive mt-3">
          <table class="table table-striped table-hover">
            <thead class="text-center">
              <tr>
                <th class="bg-primary text-light">ID Buku</th>
                <th class="bg-primary text-light">Judul Buku</th>
                <th class="bg-primary text-light">NPM</th>
                <th class="bg-primary text-light">Nama Siswa</th>
                <th class="bg-primary text-light">Jurusan</th>
                <th class="bg-primary text-light">Nama Admin</th>
                <th class="bg-primary text-light">Tanggal Pengembalian</th>
                <th class="bg-primary text-light">Keterlambatan (hari)</th>
                <th class="bg-primary text-light">Denda</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($dataDenda as $item) : ?>
                <tr class="text-center">
                  <td><?= htmlspecialchars($item["id_buku"]); ?></td>
                  <td><?= htmlspecialchars($item["judul"]); ?></td>
                  <td><?= htmlspecialchars($item["npm"]); ?></td>
                  <td><?= htmlspecialchars($item["nama"]); ?></td>
                  <td><?= htmlspecialchars($item["jurusan"]); ?></td>
                  <td><?= htmlspecialchars($item["nama_admin"]); ?></td>
                  <td><?= htmlspecialchars($item["buku_kembali"]); ?></td>
                  <td><?= htmlspecialchars($item["keterlambatan"]); ?></td>
                  <td>Rp <?= number_format($item["denda"], 0, ',', '.'); ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  
    <footer class="fixed-bottom shadow-lg bg-subtle p-3">
      <div class="container-fluid d-flex justify-content-between">
        <p class="mt-2">Created by <span class="text-primary">Team 1 B2</span> © 2025</p>
        <p class="mt-2">versi 1.0</p>
      </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>