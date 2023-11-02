-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 03, 2023 at 06:36 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `assesment_prog_buku`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `listPeminjam` (IN `id_anggota` INT)  BEGIN
	SELECT p.id_peminjaman as "ID_PINJAM", p.id_buku as "ID_BUKU", b.judul as "BUKU", p.tgl_pinjam as "TGL_PINJAM", p.tgl_jatuh_tempo as "TGL_JATUH_TEMPO", p.tgl_kembali as "TGL_KEMBALI"
    FROM peminjaman p 
    LEFT JOIN buku b ON p.id_buku = b.id_buku
    WHERE p.id_anggota = id_anggota;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pinjamBuku` (IN `id_anggota` INT, IN `id_buku` INT, IN `tanggal_pinjam` DATE, IN `lama_pinjam` INT, IN `keterangan` VARCHAR(256))  BEGIN
    -- Handling exception
    DECLARE notFound INT DEFAULT 0;

    -- Inisiasi Variabel yg dibutuhkan
    DECLARE current_stock INT;
    DECLARE current_bookName VARCHAR(256); -- Specify the length for VARCHAR
    DECLARE current_bookAuthor VARCHAR(256); -- Specify the length for VARCHAR
    DECLARE due_date DATE;
    DECLARE id_pinjam INT;

    -- Simpan data stok buku pada variable current_stock
    SELECT stok, judul, pengarang INTO current_stock, current_bookName, current_bookAuthor
    FROM buku WHERE buku.id_buku = id_buku;

    -- Set due_date berdasarkan tanggal_pinjam dan lama_pinjam
    SET due_date = DATE_ADD(tanggal_pinjam, INTERVAL lama_pinjam DAY);

    -- Jika stok buku HABIS
    IF (current_stock = 0) THEN
        SELECT CONCAT('Stok buku ', current_bookName, '-', current_bookAuthor, ', sudah tidak tersedia') AS "MESSAGE";
    ELSE
        START TRANSACTION;
        INSERT INTO peminjaman (id_anggota, id_buku, tgl_pinjam, tgl_jatuh_tempo, keterangan) VALUES
        (id_anggota, id_buku, tanggal_pinjam, due_date, keterangan);

        -- Retrieve the last inserted ID
        SET id_pinjam = LAST_INSERT_ID();

        IF (id_pinjam IS NOT NULL) THEN
            COMMIT;
            SELECT 'Pinjam Buku Berhasil';
        ELSE
            ROLLBACK;
            SELECT 'Pinjam buku gagal';
        END IF;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `anggota`
--

CREATE TABLE `anggota` (
  `id_anggota` int(11) NOT NULL,
  `nama` varchar(128) DEFAULT NULL,
  `tgl_lahir` date NOT NULL DEFAULT current_timestamp(),
  `alamat` varchar(256) DEFAULT NULL,
  `email` varchar(64) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `anggota`
--

INSERT INTO `anggota` (`id_anggota`, `nama`, `tgl_lahir`, `alamat`, `email`, `no_hp`) VALUES
(1, 'Alice Johnson', '1990-05-15', 'Jl. Anggota No. 123, Kota Contoh', 'alicejohnson@email.com', '08123456789'),
(2, 'Bob Wilson', '1985-12-20', 'Jl. Anggota No. 456, Kota Contoh', 'bobwilson@email.com', '08567890123'),
(3, 'Charlie Smith', '1988-07-10', 'Jl. Anggota No. 789, Kota Contoh', 'charliesmith@email.com', '08234567890'),
(4, 'David Clark', '1992-02-28', 'Jl. Anggota No. 321, Kota Contoh', 'davidclark@email.com', '08123456712'),
(5, 'Eve Brown', '1987-09-03', 'Jl. Anggota No. 567, Kota Contoh', 'evebrown@email.com', '08123456734');

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `judul` varchar(256) DEFAULT NULL,
  `pengarang` varchar(256) DEFAULT NULL,
  `stok` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `judul`, `pengarang`, `stok`) VALUES
(1, 'Pemrograman Python: Panduan Praktis', 'John Doe', 120),
(2, 'Dasar-Dasar SQL: Panduan Belajar Terstruktur', 'Jane Smith', 95),
(3, 'Algoritma dan Struktur Data: Teori dan Praktik', 'David Brown', 80),
(4, 'Bisnis Online: Rahasia Kesuksesan E-Commerce', 'Sandra Lee', 109),
(5, 'Jaringan Komputer: Panduan Lengkap', 'Michael Wilson', 70),
(6, 'Desain Grafis Modern: Kreativitas di Ujung Jari Anda', 'Lisa Johnson', 60),
(7, 'Buku Koki Pemula: 100 Resep Lezat', 'Daniel Miller', 90),
(8, 'Pengantar Keilmu Komputer: Konsep Dasar', 'Sophia Davis', 50),
(9, 'Buku Seni: Eksplorasi Visual dan Kreatif', 'Robert Clark', 75),
(10, 'Psikologi Manusia: Memahami Pikiran dan Perilaku', 'Karen Turner', 0);

--
-- Triggers `buku`
--
DELIMITER $$
CREATE TRIGGER `log_book_activities` AFTER UPDATE ON `buku` FOR EACH ROW BEGIN
	-- Variable untuk menentukan pengurangan / penambahan stok
    DECLARE vKet VARCHAR(256);
    
	IF OLD.stok != NEW.stok THEN
    	-- Penentuan keterangan
        IF OLD.stok > NEW.stok THEN
        	SET vKet = CONCAT('Pengurangan stok buku :',OLD.judul);
        ELSE 
        	SET vKet = CONCAT('Penambahan stok buku : ',OLD.judul);
        END IF;
        
    	-- Logging 
        INSERT INTO log_buku (id_buku, perubahan_stok, keterangan)
        VALUES (NEW.id_buku, NEW.stok - OLD.stok, vKet);
        
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `data_statistik`
-- (See below for the actual view)
--
CREATE TABLE `data_statistik` (
`jumlah_anggota` bigint(21)
,`jumlah_peminjaman` bigint(21)
,`jumlah_buku` bigint(21)
,`jumlah_buku_dipinjam` bigint(21)
,`jumlah_buku_dikembalikan` bigint(21)
);

-- --------------------------------------------------------

--
-- Table structure for table `log_buku`
--

CREATE TABLE `log_buku` (
  `log_id` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `tgl_log` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `perubahan_stok` int(11) NOT NULL DEFAULT 0,
  `keterangan` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `log_buku`
--

INSERT INTO `log_buku` (`log_id`, `id_buku`, `tgl_log`, `perubahan_stok`, `keterangan`) VALUES
(1, 1, '2023-11-02 23:01:53', -1, 'Pengurangan stok buku'),
(2, 1, '2023-11-02 23:03:54', 1, 'Penambahan stok buku'),
(3, 4, '2023-11-02 23:08:05', -1, 'Pengurangan stok buku :Bisnis Online: Rahasia Kesuksesan E-Commerce'),
(4, 4, '2023-11-02 23:09:47', 1, 'Penambahan stok buku : Bisnis Online: Rahasia Kesuksesan E-Commerce'),
(5, 10, '2023-11-02 23:10:38', -65, 'Pengurangan stok buku :Psikologi Manusia: Memahami Pikiran dan Perilaku'),
(6, 4, '2023-11-02 23:29:18', -1, 'Pengurangan stok buku :Bisnis Online: Rahasia Kesuksesan E-Commerce');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `tgl_pinjam` date NOT NULL,
  `tgl_jatuh_tempo` date NOT NULL,
  `tgl_kembali` date DEFAULT NULL,
  `keterangan` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `id_anggota`, `id_buku`, `tgl_pinjam`, `tgl_jatuh_tempo`, `tgl_kembali`, `keterangan`) VALUES
(2, 1, 1, '2023-01-01', '2023-01-31', '2023-01-30', 'Pinjam Bukunya bro'),
(3, 1, 4, '2023-02-01', '2023-02-15', '2023-02-06', 'Pinjem lagi ahh'),
(4, 2, 4, '2023-04-11', '2023-04-18', NULL, 'Pinjem yg ini yaaa');

--
-- Triggers `peminjaman`
--
DELIMITER $$
CREATE TRIGGER `check_stok_buku` BEFORE INSERT ON `peminjaman` FOR EACH ROW BEGIN

	DECLARE currentStok INT;
	-- Cek kondisi stok apakah sudah 0 atau belum
    SELECT stok INTO currentStok
    FROM buku
    WHERE id_buku = NEW.id_buku;
    
    IF currentStok = 0 THEN
    	SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Stok buku yang ingin dipinjam sudah habis';
	END IF;

END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `kembalikan_buku` BEFORE UPDATE ON `peminjaman` FOR EACH ROW BEGIN
	
    IF NEW.tgl_kembali IS NOT NULL AND OLD.tgl_kembali IS NULL THEN
    
    	-- Update Stok buku untuk ditambahkan lagi
        UPDATE buku
        SET stok = stok + 1
        WHERE id_buku = NEW.id_buku;
        
	END IF;
    
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `pinjam_buku` AFTER INSERT ON `peminjaman` FOR EACH ROW BEGIN

	UPDATE buku
    SET stok = stok - 1
    WHERE id_buku = new.id_buku;
    
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure for view `data_statistik`
--
DROP TABLE IF EXISTS `data_statistik`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `data_statistik`  AS SELECT (select count(0) from `anggota`) AS `jumlah_anggota`, (select count(0) from `peminjaman`) AS `jumlah_peminjaman`, (select count(0) from `buku`) AS `jumlah_buku`, (select count(0) from `peminjaman` where `peminjaman`.`tgl_kembali` is null) AS `jumlah_buku_dipinjam`, (select count(0) from `peminjaman` where `peminjaman`.`tgl_kembali` is not null) AS `jumlah_buku_dikembalikan` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id_anggota`) USING HASH;

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indexes for table `log_buku`
--
ALTER TABLE `log_buku`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `fk_buku` (`id_buku`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`),
  ADD KEY `fk_anggota` (`id_anggota`),
  ADD KEY `fk_pinjam_buku` (`id_buku`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id_anggota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `log_buku`
--
ALTER TABLE `log_buku`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `log_buku`
--
ALTER TABLE `log_buku`
  ADD CONSTRAINT `fk_buku` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`);

--
-- Constraints for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `fk_anggota` FOREIGN KEY (`id_anggota`) REFERENCES `anggota` (`id_anggota`),
  ADD CONSTRAINT `fk_pinjam_buku` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
