<?php
// include isi dari data.php
require_once("data.php");

class orangtua {
    // Memiliki protected member: object mysqli
    protected $mysqli;

    // Memiliki constructor dan di dalam constructor, langsung dilakukan koneksi ke database
    public function __construct() {
        // Assign mysqli dengan koneksi ke database
        $this->mysqli = new mysqli(
            SERVER_NAME, 
            USER_NAME, 
            PASSWORD, 
            DB_NAME
        );

        if ($this->mysqli->connect_errno) {
            die("Failed to connect database MySQL: " . $this->mysqli->connect_error);
        }
    }

    // Memiliki destructor, yaitu melakukan close connection pada object mysqli
    public function __destruct() {
        if ($this->mysqli) {
            $this->mysqli->close();
        }
    }
}
?>
