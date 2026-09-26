<?php
require_once "parent.php";

// class [NAMA_CLASS] extends [NAMA_CLASS_PARENT]
// 1. Inherit Parent Class
class soal extends orangtua {
    // 2. Memiliki constructor 
    // Di dalam constructor, memanggil constructor parent class
    public function __construct() {
        parent::__construct();
    }

    // Mendapatkan daftar halaman_ke yang unik dan terurut
    public function getDaftarHalaman() {
        $sql = "SELECT DISTINCT halaman_ke FROM soal ORDER BY halaman_ke ASC";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();
        $daftar = [];
        while ($row = $res->fetch_assoc()) {
            $daftar[] = (int)$row['halaman_ke'];
        }
        $stmt->close();
        return $daftar;
    }

    // Mengambil semua soal pada halaman tertentu sesuai kolom halaman_ke
    public function getSoalByHalaman($halaman_ke) {
        $sql = "SELECT * FROM soal WHERE halaman_ke = ? ORDER BY nomor ASC";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $halaman_ke);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = [];
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    }

    // Mengambil seluruh data soal untuk halaman kesimpulan
    public function getAllSoal() {
        $sql = "SELECT * FROM soal ORDER BY nomor ASC";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = [];
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    }

    // Mengambil data satu soal berdasarkan idsoal
    public function getSoalById($idsoal) {
        $sql = "SELECT * FROM soal WHERE idsoal = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idsoal);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row;
    }
}
?>
