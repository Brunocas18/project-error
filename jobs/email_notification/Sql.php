<?php

require_once ('Connection.php');

class Sql extends Connection {

    public function __construct() {
        $this->initDBO();
    }

    public function getTopicsByThreadID($threadID): ?array
    {
        $db = $this->dblocal;

        try {
            $result = $db->query("select `topic_description_l0`, `topic_description_l1`, `topic_description` from v_af_ticket_detail WHERE ticket_thread_id = '$threadID';");

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

    public function getSubjectByThreadID($threadID): ?array
    {
        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT * FROM v_af_ticket_detail WHERE ticket_thread_id=$threadID;");

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

    /**
     * Check for emails that have not been sent
     * @return array|null Data or nothing if emails have been sent
     */
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

    /**
     * Get the external reference based on the name of the form and the thread id
     * @param $formID the name of the table form
     * @param $threadID the ID of the thread ID
     * @return array|null return the reference or null if it doesn't exist.
     */
    
    public function getExternalRefByThreadAndFormID($formID, $threadID): ?array {
        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT * from `$formID` WHERE ticket_thread_id = $threadID");

            if (mysqli_num_rows($result) === 0) return NULL;

            $rows = [];
            while($r = $result->fetch_assoc()) {
                $rows[] = $r;
            }

            return $rows[0];
        }
        catch(mysqli_sql_exception $ex) {
            var_dump($ex->getMessage());
            return NULL;
        }
    }

    /**
     * GET FORM DATA USING THE THREAD ID TO GET THE REFERENCE
     * @param $threadID
     * @return array|null
     */

    public function getFormDataByThreadID($threadID): ?array {
        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT form_data from v_af_ticket_detail WHERE ticket_thread_id = $threadID");

            if (mysqli_num_rows($result) === 0) return NULL;

            $rows = [];
            while($r = $result->fetch_assoc()) {
                $rows[] = $r;
            }

            return $rows[0];
        }
        catch(mysqli_sql_exception $ex) {
            var_dump($ex->getMessage());
            return NULL;
        }
    }

    /**
     * GET THE CHILD FOR A TOPIC
     * @param $childID THE ID OF THE CHILD
     * @return array|null the child or null if the topic don't have a child
     */

    public function getTopicByChildID($childID): ?array {
        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT * from af_topic where topic_id = $childID;");

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

    /**
     * Get the state of the thread
     * @param $threadID The ID of the thread
     * @return array|null Return the state
     */

    public function getStateByThreadID($threadID): ?array {
        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT * FROM af_state WHERE state_id = (SELECT state_id from af_ticket_thread WHERE ticket_thread_id = $threadID);");

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

    /**
     * Get the team that relates to the thread id
     * @param $threadID The ID of the thread
     * @return array|null The team or null if a usar has no team
     */

    public function getTeamByThreadID($threadID): ?array {
        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT * FROM v_af_team WHERE team_id = (SELECT on_team_id FROM af_ticket_thread WHERE ticket_thread_id = $threadID);");

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

    /**
     * Get the topic that relates to the thread id
     * @param $threadID The ID of the thread
     * @return array|null The topic
     */

    public function getTopicByThreadID($threadID): ?array {
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

    /**
     * Set the flag to 1, indicates that the email has been sent
     * @param $poolID the ID of the email pool
     * @return bool|mysqli_result|null true if the stored procedures runs properly, false otherwise.
     */

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