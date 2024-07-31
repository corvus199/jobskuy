-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 29, 2024 at 02:18 AM
-- Server version: 10.0.17-MariaDB
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jobskuy`
--

-- --------------------------------------------------------

--
-- Table structure for table `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `pesanan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id`, `produk_id`, `qty`, `pesanan_id`) VALUES
(212, 33, 1, 197),
(213, 33, 1, 198),
(214, 33, 2, 199);

-- --------------------------------------------------------

--
-- Table structure for table `favorit`
--

CREATE TABLE `favorit` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `info_pembayaran`
--

CREATE TABLE `info_pembayaran` (
  `id` int(11) NOT NULL,
  `info` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `info_pembayaran`
--

INSERT INTO `info_pembayaran` (`id`, `info`) VALUES
(1, 'Bacalah dengan teliti apabila ingin melakukan transaksi\r\n\r\nPembayaran Transaksi Bisa Melalui Rekening Di Bawah Ini\r\nBRI => 7405-0100-7789-539 a/n Wawan Kusnadi\r\n\r\nkemudian konfirmasi pembayaran bisa di menu pembayaran\r\n\r\n=======================================================================\r\nRead it carefully if you want to make a transaction\r\n\r\nTransaction payments can be made via the account below\r\nBRI => 7405-0100-7789-539 a/n Wawan Kusnadi\r\n\r\nThen you can confirm payment in the payment menu');

-- --------------------------------------------------------

--
-- Table structure for table `kategori_produk`
--

CREATE TABLE `kategori_produk` (
  `id` int(3) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `kategori_produk`
--

INSERT INTO `kategori_produk` (`id`, `nama`, `deskripsi`) VALUES
(6, 'Graphics & Design', ''),
(7, 'Programming & Tech', ''),
(8, 'Digital Marketing', ''),
(9, 'Video & Animation', '');

-- --------------------------------------------------------

--
-- Table structure for table `kontak`
--

CREATE TABLE `kontak` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subjek` varchar(200) NOT NULL,
  `pesan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id` int(11) NOT NULL,
  `id_pesanan` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `file` varchar(255) NOT NULL,
  `total` int(11) NOT NULL,
  `status` enum('pending','verified','','') NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id`, `id_pesanan`, `id_user`, `file`, `total`, `status`, `keterangan`, `created_at`) VALUES
(83, 197, 29, '49eb6a44db57cba8d66b3404fa9f0ad4aa.PNG', 1000000, 'verified', '', '2024-07-27 16:07:42'),
(84, 198, 29, '49eb6a44db57cba8d66b3404fa9f0ad4aa.PNG', 1000000, 'verified', '', '2024-07-27 16:09:22'),
(85, 199, 29, '49eb6a44db57cba8d66b3404fa9f0ad4aa.PNG', 2000000, 'verified', '', '2024-07-29 04:30:07');

-- --------------------------------------------------------

--
-- Table structure for table `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int(5) NOT NULL,
  `tanggal_pesan` datetime NOT NULL,
  `user_id` int(5) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `read` enum('0','1') NOT NULL,
  `status` enum('lunas','belum lunas','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pesanan`
--

INSERT INTO `pesanan` (`id`, `tanggal_pesan`, `user_id`, `nama`, `telephone`, `read`, `status`) VALUES
(197, '2024-07-27 11:07:25', 29, 'wawan', '081378821295', '1', 'lunas'),
(198, '2024-07-27 11:09:10', 29, 'wawan', '081378821295', '1', 'lunas'),
(199, '2024-07-28 23:29:46', 29, 'wawan', '081378821295', '1', 'lunas');

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int(4) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `gambar` varchar(200) NOT NULL,
  `harga` decimal(10,0) NOT NULL,
  `kategori_produk_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `nama`, `deskripsi`, `gambar`, `harga`, `kategori_produk_id`, `user_id`) VALUES
(33, 'Logo Design', '', '49eb6a44db57cba8d66b3404fa9f0ad41.PNG', '1000000', 6, 14),
(34, 'Mobile Development', '', '49eb6a44db57cba8d66b3404fa9f0ad4mobil development.PNG', '1500000', 7, 30),
(35, 'Visual Effect', '', '49eb6a44db57cba8d66b3404fa9f0ad4Visual Effect.PNG', '1400000', 9, 31),
(36, 'Video Marketing', '', '49eb6a44db57cba8d66b3404fa9f0ad4Video Marketing.PNG', '1200000', 8, 31),
(37, 'Website Design', '', '49eb6a44db57cba8d66b3404fa9f0ad4Website Design.PNG', '1500000', 6, 14),
(38, 'Icon Design', '', '49eb6a44db57cba8d66b3404fa9f0ad4Icon Design.PNG', '1000000', 6, 14),
(39, 'Web Development', '', '49eb6a44db57cba8d66b3404fa9f0ad4Web Development.PNG', '1700000', 7, 30),
(40, 'Character Animation', '', '49eb6a44db57cba8d66b3404fa9f0ad4Character Animation.PNG', '1300000', 9, 31),
(41, 'Logo Animation', '', '49eb6a44db57cba8d66b3404fa9f0ad4Logo Animation.PNG', '1000000', 9, 31);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(10) UNSIGNED NOT NULL,
  `produk_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `review` longtext,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`id`, `produk_id`, `user_id`, `rating`, `review`, `created_at`) VALUES
