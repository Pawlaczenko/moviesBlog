<?php

class DB {

    protected $conn;
    private $user_name;
    private $user_pass;
    private $db_name;
    private $db_port;
    private $db_address;

    public function __construct($addr = "", $usr = "", $pass = "", $db = "", $port = "") {
        
        $this->db_address = !$addr ? DB_ADDRESS : $addr;
        $this->user_name = !$usr ? DB_USER : $usr;
        $this->user_pass = !$pass ? DB_PASSWORD : $pass;
        $this->db_name = !$db ? DB_NAME : $db;
        $this->db_port = !$port ? DB_PORT : $port;
    }

    protected function Open() {
        $this->conn = new mysqli($this->db_address, $this->user_name, $this->user_pass, $this->db_name);

        if ($this->conn->connect_errno) {
            return false;
        }
        return true;
    }

    protected function Close() {
        $this->conn->Close();
    }

    public function Query($query) {
        $this->Open();
        
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $result = $this->conn->query($query);

        $data = NULL;

        if( stripos($query,'INSERT') !== false ) {
            if(stripos($query,'Liked_Post') !== false ){
                $data = $result;
            } else {
                $data = $this->conn->insert_id;
            }
        }
        
        if( stripos($query,'DELETE') !== false ) {
            $data = $this->conn->affected_rows;
        }
        
        if( stripos($query,'UPDATE') !== false ) {
            $data = $this->conn->affected_rows;
        }

        if( stripos($query,'SELECT') !== false ) {
            $data = [];

            while ($row = $result->fetch_object()) {
                $data[] = $row;
            }
        }
        
        $this->Close();
        return $data;
    }
}