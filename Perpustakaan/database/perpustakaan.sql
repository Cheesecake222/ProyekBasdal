-- phpMyAdmin SQL Dump
-- Database: perpustakaan

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS perpustakaan;
USE perpustakaan;

-- ===================
-- Table: admin
-- ===================
CREATE TABLE IF NOT EXISTS admin (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nama_admin VARCHAR(255) NOT NULL,
  password VARCHAR(25) NOT NULL,
  kode_admin VARCHAR(12) NOT NULL UNIQUE,
  no_tlp VARCHAR(13) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO admin (id, nama_admin, password, kode_admin, no_tlp) VALUES
(1, 'anisa julianti', 'admin1', 'ADM001', '085749051409'),
(2, 'dinda krisnauli', '4321', 'ADM002', '085870283409');

-- ===================
-- Table: kategori_buku
-- ===================
CREATE TABLE IF NOT EXISTS kategori_buku (
  kategori VARCHAR(255) NOT NULL,
  PRIMARY KEY (kategori)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO kategori_buku (kategori) VALUES 
('Informatika'), 
('Hukum'), 
('Manajemen'), 
('Akuntansi'), 
('Pertanian');

-- ===================
-- Table: buku
-- ===================
CREATE TABLE IF NOT EXISTS buku (
  cover VARCHAR(255) NOT NULL,
  id_buku VARCHAR(20) NOT NULL,
  kategori VARCHAR(255) NOT NULL,
  judul VARCHAR(255) NOT NULL,
  pengarang VARCHAR(255) NOT NULL,
  penerbit VARCHAR(255) NOT NULL,
  tahun_terbit YEAR NOT NULL,
  jumlah_halaman INT(11) NOT NULL,
  buku_deskripsi TEXT NOT NULL,
  PRIMARY KEY (id_buku),
  FOREIGN KEY (kategori) REFERENCES kategori_buku(kategori)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO buku (
  cover, id_buku, kategori, judul, pengarang, penerbit, tahun_terbit, jumlah_halaman, buku_deskripsi
) VALUES 
('IMGBK001.jpg', 'BK001', 'Informatika', 'Introduction to Algorithms', 'Cormen et al.', 'MIT Press', 2009, 1312, 'Buku fundamental algoritma untuk pemrograman dan rekayasa perangkat lunak.'),
('IMGBK002.jpg', 'BK002', 'Informatika', 'Clean Code', 'Robert C. Martin', 'Prentice Hall', 2008, 464, 'Panduan menulis kode yang bersih, mudah dibaca, dan mudah dipelihara.'),
('IMGBK003.jpg', 'BK003', 'Informatika', 'Computer Networking: A Top-Down Approach', 'Kurose & Ross', 'Pearson', 2016, 864, 'Buku pengantar jaringan komputer dengan pendekatan aplikasi ke bawah.'),
('IMGBK004.jpg', 'BK004', 'Hukum', 'Hukum Acara Pidana Indonesia', 'Andi Hamzah', 'Sinar Grafika', 2016, 400, 'Penjelasan lengkap proses peradilan pidana di Indonesia.'),
('IMGBK005.jpg', 'BK005', 'Hukum', 'Pengantar Ilmu Hukum', 'Soerjono Soekanto', 'Rajawali Pers', 2010, 276, 'Dasar-dasar ilmu hukum dan peran hukum dalam masyarakat.'),
('IMGBK006.jpg', 'BK006', 'Hukum', 'Legal Research: How to Find & Understand the Law', 'Stephen Elias', 'NOLO', 2012, 432, 'Panduan melakukan riset hukum, penting bagi mahasiswa dan praktisi hukum.'),
('IMGBK007.jpg', 'BK007', 'Manajemen', 'Manajemen', 'Ricky W. Griffin', 'Cengage Learning', 2016, 688, 'Pembahasan fungsi-fungsi manajemen secara menyeluruh.'),
('IMGBK008.jpg', 'BK008', 'Manajemen', 'Strategic Management: Concepts and Cases', 'Fred R. David', 'Pearson', 2017, 416, 'Panduan menyusun strategi bisnis dengan studi kasus nyata.'),
('IMGBK009.jpg', 'BK009', 'Manajemen', 'Organizational Behavior', 'Stephen P. Robbins & Timothy A. Judge', 'Pearson', 2018, 744, 'Pembahasan perilaku dalam organisasi dan manajemennya.'),
('IMGBK010.jpg', 'BK010', 'Akuntansi', 'Akuntansi Keuangan Menengah', 'Kieso et al.', 'Salemba Empat', 2020, 1000, 'Pembahasan prinsip akuntansi lanjutan dan laporan keuangan.'),
('IMGBK011.jpg', 'BK011', 'Akuntansi', 'Auditing and Assurance Services', 'Alvin A. Arens', 'Pearson', 2017, 832, 'Dasar-dasar audit laporan keuangan dengan pendekatan standar.'),
('IMGBK012.jpg', 'BK012', 'Akuntansi', 'Cost Accounting: A Managerial Emphasis', 'Charles T. Horngren', 'Pearson', 2014, 896, 'Akuntansi biaya untuk pengambilan keputusan manajerial.'),
('IMGBK013.jpg', 'BK013', 'Pertanian', 'Ilmu Tanah', 'Hardjowigeno S.', 'Akademika Pressindo', 2003, 324, 'Pembahasan sifat, klasifikasi, dan pengelolaan tanah.'),
('IMGBK014.jpg', 'BK014', 'Pertanian', 'Agribisnis: Teori dan Aplikasinya', 'Mubyarto', 'LP3ES', 2005, 250, 'Konsep agribisnis dari produksi sampai pemasaran.'),
('IMGBK015.jpg', 'BK015', 'Pertanian', 'Pemuliaan Tanaman', 'George Acquaah', 'Waveland Press', 2012, 760, 'Teknik dan prinsip pemuliaan tanaman untuk hasil pertanian optimal.');


-- ===================
-- Table: member
-- ===================
CREATE TABLE IF NOT EXISTS member (
  npm VARCHAR(11) NOT NULL,
  kode_member VARCHAR(12) NOT NULL UNIQUE,
  nama VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  jenis_kelamin VARCHAR(20) NOT NULL,
  semester INT(11) NOT NULL,
  jurusan VARCHAR(50) NOT NULL,
  no_tlp VARCHAR(15) NOT NULL,
  tgl_pendaftaran DATE NOT NULL,
  PRIMARY KEY (npm)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO member (
  npm, kode_member, nama, password, jenis_kelamin, semester, jurusan, no_tlp, tgl_pendaftaran
) VALUES (
  'g1a023080', 'mem01', 'Khalisa Amanda', 'manda080', 'Perempuan', 4, 'Informatika', '081383877025', '2025-04-23'
);

-- ===================
-- Table: peminjaman
-- ===================
CREATE TABLE IF NOT EXISTS peminjaman (
  id_peminjaman INT(11) NOT NULL AUTO_INCREMENT,
  id_buku VARCHAR(20) NOT NULL,
  npm VARCHAR(11) NOT NULL,
  id_admin INT(11) NOT NULL,
  tgl_peminjaman DATE NOT NULL,
  tgl_pengembalian DATE NOT NULL,
  PRIMARY KEY (id_peminjaman),
  FOREIGN KEY (id_buku) REFERENCES buku (id_buku),
  FOREIGN KEY (npm) REFERENCES member (npm),
  FOREIGN KEY (id_admin) REFERENCES admin (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

-- ===================
-- Table: pengembalian
-- ===================
CREATE TABLE IF NOT EXISTS pengembalian (
  id_pengembalian INT(11) NOT NULL AUTO_INCREMENT,
  id_peminjaman INT(11) NOT NULL,
  id_buku VARCHAR(20) NOT NULL,
  npm VARCHAR(11) NOT NULL,
  id_admin INT(11) NOT NULL,
  tgl_pengembalian DATE NOT NULL,
  buku_kembali DATE NOT NULL,
  keterlambatan ENUM('YA', 'TIDAK') NOT NULL,
  denda INT(15) NOT NULL,
  PRIMARY KEY (id_pengembalian),
  FOREIGN KEY (id_peminjaman) REFERENCES peminjaman (id_peminjaman),
  FOREIGN KEY (id_buku) REFERENCES buku (id_buku),
  FOREIGN KEY (npm) REFERENCES member (npm),
  FOREIGN KEY (id_admin) REFERENCES admin (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=60;

COMMIT;