(35, 33, 29, 5, 'bagus sekali', '2024-07-27 09:08:20'),
(36, 33, 29, 3, 'cukup bagus', '2024-07-27 09:09:52'),
(37, 33, 29, 5, '', '2024-07-28 21:30:56');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(5) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `email` varchar(75) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `password` varchar(100) NOT NULL,
  `status` enum('user','admin','freelance') NOT NULL,
  `skills` varchar(255) DEFAULT NULL,
  `bahasa` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `nama`, `email`, `telephone`, `alamat`, `password`, `status`, `skills`, `bahasa`) VALUES
(9, 'Admin', 'admin@gmail.com', '0813233873738', 'pekanbaru', '202cb962ac59075b964b07152d234b70', 'admin', NULL, NULL),
(14, 'iki', 'iki@gmail.com', '0818098331', 'pekanbaru', '202cb962ac59075b964b07152d234b70', 'freelance', 'design', 'php, css, javascript'),
(29, 'wawan', 'wawan13596@gmail.com', '081378821295', 'pekanbaru', '202cb962ac59075b964b07152d234b70', 'user', ' ', NULL),
(30, 'udin', 'udin@gmail.com', '081378821292', 'Marpoyan', '202cb962ac59075b964b07152d234b70', 'freelance', ' Mobile Developer & Web Developer', NULL),
(31, 'ujeng', 'ujeng@gmail.com', '081378821200', 'Rumbai', '202cb962ac59075b964b07152d234b70', 'freelance', 'Visual Effects & Software developer', NULL),
(53, 'tes 3', 'tes3@gmail.com', '082313t13', 'Rumbai', '202cb962ac59075b964b07152d234b70', 'freelance', ' ', NULL),
(56, 'tes', 'tes@gmail.com', '2', 'Marpoyan', '698d51a19d8a121ce581499d7b701668', 'user', ' ', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id`,`produk_id`,`pesanan_id`),
  ADD KEY `pesanan_id` (`pesanan_id`),
  ADD KEY `produk_id` (`produk_id`);

--
-- Indexes for table `favorit`
--
ALTER TABLE `favorit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `produk_id` (`produk_id`);

--
-- Indexes for table `info_pembayaran`
--
ALTER TABLE `info_pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kategori_produk`
--
ALTER TABLE `kategori_produk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kontak`
--
ALTER TABLE `kontak`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`,`kategori_produk_id`),
  ADD KEY `kategori_produk_id` (`kategori_produk_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=215;
--
-- AUTO_INCREMENT for table `favorit`
--
ALTER TABLE `favorit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `info_pembayaran`
--
ALTER TABLE `info_pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `kategori_produk`
--
ALTER TABLE `kategori_produk`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `kontak`
--
ALTER TABLE `kontak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;
--
-- AUTO_INCREMENT for table `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=200;
--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_pesanan_ibfk_3` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `favorit`
--
ALTER TABLE `favorit`
  ADD CONSTRAINT `favorit_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `favorit_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`);

--
-- Constraints for table `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`kategori_produk_id`) REFERENCES `kategori_produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
