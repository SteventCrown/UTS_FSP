<?php
require_once "parent.php";

class soal extends orangtua {
    public function __construct() {
        parent::__construct();
    }

    public function getDaftarHalaman() {
        $sql = "SELECT halaman_ke FROM soal ORDER BY halaman_ke ASC";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();
        $daftar = array();
        while ($row = $res->fetch_assoc()) {
            $halaman = (int)$row['halaman_ke'];
            if (!in_array($halaman, $daftar)) {
                $daftar[] = $halaman;
            }
        }
        $stmt->close();
        return $daftar;
    }

    public function getSoalByHalaman($halaman_ke) {
        $sql = "SELECT * FROM soal WHERE halaman_ke = ? ORDER BY nomor ASC";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $halaman_ke);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = array();
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    }

    public function getAllSoal() {
        $sql = "SELECT * FROM soal ORDER BY nomor ASC";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = array();
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    }

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
