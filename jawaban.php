<?php
require_once "parent.php";

// class [NAMA_CLASS] extends [NAMA_CLASS_PARENT]
// 1. Inherit Parent Class
class jawaban extends orangtua {
    // 2. Memiliki constructor 
    // Di dalam constructor, memanggil constructor parent class
    public function __construct() {
        parent::__construct();
    }

    // Mengambil pilihan jawaban untuk satu soal dengan urutan acak (ORDER BY RAND())
    public function getJawabanBySoal($idsoal) {
        $sql = "SELECT * FROM jawaban WHERE idsoal = ? ORDER BY RAND()";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idsoal);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = [];
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    }

    // Mengambil satu data jawaban berdasarkan idjawaban
    public function getJawabanById($idjawaban) {
        $sql = "SELECT * FROM jawaban WHERE idjawaban = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idjawaban);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row;
    }

    // Mengambil jawaban yang bernilai benar (kunci jawaban) untuk suatu soal
    public function getJawabanBenarBySoal($idsoal) {
        $sql = "SELECT * FROM jawaban WHERE idsoal = ? AND benarkah = 1 LIMIT 1";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idsoal);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row;
    }

    // Mengecek apakah suatu jawaban bernilai benar (1) atau salah (0)
    public function cekJawaban($idjawaban) {
        $sql = "SELECT benarkah FROM jawaban WHERE idjawaban = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idjawaban);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();

        if ($row && (int)$row['benarkah'] === 1) {
            return 1;
        }
        return 0;
    }
}
?>
