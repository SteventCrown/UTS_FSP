<?php
require_once "parent.php";

class jawaban extends orangtua {
    public function __construct() {
        parent::__construct();
    }

    public function getJawabanBySoal($idsoal) {
        $sql = "SELECT * FROM jawaban WHERE idsoal = ? ORDER BY RAND()";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idsoal);
        $stmt->execute();
        $res = $stmt->get_result();
        $data = array();
        while ($row = $res->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $data;
    }

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

    public function cekJawaban($idjawaban) {
        $sql = "SELECT benarkah FROM jawaban WHERE idjawaban = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idjawaban);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();

        if ($row && (int)$row['benarkah'] == 1) {
            return 1;
        }
        return 0;
    }
}
?>
