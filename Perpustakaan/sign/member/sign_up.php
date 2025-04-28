<?php 
require "../../loginSystem/connect.php";
if (isset($_POST["signUp"])) {
  if (signUp($_POST) > 0) {
    echo "<script>alert('Sign Up berhasil!');window.location='sign_in.php';</script>";
  } else {
    echo "<script>alert('Sign Up gagal!');</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
  <title>Member Sign Up</title>
</head>
<body>
  <div class="login-card">
    <div class="card-left">
      <h2>Join With Us!</h2>
      <p>Daftar sebagai member untuk mendapatkan akses penuh ke sistem perpustakaan.</p>
      <a href="sign_in.php" class="btn btn-outline-light mt-3">Sign In</a>
    </div>
    <div class="card-right">
      <div class="text-center">
        <img src="../../assets/memberLogo.png" alt="memberLogo" class="logo-img rounded-circle">
        <h4 class="fw-bold mb-3">Member Sign Up</h4>
      </div>
      <form action="" method="post" class="needs-validation" novalidate>
        <div class="mb-3">
          <label for="npmInput" class="form-label">NPM</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
            <input type="text" class="form-control" name="npm" id="npmInput" required pattern="[a-zA-Z0-9]+" title="NPM hanya boleh terdiri dari huruf dan angka.">
            <div class="invalid-feedback">NPM wajib diisi dan hanya boleh huruf dan angka!</div>
          </div>
        </div>
        <div class="mb-3">
          <label for="kodeMember" class="form-label">Kode Member</label>
          <input type="text" class="form-control" name="kode_member" id="kodeMember" required>
          <div class="invalid-feedback">Kode member wajib diisi!</div>
        </div>
        <div class="mb-3">
          <label for="nama" class="form-label">Nama Lengkap</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
            <input type="text" class="form-control" name="nama" id="nama" required>
            <div class="invalid-feedback">Nama wajib diisi!</div>
          </div>
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
            <input type="password" class="form-control" name="password" id="password" required>
            <div class="invalid-feedback">Password wajib diisi!</div>
          </div>
        </div>
        <div class="mb-3">
          <label for="confirmPw" class="form-label">Confirm Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
            <input type="password" class="form-control" name="confirmPw" id="confirmPw" required>
            <div class="invalid-feedback">Konfirmasi password wajib diisi!</div>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col">
            <label for="jenisKelamin" class="form-label">Gender</label>
            <select class="form-select" name="jenis_kelamin" id="jenisKelamin" required>
              <option value="" selected>Choose</option>
              <option value="Laki laki">Laki-laki</option>
              <option value="Perempuan">Perempuan</option>
            </select>
            <div class="invalid-feedback">Pilih gender!</div>
          </div>
          <div class="col">
            <label for="semester" class="form-label">Semester</label>
            <select class="form-select" name="semester" id="semester" required>
              <option value="" selected>Pilih Semester</option>
              <option value="1">I</option>
              <option value="2">II</option>
              <option value="3">III</option>
              <option value="4">IV</option>
              <option value="5">V</option>
              <option value="6">VI</option>
              <option value="7">VII</option>
              <option value="8">VIII</option>
            </select>
            <div class="invalid-feedback">Pilih semester!</div>
          </div>
        </div>
        <div class="mb-3">
          <label for="jurusan" class="form-label">Jurusan</label>
          <select class="form-select" name="jurusan" id="jurusan" required>
            <option value="" selected>Choose</option>
            <option value="Informatika">Informatika</option>
            <option value="Teknik Mesin">Teknik Mesin</option>
            <option value="Teknik Sipil">Teknik Sipil</option>
            <option value="Teknik Elektro">Teknik Elektro</option>
            <option value="Sistem Informasi">Sistem Informasi</option>
            <option value="Arsitektur">Arsitektur</option>
          </select>
          <div class="invalid-feedback">Pilih jurusan!</div>
        </div>
        <div class="mb-3">
          <label for="noTlp" class="form-label">No Telepon</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-phone"></i></span>
            <input type="number" class="form-control" name="no_tlp" id="noTlp" required>
            <div class="invalid-feedback">No telepon wajib diisi!</div>
          </div>
        </div>
        <div class="mb-3">
          <label for="tglPendaftaran" class="form-label">Tanggal Pendaftaran</label>
          <div class="input-group">
            <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
            <input type="date" class="form-control" name="tgl_pendaftaran" id="tglPendaftaran" required>
            <div class="invalid-feedback">Tanggal pendaftaran wajib diisi!</div>
          </div>
        </div>
        <div class="d-grid gap-2">
          <button class="btn btn-custom text-white" type="submit" name="signUp">Sign Up</button>
          <button type="reset" class="btn btn-outline-secondary">Reset</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    (() => {
      'use strict';
      const forms = document.querySelectorAll('.needs-validation');
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    })();
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
