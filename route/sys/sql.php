<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once ('dbconn.php');

class sql extends dbconn {

    public function __construct() {
        $this->initDBO();
    }

    public function checkSisters($motherID, $isMother = false): ?array
    {
        $db = $this->dblocal;

        try {
            if ($isMother) {
                $result = $db->query("SELECT * from v_af_ticket_detail where mother_id = $motherID AND ticket_thread_id NOT IN ($motherID)");
            } else {
                $result = $db->query("SELECT * from v_af_ticket_detail where mother_id = $motherID");
            }

            if (is_bool($result)) return NULL;
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


    public function insertFormData($sql): ?string
    {
        $db = $this->dblocal;

        try {
            $result = $db->query($sql);
            return json_encode($result);
        }
        catch(mysqli_sql_exception $ex) {
            var_dump($ex->getMessage());
            return NULL;
        }
    }

    public function getTopicForm($topicID): ?array
    {
        $db = $this->dblocal;

        try {
            $result = $db->query("select form_data from v_af_topic_form where topic_id = '$topicID'");

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

    public function checkModulesPrimavera(): ?array
    {
        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT id, module_1 from v_af_x_ref_module  where system_1 = 'Primavera' ORDER BY module_1 ASC;");

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



    public function checkIfMultiTicket($topicID, $isChild = false): ?array
    {
        $db = $this->dblocal;

        try {
            if ($isChild) {
                $result = $db->query("SELECT * from af_multi_ticket where topic_child = $topicID");
            } else {
                $result = $db->query("SELECT * from af_multi_ticket where topic_parent = $topicID");
            }


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

    public function getBehalfEmailByThreadID($threadID): ?array
    {
        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT user_name_behalf_email FROM v_af_ticket_pool where ticket_thread_id=$threadID and user_name_behalf_email IS NOT NULL");

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



    public function getChildByGrandAndParent($parentID): ?array
    {
        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT
child.topic_id as child_id
from af_topic as child
join af_topic as parent
join af_topic as grandchild
where child.topic_id_child = parent.topic_id and parent.topic_id_child = grandchild.topic_id and parent.topic_id = $parentID");

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


    public function getChildByGrandchildID($grandID): ?array
    {
        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT
child.topic_id as child_id
from af_topic as child
join af_topic as parent
join af_topic as grandchild
where child.topic_id_child = parent.topic_id and parent.topic_id_child = grandchild.topic_id and grandchild.topic_id = $grandID");

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


    public function ticketPoolSearch($post = NULL)
    {
        $db = $this->dblocal;
        try
        {
            $post['origem'] = !empty($post['origem']) ? $post['origem'] : 'SmartFlow';

            $sql = "SELECT * FROM v_af_ticket_pool WHERE source_name = '{$post['origem']}'";

            if (!empty($post['assunto'])) {
                $sql .= " AND `subject` like '%{$post['assunto']}%'";
            }

            if (!empty($post['topicoNivel2'])) {
                $sql .= " AND topic_id = {$post['topicoNivel2']}";
            }

            // so existe topico abertura
            if (!empty($post['topicoAbertura']) && empty($post['topicoNivel2'])) {
                $tickets = implode(',', array_column($this->getChildByGrandchildID($post['topicoAbertura']), 'child_id'));
                $sql .= " AND ticket_thread_id IN ($tickets)";
            }

            //& existe topico abertura e topico nivel 1
            if (!empty($post['topicoNivel1']) && empty($post['topicoNivel2'])) {
                $tickets = implode(',', array_column($this->getChildByGrandAndParent($post['topicoNivel1']), 'child_id'));
                $sql .= " AND ticket_thread_id IN ($tickets)";
            }

            if (!empty($post['ticket_thread_id'])) {
                $sql .= " AND ticket_thread_id = {$post['ticket_thread_id']}";
            }


            if (!empty($post['emnomede'])) {
                $sql .= " AND user_name_behalf = '{$post['emnomede']}'";
            }

            if (!empty($post['utilizador'])) {

                $sql .= " AND user_id = '{$post['utilizador']}'";
            }

            if (!empty($post['estadoTicket'])) {
                $sql .= " AND state_id = '{$post['estadoTicket']}'";
            }

            if (!empty($post['equipa'])) {
                $sql .= " AND on_team_id = '{$post['equipa']}'";
            }

            if (!empty($post['date'])) {
                $sql .= " AND created > '{$post['date']}'";
            }

            if (!empty($post['atribuido'])) {
                $sql .= " AND user_id_op = {$post['atribuido']}";
            }

            $result = $db->query($sql);
            $data = array();
            while($r = $result->fetch_array()) {
                $data['data'][] = array(
                    $r['ticket_thread_id'],
                    $r['source_name'],
                    $r['created'],
                    $r['days'],
                    $r['hours'],
                    $r['topic_sla'],
                    $r['count_red'],
                    $r['topic_description'],
                    $r['subject'],
                    $r['user_name'],
                    $r['user_name_behalf'],
                    $r['state_code'],
                    $r['on_team_name'],
                    $r['user_name_op']
                );
            }

            //return $stat;
            return json_encode($data);
        }
        catch(mysqli_sql_exception $ex)
        {
            $data[0] = false;
            $data[1] = $ex->getMessage();
            return $data;
        }
    }

    public function getAllSources(): ?array
    {
        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT * FROM af_source;");

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

    public function getAllColumns(): ?array
    {
        $db = $this->dblocal;

        try {
            $result = $db->query("select distinct column_name from information_schema.columns
                                        where table_schema = 'smartflow_dev'
                                        and table_name like 'af_x_form%'");

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

    public function getFormColumnsByNumber($number): ?array
    {
        $db = $this->dblocal;

        try {
            $result = $db->query("describe af_x_form_$number;");

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

    function getFormByID($id): ?array
    {
        $db = $this->dblocal;

        try {
            $result = $db->query("select form_id from af_topic where topic_id = $id;");

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

    public function getTopicoAbertura(): ?array
    {
        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT * FROM af_topic where topic_level = 0;");

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

    public function getAllCompanies(): ?array
    {
        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT * from v_af_x_ref_company;");

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


    public function getAllStates(): ?array
    {
        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT * from af_state;");

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

    public function getAllTeams(): ?array {
        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT * from v_af_team;");

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
     * GET ALL USERS FROM THE DATABASE
     * @return array|null it should return users, but if don't return null
     */

    public function getAllUsers(): ?array {
        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT * from af_user;");

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
     * GET THE TEAM DESCRIPTION GIVEN A THREAD ID
     * @param $thread_id THE ID OF THE THREAD
     * @return array|null AN ARRAY WITH THE TEAM NAME OR NULL IF THE
     */

    public function getTeamNameByThreadID($thread_id): ?array {

        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT team_description FROM v_af_team WHERE team_id=(SELECT on_team_id FROM af_ticket_thread WHERE ticket_thread_id=$thread_id);");

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
     * GET TICKET ID BY THE TICKET THREAD ID
     * @param $threadID INT ID OF THE THREAD
     * @return array|null RETURN THE DETAILS OF THE TICKET OR NULL
     */

    public function getTicketByThreadID($threadID): ?array {

        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT * FROM af_ticket WHERE ticket_thread_id=$threadID;");

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
     * GET TEAM USERS BY THE ID OF THE TEAM
     * @param $teamID THE ID OF THE TEAM
     * @return array|null RETURN AN ARRAY WITH THE USERS OR NULL
     */

    public function getTeamUsersByTeamID($teamID): ?array {
        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT * FROM v_af_user_team WHERE team_id=$teamID;");

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
     * GET USERS BY THE THREAD ID
     * @param $thread_id THRAD ID
     * @return array|null RETURN THE ARRAY IF THERE'S USERS OR NULL IF IT DOESN'T ENCOUNTER ANY TEAM WITH MEMBERS
     */

    public function getTeamUsersByThreadID($thread_id): ?array {

        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT * FROM v_af_user_team WHERE team_id=(SELECT on_team_id FROM af_ticket_thread WHERE ticket_thread_id=$thread_id);");

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
     * iNSERT DETAILS INTO EMAIL POOL(SEND EMAIL CHOSEN OPERATOR), A JOB WILL RUN AND THE EMAIL WILL BE SENT
     * @param string $destiny Email destination
     * @param string $origin Email origin
     * @param string $subject Email Subject
     * @param string $body The body/motive for the operator
     * @param string $ticket_thread_id
     * @param string $note_type
     * @return array|false|string
     */

    public function insertIntoEmailPool(string $destiny, string $origin, string $subject, string $body, string $ticket_thread_id, string $note_type): ?bool {

        $db = $this->dblocal;

        try {
            return $db->query("CALL sp_af_insert_into_email_pool ('$destiny', '$origin', '$subject', '$body', '$ticket_thread_id', '$note_type')");
        }

        catch(mysqli_sql_exception $ex)  {
            var_dump($ex->getMessage());
            return NULL;
        }
    }

    /**
     * GET USER DETAILS FROM DATABASE
     * @param string|null $userId THE ID OF THE USER
     * @return array AN ARRAY WITH USER DETAILS OR AN ERROR [0] AND [1]
     */

    public function getUserDetails($userId = NULL): ?array {

        $db = $this->dblocal;

        try {
            $result = $db->query("SELECT * FROM af_user WHERE user_id=$userId");

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

    public function getSysConf(){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT * FROM af_sys_conf");

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows['item'][] = $r;
            }

            //return $stat;
            return json_encode($rows);
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }

    public function getLogin($term1, $term2){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT * FROM v_af_user_team
                                    WHERE user_ad_domain='$term1' AND user_ad='$term2' AND user_status=1");

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows[] = $r;
            }

            //return $stat;
            $db->query("UPDATE af_user SET user_last_login=NOW() WHERE user_ad_domain='$term1' AND user_ad='$term2' AND user_status=1");
            return json_encode($rows);
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }


    public function getTicketCount($term1){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT state_id, state_code, sum(count) AS count 
                                    FROM v_af_ticket_count
                                    WHERE team_id IN ($term1)
                                    group by state_id");

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows[] = $r;
            }

            //return $stat;
            return json_encode($rows);
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }


    public function getTicketCountOp($term1){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT * 
                                    FROM v_af_ticket_count_op
                                    WHERE user_id_op = $term1");

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows[] = $r;
            }

            //return $stat;
            return json_encode($rows);
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }


    public function getTicketCountOwn($term1){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT * 
                                    FROM v_af_ticket_count_usr
                                    WHERE user_id = $term1");

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows[] = $r;
            }

            //return $stat;
            return json_encode($rows);
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }


    public function getTicketPoolbyState($term1){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT *
                                    FROM v_af_ticket_pool
                                    WHERE state_id =$term1");

            $data = array();
            while($r = $result->fetch_array()) {
                $data['data'][] = array(
                    $r['ticket_thread_id'],
                    $r['source_name'],
                    $r['created'],
                    $r['days'],
                    $r['hours'],
                    $r['topic_sla'],
                    $r['count_red'],
                    $r['topic_description'],
                    $r['subject'],
                    $r['user_name'],
                    $r['user_name_behalf'],
                    $r['state_code'],
                    $r['on_team_name'],
                    $r['user_name_op']
                );
            }

            //return $stat;
            return json_encode($data);
        }
        catch(mysqli_sql_exception $ex)
        {
            $data[0] = false;
            $data[1] = $ex->getMessage();
            return $data;
        }
    }


    public function getTicketPoolbyStateAndTeams($term1,$term2){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT *
                                    FROM v_af_ticket_pool
                                    WHERE state_id =$term1 AND on_team_id IN ($term2)");

            $data = array();
            while($r = $result->fetch_array()) {
                $data['data'][] = array(
                    $r['ticket_thread_id'],
                    $r['source_name'],
                    $r['created'],
                    $r['days'],
                    $r['hours'],
                    $r['topic_sla'],
                    $r['count_red'],
                    $r['topic_description'],
                    $r['subject'],
                    $r['user_name'],
                    $r['user_name_behalf'],
                    $r['state_code'],
                    $r['on_team_name'],
                    $r['user_name_op']
                );
            }

            //return $stat;
            return json_encode($data);
        }
        catch(mysqli_sql_exception $ex)
        {
            $data[0] = false;
            $data[1] = $ex->getMessage();
            return $data;
        }
    }


    public function getTicketPoolRedistribution($term1,$term2,$term3){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT *
                                    FROM v_af_ticket_pool
                                    WHERE state_id =$term1 AND on_team_id NOT IN ($term2) AND start_team_id IN ($term3)");

            $data = array();
            while($r = $result->fetch_array()) {
                $data['data'][] = array(
                    $r['ticket_thread_id'],
                    $r['source_name'],
                    $r['created'],
                    $r['days'],
                    $r['hours'],
                    $r['topic_sla'],
                    $r['count_red'],
                    $r['topic_description'],
                    $r['subject'],
                    $r['user_name'],
                    $r['user_name_behalf'],
                    $r['state_code'],
                    $r['on_team_name'],
                    $r['user_name_op']
                );
            }

            //return $stat;
            return json_encode($data);
        }
        catch(mysqli_sql_exception $ex)
        {
            $data[0] = false;
            $data[1] = $ex->getMessage();
            return $data;
        }
    }


    public function getTicketPoolbyUser($term1){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT *
                                    FROM v_af_ticket_pool
                                    WHERE user_id_op =$term1");

            $data = array();
            while($r = $result->fetch_array()) {
                $data['data'][] = array(
                    $r['ticket_thread_id'],
                    $r['source_name'],
                    $r['created'],
                    $r['days'],
                    $r['hours'],
                    $r['topic_sla'],
                    $r['count_red'],
                    $r['topic_description'],
                    $r['subject'],
                    $r['user_name'],
                    $r['user_name_behalf'],
                    $r['state_code'],
                    $r['on_team_name'],
                    $r['user_name_op']
                );
            }

            //return $stat;
            return json_encode($data);
        }
        catch(mysqli_sql_exception $ex)
        {
            $data[0] = false;
            $data[1] = $ex->getMessage();
            return $data;
        }
    }


    public function getTicketPoolbyUserOwn($term1){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT *
                                    FROM v_af_ticket_pool
                                    WHERE user_id =$term1");

            $data = array();
            while($r = $result->fetch_array()) {
                $data['data'][] = array(
                    $r['ticket_thread_id'],
                    $r['source_name'],
                    $r['created'],
                    $r['days'],
                    $r['hours'],
                    $r['topic_sla'],
                    $r['count_red'],
                    $r['topic_description'],
                    $r['subject'],
                    $r['user_name'],
                    $r['user_name_behalf'],
                    $r['state_code'],
                    $r['on_team_name'],
                    $r['user_name_op']
                );
            }

            //return $stat;
            return json_encode($data);
        }
        catch(mysqli_sql_exception $ex)
        {
            $data[0] = false;
            $data[1] = $ex->getMessage();
            return $data;
        }
    }


    public function getTicketDetail($term){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT * FROM v_af_ticket_detail WHERE ticket_thread_id = $term");
            //$stat[0] = true;
            //$stat[1] = $result->fetch_assoc();

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows['item'][] = $r;
            }

            //return $stat;
            return $rows;
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }


    public function getTicketContent($term1, $term2){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT * FROM $term1 WHERE ticket_thread_id = $term2");
            //$stat[0] = true;
            //$stat[1] = $result->fetch_assoc();

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows['item'][] = $r;
            }

            //return $stat;
            return $rows;
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }


    public function getTicketEvent($term){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT * FROM v_af_ticket_event WHERE ticket_thread_id = $term");
            //$stat[0] = true;
            //$stat[1] = $result->fetch_assoc();

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows['item'][] = $r;
            }

            //return $stat;
            return $rows;
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }


    public function getTicketNote($term){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT * FROM v_af_ticket_note WHERE ticket_thread_id = $term");
            //$stat[0] = true;
            //$stat[1] = $result->fetch_assoc();

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows['item'][] = $r;
            }

            //return $stat;
            return $rows;
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }


    public function getTicketAttach($ticket_thread_id){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT * FROM v_af_ticket_attach2 WHERE ticket_thread_id = $ticket_thread_id");
            //$stat[0] = true;
            //$stat[1] = $result->fetch_assoc();

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows['item'][] = $r;
            }

            //return $stat;
            return $rows;
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }

    public function setTicketAttach($ticket_thread_id, $user_id, $file_name){
        $db = $this->dblocal;
        try{
            $result = $db->query("call sp_af_ticket_attach ('$ticket_thread_id', '$user_id', '$file_name')");
            return json_encode($result);
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            echo 1;
            return $rows;
        }

    }

    public function setTicketViewing($term1,$term2){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("UPDATE af_ticket_thread SET user_id_viewing='$term1' WHERE ticket_thread_id = $term2");
            return json_encode($result);
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }


    public function setTicketViewingReset($term){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("UPDATE af_ticket_thread SET user_id_viewing=NULL WHERE user_id_viewing='$term'");
            return json_encode($result);
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();

            return $rows;
        }
    }


    public function getTicketReplyOptions($term){

        $db = $this->dblocal;
        try
        {
            $explode_term = explode(",",$term);
            // loop through the defined fields
            foreach($explode_term as $field){
                $conditions[] = "`team_id` LIKE '%" . $field . "%'";
            }
            $query = "SELECT * FROM af_ticket_reply WHERE team_id=0 OR";
            $query .= implode (' OR ', $conditions);

            //echo $query;
            $result = $db->query($query);

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows['item'][] = $r;
            }

            return $rows;
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }


    public function getTicketStateOptions(){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT * FROM af_state");
            //$stat[0] = true;
            //$stat[1] = $result->fetch_assoc();

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows['item'][] = $r;
            }

            //return $stat;
            return $rows;
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }


    public function getTeamMembers($term1, $term2){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT * FROM v_af_user_team WHERE team_id='$term1' AND user_id!='$term2'");
            //$stat[0] = true;
            //$stat[1] = $result->fetch_assoc();

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows['item'][] = $r;
            }

            //return $stat;
            return $rows;
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }


    public function getTeams($term1,$term2){

        $db = $this->dblocal;
        try
        {
            if ($term1 == 'All'){
                $result = $db->query("SELECT * FROM v_af_team WHERE team_id!='$term2'");
                //$stat[0] = true;
                //$stat[1] = $result->fetch_assoc();

                $rows = array();
                while($r = $result->fetch_assoc()) {
                    $rows['item'][] = $r;
                }

                //return $stat;
                return $rows;
            }

        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }


    public function getNoteTypes(){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT * FROM v_af_note_types WHERE is_visible=1 AND is_active=1");
            //$stat[0] = true;
            //$stat[1] = $result->fetch_assoc();

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows['item'][] = $r;
            }

            //return $stat;
            return $rows;
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }


    public function getTopicLevel($term){

        $db = $this->dblocal;
        try
        {
            if ($term == 0) { //ALL LEVEL 1
                $result = $db->query("SELECT * FROM v_af_topic WHERE topic_level=0 AND isactive=1");
                //$stat[0] = true;
                //$stat[1] = $result->fetch_assoc();

                $rows = array();
                while ($r = $result->fetch_assoc()) {
                    $rows['item'][] = $r;
                }

                //return $stat;
                return $rows;
            }
            else { //BY SELECTED LEVEL - CHILD
                    $result = $db->query("SELECT * FROM v_af_topic WHERE topic_id_child='$term' AND isactive=1");
                    //$stat[0] = true;
                    //$stat[1] = $result->fetch_assoc();

                    $rows = array();
                    while ($r = $result->fetch_assoc()) {
                        $rows['item'][] = $r;
                    }

                    //return $stat;
                    return $rows;
                }
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }



    public function getTopicLevelForm($term){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT * FROM v_af_topic_form WHERE topic_id='$term'");
            //$stat[0] = true;
            //$stat[1] = $result->fetch_assoc();

            $rows = array();
            while ($r = $result->fetch_assoc()) {
                $rows['item'][] = $r;
            }

            //return $stat;
            return $rows;
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }


    public function setTicketNew($term1,$term2,$term3,$term4,$term5,$term6, $onBehalfUserID, $subject, $motherID) {

        $db = $this->dblocal;
        try
        {
            $onBehalfUserID = $onBehalfUserID === 'NULL' ? NULL : $onBehalfUserID;
            $motherID = $motherID === 'NULL' ? NULL : $motherID;

            if (is_null($onBehalfUserID) && is_null($motherID)) {
                $result = $db->query("CALL sp_af_ticket_create ('$term1', '$term2', '$term3', '$term4', '$term5', '$term6', NULL, '$subject', NULL)");
            } else if (is_null($motherID)) {
                $result = $db->query("CALL sp_af_ticket_create ('$term1', '$term2', '$term3', '$term4', '$term5', '$term6', $onBehalfUserID, '$subject', NULL)");
            } else if (is_null($onBehalfUserID)) {
                $result = $db->query("CALL sp_af_ticket_create ('$term1', '$term2', '$term3', '$term4', '$term5', '$term6', NULL, '$subject', '$motherID')");
            } else {
                $result = $db->query("CALL sp_af_ticket_create ('$term1', '$term2', '$term3', '$term4', '$term5', '$term6', $onBehalfUserID, '$subject', '$motherID')");
            }

            return json_encode($result);
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            echo 1;
            return $rows;
        }
    }


    public function setTicketNewData($term1,$term2,$term3){

        $db = $this->dblocal;
        try
        {
            //CREATE DYNAMIC QUERY
            $__query_fields = '';
            $__query_values = '';
            foreach($term3 as $key => $value) {
                $__query_fields .= $key.",";
                $__query_values .= "'".$value."'".",";
            }
            $__query_fields = substr($__query_fields, 0, -1); //remove last ','
            $__query_values = substr($__query_values, 0, -1); //remove last ','

            $result = $db->query("INSERT INTO $term2 (ticket_thread_id,$__query_fields) VALUES ($term1,$__query_values);");
            return json_encode($result);
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();

            return $rows;
        }
    }


    public function getMaxTicketThreadId(){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT MAX(ticket_thread_id) AS max_ticket_thread_id FROM af_ticket_thread");
            //$stat[0] = true;
            //$stat[1] = $result->fetch_assoc();

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows['item'][] = $r;
            }

            //return $stat;
            return $rows;
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }


    public function setTicketState($term1,$term2,$term3,$term4,$term5){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("CALL sp_af_ticket_change_state ('$term1', '$term2', '$term3', '$term4', '$term5')");
            return json_encode($result);
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            echo 1;
            return $rows;
        }
    }


    public function setTicketOwnOp($term1,$term2,$term3){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("CALL sp_af_ticket_change_operator_own ('$term1', '$term2', '$term3')");
            return json_encode($result);
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            echo 1;
            return $rows;
        }
    }


    public function setTicketOp($term1,$term2,$term3,$term4,$term5){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("CALL sp_af_ticket_change_operator ('$term1', '$term2', '$term3', '$term4', '$term5')");
            return json_encode($result);
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            echo 1;
            return $rows;
        }
    }


    public function setTicketRedistribution($term1,$term2,$term3,$term4,$term5){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("CALL sp_af_ticket_redistribute ('$term1', '$term2', '$term3', '$term4', '$term5')");
            return json_encode($result);
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            echo 1;
            return $rows;
        }
    }


    public function setTicketNoteNew($term1,$term2,$term3,$term4, $time_consumed = 0){

        $db = $this->dblocal;
        try
        {

            $result = $db->query("CALL sp_af_ticket_note ('$term1', '$term2', '$term3', '$term4', $time_consumed)");
            return json_encode($result);
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            echo 1;
            return $rows;
        }
    }



    public function get_x_system($type){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT system_1 FROM v_af_x_ref_system WHERE type='$type' ORDER BY system_1 ASC;");
            //$stat[0] = true;
            //$stat[1] = $result->fetch_assoc();

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows['item'][] = $r;
            }

            //return $stat;
            return $rows;
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }

    public function get_x_module() {
        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT module_1 from v_af_x_ref_module ORDER BY module_1 ASC;");
            //$stat[0] = true;
            //$stat[1] = $result->fetch_assoc();

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows['item'][] = $r;
            }

            //return $stat;
            return $rows;
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }


    public function get_x_company(){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT company FROM v_af_x_ref_company ORDER BY company ASC;");
            //$stat[0] = true;
            //$stat[1] = $result->fetch_assoc();

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows['item'][] = $r;
            }

            //return $stat;
            return $rows;
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }

    public function getTeamData($ticket_thread_id){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT * FROM v_af_ta_team WHERE ticket_thread_id = $ticket_thread_id");
            //$stat[0] = true;
            //$stat[1] = $result->fetch_assoc();

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows['item'][] = $r;
            }

            //return $stat;
            return $rows;
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }


    public function getUserData($ticket_thread_id){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT * FROM v_af_ta_user WHERE ticket_thread_id = $ticket_thread_id");
            //$stat[0] = true;
            //$stat[1] = $result->fetch_assoc();

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows['item'][] = $r;
            }

            //return $stat;
            return $rows;
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }



    public function getMonthData($ticket_thread_id){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT * FROM v_af_ta_month WHERE ticket_thread_id = $ticket_thread_id");
            //$stat[0] = true;
            //$stat[1] = $result->fetch_assoc();

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows['item'][] = $r;
            }

            //return $stat;
            return $rows;
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }

    public function getWeekData($ticket_thread_id){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT * FROM v_af_ta_month WHERE ticket_thread_id = $ticket_thread_id");
            //$stat[0] = true;
            //$stat[1] = $result->fetch_assoc();

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows['item'][] = $r;
            }

            //return $stat;
            return $rows;
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }

    public function getDayData($ticket_thread_id){

        $db = $this->dblocal;
        try
        {
            $result = $db->query("SELECT * FROM v_af_ta_day WHERE ticket_thread_id = $ticket_thread_id");
            //$stat[0] = true;
            //$stat[1] = $result->fetch_assoc();

            $rows = array();
            while($r = $result->fetch_assoc()) {
                $rows['item'][] = $r;
            }

            //return $stat;
            return $rows;
        }
        catch(mysqli_sql_exception $ex)
        {
            $rows[0] = false;
            $rows[1] = $ex->getMessage();
            return $rows;
        }
    }
}




