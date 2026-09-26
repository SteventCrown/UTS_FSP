-- Database: fullstack
-- UTS Full-stack Programming

CREATE DATABASE IF NOT EXISTS `fullstack`;
USE `fullstack`;

-- Struktur dari tabel `soal`
DROP TABLE IF EXISTS `jawaban`;
DROP TABLE IF EXISTS `soal`;

CREATE TABLE `soal` (
  `idsoal` int(11) NOT NULL AUTO_INCREMENT,
  `nomor` int(11) NOT NULL,
  `pertanyaan` text NOT NULL,
  `halaman_ke` int(11) NOT NULL,
  PRIMARY KEY (`idsoal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data untuk tabel `soal`
INSERT INTO `soal` (`idsoal`, `nomor`, `pertanyaan`, `halaman_ke`) VALUES
(1, 1, 'Siapakah nama saudara kembar Upin?', 1),
(2, 2, 'Siapakah nama nenek dari Upin dan Ipin?', 1),
(3, 3, 'Berikut ini adalah bukan nama teman Upin, kecuali', 2),
(4, 4, 'Siapakah nama kakak perempuan Upin dan Ipin yang terkenal galak?', 2),
(5, 5, 'Apakah makanan yang paling digemari oleh Upin dan Ipin?', 3),
(6, 6, 'Siapakah nama sahabat Upin dan Ipin yang berasal dari Indonesia?', 3),
(7, 7, 'Siapakah nama guru kelas Upin dan Ipin di Tadika Mesra?', 4),
(8, 8, 'Siapakah nama sahabat Upin dan Ipin yang gemar berpantun?', 4),
(9, 9, 'Apakah slogan khas yang sering diucapkan oleh Mail ketika berdagang?', 5),
(10, 10, 'Siapakah nama ayam jantan peliharaan kesayangan Tok Dalang?', 5);

-- Struktur dari tabel `jawaban`
CREATE TABLE `jawaban` (
  `idjawaban` int(11) NOT NULL AUTO_INCREMENT,
  `idsoal` int(11) NOT NULL,
  `isi_jawaban` text NOT NULL,
  `benarkah` tinyint(1) NOT NULL,
  PRIMARY KEY (`idjawaban`),
  KEY `idsoal` (`idsoal`),
  CONSTRAINT `fk_jawaban_soal` FOREIGN KEY (`idsoal`) REFERENCES `soal` (`idsoal`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Data untuk tabel `jawaban`
INSERT INTO `jawaban` (`idjawaban`, `idsoal`, `isi_jawaban`, `benarkah`) VALUES
-- Soal 1
(1, 1, 'Ipin', 1),
(2, 1, 'Ehsan', 0),
(3, 1, 'Mail', 0),
(4, 1, 'Fizi', 0),

-- Soal 2
(5, 2, 'Opah', 1),
(6, 2, 'Kak Ros', 0),
(7, 2, 'Cikgu Jasmin', 0),
(8, 2, 'Mak Siti', 0),

-- Soal 3
(9, 3, 'Jarjit', 1),
(10, 3, 'Unyil', 0),
(11, 3, 'Android', 0),
(12, 3, 'Kak Ros', 0),

-- Soal 4
(13, 4, 'Kak Ros', 1),
(14, 4, 'Opah', 0),
(15, 4, 'Cikgu Melati', 0),
(16, 4, 'Susanti', 0),

-- Soal 5
(17, 5, 'Ayam Goreng', 1),
(18, 5, 'Rendang', 0),
(19, 5, 'Nasi Lemak', 0),
(20, 5, 'Bakso', 0),

-- Soal 6
(21, 6, 'Susanti', 1),
(22, 6, 'Mei Mei', 0),
(23, 6, 'Devi', 0),
(24, 6, 'Ijat', 0),

-- Soal 7
(25, 7, 'Cikgu Jasmin', 1),
(26, 7, 'Cikgu Melati', 0),
(27, 7, 'Cikgu Besar', 0),
(28, 7, 'Mak Cik Rose', 0),

-- Soal 8
(29, 8, 'Jarjit', 1),
(30, 8, 'Mail', 0),
(31, 8, 'Ehsan', 0),
(32, 8, 'Fizi', 0),

-- Soal 9
(33, 9, 'Dua seringgit', 1),
(34, 9, 'Betul betul betul', 0),
(35, 9, 'Marvelous', 0),
(36, 9, 'Sedap giler', 0),

-- Soal 10
(37, 10, 'Rembo', 1),
(38, 10, 'Jago', 0),
(39, 10, 'Rambo', 0),
(40, 10, 'Si Putih', 0);
