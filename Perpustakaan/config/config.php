<?php
$host = "127.0.0.1";
$username = "root";
$password = "lunaire";
$database_name = "perpustakaan";
$connection = mysqli_connect($host, $username, $password, $database_name);

if (!$connection) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// === FUNCTION KHUSUS ADMIN START ===

function queryReadData($query, $params = []) {
    global $connection;

    $stmt = mysqli_prepare($connection, $query);
    if (!$stmt) {
        die("Prepare failed: " . mysqli_error($connection));
    }

    if (!empty($params)) {
        $types = '';
        $values = [];
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_double($param)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
            $values[] = $param;
        }

        mysqli_stmt_bind_param($stmt, $types, ...$values);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $items = [];
    while ($item = mysqli_fetch_assoc($result)) {
        $items[] = $item;
    }

    mysqli_stmt_close($stmt);
    return $items;
}

function tambahBuku($dataBuku) {
    global $connection;

    $cover = upload();
    if (!$cover) {
        return 0;
    }

    $query = "INSERT INTO buku (id_buku, kategori, judul, pengarang, penerbit, tahun_terbit, jumlah_halaman, buku_deskripsi, cover) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $query);

    mysqli_stmt_bind_param($stmt, "sssssiiss",
        $dataBuku["id_buku"],
        $dataBuku["kategori"],
        $dataBuku["judul"],
        $dataBuku["pengarang"],
        $dataBuku["penerbit"],
        $dataBuku["tahun_terbit"],
        $dataBuku["jumlah_halaman"],
        $dataBuku["buku_deskripsi"],
        $cover
    );

    mysqli_stmt_execute($stmt);
    return mysqli_stmt_affected_rows($stmt);
}

function upload() {
    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif'
    ];

    if (!isset($_FILES['cover']) || $_FILES['cover']['error'] === 4) {
        echo "<script>alert('Silahkan upload cover buku terlebih dahulu!')</script>";
        return false;
    }

    $fileType = $_FILES['cover']['type'];
    if (!array_key_exists($fileType, $allowedTypes)) {
        echo "<script>alert('Format file tidak didukung! Hanya JPEG, PNG, dan GIF yang diperbolehkan.')</script>";
        return false;
    }

    if ($_FILES['cover']['size'] > 2000000) {
        echo "<script>alert('Ukuran file terlalu besar! Maksimal 2MB.')</script>";
        return false;
    }

    $extension = $allowedTypes[$fileType];
    $newFilename = uniqid() . '.' . $extension;
    $destination = '../../imgDB/' . $newFilename;

    if (move_uploaded_file($_FILES['cover']['tmp_name'], $destination)) {
        return $newFilename;
    }

    echo "<script>alert('Gagal mengupload file.')</script>";
    return false;
}

function search($keyword) {
    $keyword = "%$keyword%";
    return queryReadData(
        "SELECT * FROM buku WHERE judul LIKE ? OR kategori LIKE ?",
        [$keyword, $keyword]
    );
}

function searchMember($keyword) {
    $keyword = "%$keyword%";
    return queryReadData(
        "SELECT * FROM member WHERE 
        npm LIKE ? OR 
        kode_member LIKE ? OR
        nama LIKE ? OR 
        jurusan LIKE ?",
        [$keyword, $keyword, $keyword, $keyword]
    );
}

function delete($bukuId) {
    global $connection;

    $stmt = mysqli_prepare($connection, "DELETE FROM buku WHERE id_buku = ?");
    mysqli_stmt_bind_param($stmt, "s", $bukuId);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_affected_rows($stmt);
}

function updateBuku($dataBuku) {
    global $connection;

    $cover = ($_FILES["cover"]["error"] === 4) 
        ? $dataBuku["coverLama"] 
        : upload();

    if ($cover === false) {
        return 0;
    }

    $stmt = mysqli_prepare($connection,
        "UPDATE buku SET 
        cover = ?,
        kategori = ?,
        judul = ?,
        pengarang = ?,
        penerbit = ?,
        tahun_terbit = ?,
        jumlah_halaman = ?,
        buku_deskripsi = ? 
        WHERE id_buku = ?"
    );

    mysqli_stmt_bind_param($stmt, "ssssssiss",
        $cover,
        $dataBuku["kategori"],
        $dataBuku["judul"],
        $dataBuku["pengarang"],
        $dataBuku["penerbit"],
        $dataBuku["tahun_terbit"],
        $dataBuku["jumlah_halaman"],
        $dataBuku["buku_deskripsi"],
        $dataBuku["id_buku"]
    );

    mysqli_stmt_execute($stmt);
    return mysqli_stmt_affected_rows($stmt);
}

