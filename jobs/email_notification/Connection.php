<?php

class Connection {

    public string $dbuserx='actionflow_db';
    public string $dbpassx='$%2020mst!';
    public mysqli $dblocal;

    public function __construct() {}

    public function initDBO() {

        try {
            $this->dblocal = new mysqli("localhost",$this->dbuserx,$this->dbpassx,"smartflow_dev");
        }
        catch(mysqli_sql_exception $e) {
            die("DB ERROR001");
        }
    }
}