<?php
$dbuserx='actionflow_db';
$dbpassx='$%2020mst!';

class dbconn {
    public $dblocal;
    public function __construct()
    {

    }
    public function initDBO()
    {
        global $dbuserx,$dbpassx;
        try {
            $this->dblocal = new mysqli("localhost",$dbuserx,$dbpassx,"smartflow_dev");
        }
        catch(mysqli_sql_exception $e)
        {
            die("DB ERROR001");
        }
    }
}
