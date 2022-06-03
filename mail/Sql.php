<?php

require_once ('Connection.php');

class Sql extends Connection {

    public function __construct() {
        $this->initDBO();
    }

    public function getUnsentEmailsData(): ?array {

        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT * FROM af_email_pool WHERE flag = 0 AND destiny != 'NULL';");

            if (mysqli_num_rows($result) === 0) return NULL;

            $rows = [];
            while($r = $result->fetch_assoc()) {
                $rows[] = $r;
            }

            return $rows;
        }
        catch(mysqli_sql_exception $ex) {
            var_dump($ex->getMessage());
            return NULL;
        }
    }


    public function getTopicByThreadID($threadID, $topicID) {
        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT * FROM af_topic WHERE topic_id = (SELECT topic_id from af_ticket_thread WHERE ticket_thread_id = $threadID);");

            if (mysqli_num_rows($result) === 0) return NULL;

            $rows = [];
            while($r = $result->fetch_assoc()) {
                $rows[] = $r;
            }

            return $rows;
        }
        catch(mysqli_sql_exception $ex) {
            var_dump($ex->getMessage());
            return NULL;
        }
    }


    public function setMailSendByPoolID($poolID) {
        $db = $this->dblocal;

        try {
            return $db->query("CALL sp_af_set_email_sent ('$poolID')");
        }

        catch(mysqli_sql_exception $ex)  {
            var_dump($ex->getMessage());
            return NULL;
        }
    }
}