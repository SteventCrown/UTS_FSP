<?php
require_once("data.php");

class orangtua {
    protected $mysqli;

    public function __construct() {
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

    public function __destruct() {
        if ($this->mysqli) {
            $this->mysqli->close();
        }
    }
}
?>
