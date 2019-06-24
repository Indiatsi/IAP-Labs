<?php
class DBConnector{
    public $conn;
    private $DB_SERVER = 'localhost';
    private $DB_USER = 'root';
    private $DB_PASS = '';
    private $DB_NAME = 'btc3205';
    function __construct(){
        $this->conn = new mysqli(
            $this->DB_SERVER,$this->DB_USER,$this->DB_PASS,$this->DB_NAME);
    }
    public function getConnection(){
        return $this->conn;
    }

    public function closeDatabase(){
        $this->conn->close();
    }
}
?>