function deleteMember($npm) {
    global $connection;

    $stmt = mysqli_prepare($connection, "DELETE FROM member WHERE npm = ?");
    mysqli_stmt_bind_param($stmt, "s", $npm);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_affected_rows($stmt);
}

function deleteDataPengembalian($idPengembalian) {
    global $connection;

    $stmt = mysqli_prepare($connection, "DELETE FROM pengembalian WHERE id_pengembalian = ?");
    mysqli_stmt_bind_param($stmt, "i", $idPengembalian);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_affected_rows($stmt);
}

// === FUNCTION KHUSUS MEMBER START ===

function pinjamBuku($dataBuku) {
    global $connection;

    $stmt = mysqli_prepare($connection, 
        "SELECT denda FROM pengembalian WHERE npm = ? AND denda > 0");
    mysqli_stmt_bind_param($stmt, "s", $dataBuku["npm"]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Anda belum melunasi denda, silahkan lakukan pembayaran terlebih dahulu!')</script>";
        return 0;
    }

    $stmt = mysqli_prepare($connection, 
        "SELECT npm FROM peminjaman WHERE npm = ?");
    mysqli_stmt_bind_param($stmt, "s", $dataBuku["npm"]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Anda sudah meminjam buku, Harap kembalikan dahulu buku yang anda pinjam!')</script>";
        return 0;
    }

    if (empty($dataBuku["id_admin"])) {
        echo "<script>alert('ID Admin harus diisi untuk proses peminjaman.')</script>";
        return 0;
    }

    $stmt = mysqli_prepare($connection, 
        "INSERT INTO peminjaman (id_buku, npm, id_admin, tgl_peminjaman, tgl_pengembalian) 
         VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssiss", 
        $dataBuku["id_buku"],
        $dataBuku["npm"],
        $dataBuku["id_admin"],
        $dataBuku["tgl_peminjaman"],
        $dataBuku["tgl_pengembalian"]
    );

    mysqli_stmt_execute($stmt);
    return mysqli_stmt_affected_rows($stmt);
}

function pengembalian($dataBuku) {
    global $connection;

    mysqli_begin_transaction($connection);

    try {
        $tgl_pengembalian = strtotime($dataBuku["tgl_pengembalian"]);
        $tgl_buku_kembali = strtotime($dataBuku["buku_kembali"]);
        $keterlambatan = max(0, ceil(($tgl_buku_kembali - $tgl_pengembalian) / (60 * 60 * 24)));
        $denda = $keterlambatan * 5000;

        $stmt1 = mysqli_prepare($connection, 
            "INSERT INTO pengembalian (id_peminjaman, id_buku, npm, id_admin, tgl_pengembalian, keterlambatan, denda) 
             VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt1) {
            throw new Exception("Prepare statement 1 gagal: " . mysqli_error($connection));
        }
        mysqli_stmt_bind_param($stmt1, "issisii", 
            $dataBuku["id_peminjaman"],
            $dataBuku["id_buku"],
            $dataBuku["npm"],
            $dataBuku["id_admin"],
            $dataBuku["buku_kembali"],
            $keterlambatan,
            $denda
        );
        mysqli_stmt_execute($stmt1);

        if (mysqli_stmt_affected_rows($stmt1) <= 0) {
            throw new Exception("Insert ke tabel pengembalian gagal.");
        }

        $stmt2 = mysqli_prepare($connection, 
            "DELETE FROM peminjaman WHERE id_peminjaman = ?");
        if (!$stmt2) {
            throw new Exception("Prepare statement 2 gagal: " . mysqli_error($connection));
        }
        mysqli_stmt_bind_param($stmt2, "i", $dataBuku["id_peminjaman"]);
        mysqli_stmt_execute($stmt2);

        if (mysqli_stmt_affected_rows($stmt2) <= 0) {
            throw new Exception("Delete dari tabel peminjaman gagal.");
        }

        mysqli_commit($connection);

        if ($keterlambatan > 0) {
            echo "<script>alert('Anda terlambat mengembalikan buku. Denda: Rp " . number_format($denda, 0, ',', '.') . "');</script>";
        } else {
            echo "<script>alert('Buku berhasil dikembalikan tanpa denda.');</script>";
        }

        return 1;
    } catch (Exception $e) {
        mysqli_rollback($connection);
        error_log("Error saat pengembalian buku: " . $e->getMessage());
        echo "<script>alert('Terjadi kesalahan saat pengembalian. Silakan coba lagi.');</script>";
        return 0;
    }
}

function bayarDenda($data) {
    global $connection;

    $stmt = mysqli_prepare($connection, 
        "UPDATE pengembalian SET denda = 0 WHERE id_pengembalian = ?");
    mysqli_stmt_bind_param($stmt, "i", $data["id_pengembalian"]);
    mysqli_stmt_execute($stmt);

    return mysqli_stmt_affected_rows($stmt);
}
?>
