<?php
// FILE LOGIN SYSTEM 
$host = "127.0.0.1";
$username = "root";
$password = "lunaire";
$database = "perpustakaan";
$connect = mysqli_connect($host, $username, $password, $database);

/* SIGN UP Member */
function signUp($data) {
  global $connect;

  $npm = htmlspecialchars($data["npm"]);
  $kodeMember = htmlspecialchars($data["kode_member"]);
  $nama = htmlspecialchars(strtolower($data["nama"]));
  $password = mysqli_real_escape_string($connect, $data["password"]);
  $confirmPw = mysqli_real_escape_string($connect, $data["confirmPw"]);
  $jk = htmlspecialchars($data["jenis_kelamin"]);
  $semester = htmlspecialchars($data["semester"]);
  $jurusan = htmlspecialchars($data["jurusan"]);
  $noTlp = htmlspecialchars($data["no_tlp"]);
  $tglDaftar = $data["tgl_pendaftaran"];

  // cek npm sudah ada / belum 
  $npmResult = mysqli_query($connect, "SELECT npm FROM member WHERE npm = '$npm'");
  if(mysqli_fetch_assoc($npmResult)) {
    echo "<script>
    alert('NPM sudah terdaftar, silahkan gunakan NPM lain!');
    </script>";
    return 0;
  }

  //cek kodeMember sudah ada / belum
  $kodeMemberResult = mysqli_query($connect, "SELECT kode_member FROM member WHERE kode_member = '$kodeMember'");
  if(mysqli_fetch_assoc($kodeMemberResult)){
    echo "<script>
    alert('Kode member telah terdaftar, silahkan gunakan kode member lain!');
    </script>";
    return 0;
  }

  // Pengecekan kesamaan confirm password dan password
  if($password !== $confirmPw) {
    echo "<script>
    alert('Password dan Confirm Password tidak sesuai');
    </script>";
    return 0;
  }

  // Enkripsi password
  $password = password_hash($password, PASSWORD_DEFAULT);

  // Simpan ke database
  $querySignUp = "INSERT INTO member (npm, kode_member, nama, password, jenis_kelamin, semester, jurusan, no_tlp, tgl_pendaftaran) 
                  VALUES ('$npm', '$kodeMember', '$nama', '$password', '$jk', '$semester', '$jurusan', '$noTlp', '$tglDaftar')";
  mysqli_query($connect, $querySignUp);
  return mysqli_affected_rows($connect);
}
?>
