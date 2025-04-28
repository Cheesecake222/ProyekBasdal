<?php
session_start();
require "../../config/config.php";

// Check if user is logged in
if(!isset($_SESSION["signIn"])) {
  header("Location: ../../sign/admin/sign_in.php");
  exit;
}

// Check if npm parameter exists and is valid
if(isset($_GET["npm"]) && !empty($_GET["npm"])) {
    $npm = $_GET["npm"];
    
    // First verify the member exists
    $memberExists = queryReadData("SELECT npm FROM member WHERE npm = ?", [$npm]);
    
    if(empty($memberExists)) {
        echo "<script>
        alert('Member tidak ditemukan!');
        document.location.href = 'member.php';
        </script>";
        exit;
    }

    // Attempt to delete the member
    if(deleteMember($npm) > 0) {
        echo "<script>
        alert('Member berhasil dihapus!');
        document.location.href = 'member.php';
        </script>";
    } else {
        echo "<script>
        alert('Member gagal dihapus! Silakan coba lagi.');
        document.location.href = 'member.php';
        </script>";
    }
} else {
    // Invalid or missing parameter
    echo "<script>
    alert('Parameter tidak valid!');
    document.location.href = 'member.php';
    </script>";
}
?>