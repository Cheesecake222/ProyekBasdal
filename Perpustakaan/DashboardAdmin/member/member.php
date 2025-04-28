<?php
session_start();

if(!isset($_SESSION["signIn"])) {
  header("Location: ../../sign/admin/sign_in.php");
  exit;
}
require "../../config/config.php";

$member = queryReadData("SELECT 
    npm,
    kode_member, 
    nama, 
    jenis_kelamin, 
    semester, 
    jurusan, 
    no_tlp, 
    tgl_pendaftaran 
FROM member");

if(isset($_POST["search"])) {
  $member = searchMember($_POST["keyword"]);
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
    <title>Member Terdaftar</title>
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
      <!-- Search Engine -->
      <form action="" method="post" class="mt-5">
        <div class="input-group d-flex justify-content-end mb-3">
          <input class="border p-2 rounded rounded-end-0 bg-tertiary" type="text" name="keyword" id="keyword" placeholder="Cari data member...">
          <button class="border border-start-0 bg-light rounded rounded-start-0" type="submit" name="search">
            <i class="fa-solid fa-magnifying-glass"></i>
          </button>
        </div>
      </form>
      
      <caption>List of Member</caption>
      <div class="table-responsive mt-3">
        <table class="table table-striped table-hover">
          <thead class="text-center">
            <tr>
              <th class="bg-primary text-light">NPM</th>
              <th class="bg-primary text-light">Kode Member</th>
              <th class="bg-primary text-light">Nama</th>
              <th class="bg-primary text-light">Jenis Kelamin</th>
              <th class="bg-primary text-light">Semester</th>
              <th class="bg-primary text-light">Jurusan</th>
              <th class="bg-primary text-light">No Telepon</th>
              <th class="bg-primary text-light">Pendaftaran</th>
              <th class="bg-primary text-light">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($member as $item) : ?>
            <tr class="text-center">
              <td><?= htmlspecialchars($item["npm"] ?? '-'); ?></td>
              <td><?= htmlspecialchars($item["kode_member"] ?? '-'); ?></td>
              <td><?= htmlspecialchars($item["nama"] ?? '-'); ?></td>
              <td><?= htmlspecialchars($item["jenis_kelamin"] ?? '-'); ?></td>
              <td><?= htmlspecialchars($item["semester"] ?? '-'); ?></td>
              <td><?= htmlspecialchars($item["jurusan"] ?? '-'); ?></td>
              <td><?= htmlspecialchars($item["no_tlp"] ?? '-'); ?></td>
              <td><?= htmlspecialchars($item["tgl_pendaftaran"] ?? '-'); ?></td>
              <td>
                <div class="action">
                  <a href="deleteMember.php?npm=<?= $item['npm']; ?>" 
                     class="btn btn-danger" 
                     onclick="return confirm('Yakin ingin menghapus member ini?')">
                    <i class="fa-solid fa-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
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