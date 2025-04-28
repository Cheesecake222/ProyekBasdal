<?php 
session_start();

if (!isset($_SESSION["signIn"])) {
    header("Location: ../../sign/member/sign_in.php");
    exit;
}

require "../../config/config.php";

// Tangkap id buku dari URL
$idBuku = $_GET["id"];
$queryBuku = queryReadData("SELECT * FROM buku WHERE id_buku = '$idBuku'");

// Menampilkan data siswa yang sedang login
$npmSiswa = $_SESSION["member"]["npm"];
$dataSiswa = queryReadData("SELECT * FROM member WHERE npm = '$npmSiswa'");

// Menampilkan semua data admin (tanpa filter status karena kolom tidak ada)
$dataAdmin = queryReadData("SELECT * FROM admin");

// Proses peminjaman
if (isset($_POST["pinjam"])) {
    // Validasi data
    if (empty($_POST["id_admin"])) {
        echo "<script>
                alert('Silakan pilih admin yang bertugas!');
                document.location.href = 'pinjamBuku.php?id=$idBuku';
              </script>";
        exit;
    }

    // Pastikan data yang diperlukan tersedia
    $dataPinjam = [
        'id_buku' => $_POST['id_buku'],
        'npm' => $_POST['npm'],
        'id_admin' => $_POST['id_admin'],
        'tgl_peminjaman' => $_POST['tgl_peminjaman'],
        'tgl_pengembalian' => $_POST['tgl_pengembalian']
    ];

    if (pinjamBuku($dataPinjam) > 0) {
        echo "<script>
                alert('Buku berhasil dipinjam');
                document.location.href = '../dashboardMember.php';
              </script>";
    } else {
        echo "<script>
                alert('Buku gagal dipinjam!');
                document.location.href = 'pinjamBuku.php?id=$idBuku';
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Pinjam Buku | Member</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            padding-top: 70px;
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #0d6efd;
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        .img-thumbnail {
            border: none;
            background-color: #f8f9fa;
        }
        .form-label {
            font-weight: 500;
        }
        .alert {
            border-radius: 10px;
        }
        footer {
            background-color: #e9ecef;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="../../assets/logoNav.png" alt="logo" width="120px">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="btn btn-outline-primary" href="../dashboardMember.php">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container p-4">
    <h2 class="mb-4"><i class="fas fa-book"></i> Form Peminjaman Buku</h2>

    <!-- Card Data Buku -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-book-open"></i> Data Buku</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <?php foreach ($queryBuku as $buku) : ?>
                <div class="col-md-4 text-center mb-3 mb-md-0">
                    <img src="../../imgDB/<?= htmlspecialchars($buku["cover"]); ?>" class="img-thumbnail" width="200" alt="Cover Buku">
                </div>
                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">ID Buku</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($buku["id_buku"]); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($buku["kategori"]); ?>" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Judul Buku</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($buku["judul"]); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Pengarang</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($buku["pengarang"]); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Penerbit</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($buku["penerbit"]); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tahun Terbit</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($buku["tahun_terbit"]); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jumlah Halaman</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($buku["jumlah_halaman"]); ?>" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi Buku</label>
                            <textarea class="form-control" rows="3" readonly><?= htmlspecialchars($buku["buku_deskripsi"]); ?></textarea>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Card Data Member -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-user"></i> Data Member</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <?php foreach ($dataSiswa as $siswa) : ?>
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <img src="../../assets/memberLogo.png" class="img-thumbnail" width="150" alt="Foto Member">
                </div>
                <div class="col-md-9">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">NPM</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($siswa["npm"]); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kode Member</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($siswa["kode_member"]); ?>" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($siswa["nama"]); ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jenis Kelamin</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($siswa["jenis_kelamin"]); ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Jurusan</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($siswa["jurusan"]); ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($siswa["no_tlp"]); ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Daftar</label>
                            <input type="text" class="form-control" value="<?= htmlspecialchars($siswa["tgl_pendaftaran"]); ?>" readonly>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Warning -->
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle"></i> Silakan periksa data di atas. Jika ada kesalahan, segera hubungi admin perpustakaan.
    </div>

    <!-- Form Peminjaman -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-edit"></i> Form Peminjaman</h5>
        </div>
        <div class="card-body">
            <form method="post" class="needs-validation" novalidate>
                <?php foreach ($queryBuku as $buku) : ?>
                <input type="hidden" name="id_buku" value="<?= htmlspecialchars($buku["id_buku"]); ?>">
                <?php endforeach; ?>

                <input type="hidden" name="npm" value="<?= htmlspecialchars($_SESSION["member"]["npm"]); ?>">

                <div class="mb-3">
                    <label for="id_admin" class="form-label">Admin Penanggung Jawab <span class="text-danger">*</span></label>
                    <select class="form-select" id="id_admin" name="id_admin" required>
                        <option value="" selected disabled>-- Pilih Admin --</option>
                        <?php foreach ($dataAdmin as $admin) : ?>
                            <option value="<?= htmlspecialchars($admin["id"]); ?>">
                                <?= htmlspecialchars($admin["nama_admin"]); ?> (ID: <?= htmlspecialchars($admin["id"]); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        Silakan pilih admin yang bertugas
                    </div>
                </div>

                <div class="mb-3">
                    <label for="tgl_peminjaman" class="form-label">Tanggal Pinjam <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="tgl_peminjaman" name="tgl_peminjaman" required onchange="setReturnDate()">
                    <div class="invalid-feedback">
                        Silakan isi tanggal pinjam
                    </div>
                </div>

                <div class="mb-3">
                    <label for="tgl_pengembalian" class="form-label">Tanggal Kembali</label>
                    <input type="date" class="form-control" id="tgl_pengembalian" name="tgl_pengembalian" readonly>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="../buku/daftarBuku.php" class="btn btn-danger">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" name="pinjam" class="btn btn-success">
                        <i class="fas fa-check"></i> Konfirmasi Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Catatan Peminjaman -->
    <div class="alert alert-warning">
        <h5><i class="fas fa-info-circle"></i> Ketentuan Peminjaman:</h5>
        <ul class="mb-0">
            <li>Batas waktu peminjaman adalah <strong>7 hari</strong></li>
            <li>Denda keterlambatan <strong>Rp 5.000 per hari</strong></li>
            <li>Buku yang hilang/rusak menjadi tanggung jawab peminjam</li>
            <li>Waktu pengembalian pukul <strong>16.00 WIB</strong></li>
        </ul>
    </div>
</div>

<!-- Footer -->
<footer class="footer mt-auto py-3 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <span class="text-muted">Perpustakaan Digital © 2025</span>
            </div>
            <div class="col-md-6 text-md-end">
                <span class="text-muted">Versi 1.0.0</span>
            </div>
        </div>
    </div>
</footer>

<!-- JavaScript -->
<script>
// Fungsi untuk set tanggal kembali otomatis
function setReturnDate() {
    const tglPinjam = document.getElementById('tgl_peminjaman').value;
    if (tglPinjam) {
        const tanggalPinjam = new Date(tglPinjam);
        tanggalPinjam.setDate(tanggalPinjam.getDate() + 7);
        
        const tahun = tanggalPinjam.getFullYear();
        const bulan = String(tanggalPinjam.getMonth() + 1).padStart(2, '0');
        const tanggal = String(tanggalPinjam.getDate()).padStart(2, '0');
        
        document.getElementById('tgl_pengembalian').value = `${tahun}-${bulan}-${tanggal}`;
    }
}

// Validasi form
(function () {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation')
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>