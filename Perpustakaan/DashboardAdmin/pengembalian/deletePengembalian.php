<?php 
require "../../config/config.php";

// Validasi ID
if(isset($_GET["id"]) && !empty($_GET["id"])) {
    $idPengembalian = (int)$_GET["id"]; // Cast ke integer untuk keamanan
    
    // Pengecekan apakah data ada sebelum dihapus
    $checkData = mysqli_query($connection, "SELECT id_pengembalian FROM pengembalian WHERE id_pengembalian = $idPengembalian");
    
    if(mysqli_num_rows($checkData) > 0) {
        // Jika data ditemukan, lakukan penghapusan
        if(deleteDataPengembalian($idPengembalian) > 0) {
            echo "
            <script>
            alert('Data berhasil dihapus');
            document.location.href = 'pengembalianBuku.php';
            </script>";
        } else {
            echo "
            <script>
            alert('Data gagal dihapus. Silakan coba lagi.');
            document.location.href = 'pengembalianBuku.php';
            </script>";
        }
    } else {
        // Jika data tidak ditemukan
        echo "
        <script>
        alert('Data tidak ditemukan');
        document.location.href = 'pengembalianBuku.php';
        </script>";
    }
} else {
    // Jika tidak ada parameter ID
    echo "
    <script>
    alert('Akses tidak valid');
    document.location.href = 'pengembalianBuku.php';
    </script>";
}
?>