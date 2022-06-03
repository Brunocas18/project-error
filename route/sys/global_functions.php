<?php

    function insert($form, $arr, $company, $motherID) {
        $queryFields = $queryValues = $queryField = '';
        $numberOfFields = 0;
        foreach($arr as $key => $value) {
            if (is_array($value)) {
                $queryField .= $key;
                foreach ($value as $key => $v) {
                    $queryValue = "'$v'";
                    $sql = "INSERT INTO {$form['form_data']} ($queryField, empresa, sistema, mother_id) VALUES ($queryValue, '$company', 'Primavera', $motherID);";
                    insertFormData($sql);
                }
            } else if (is_string($key) && !is_array($value)) {
                if ($key !== 'empresa') {
                    $queryFields .= $key . ',';
                    $queryValues .= "'$value'" . ',';
                }
            } else if(is_int($key)) {
                $queryFields .= $value . ',';
                $queryValues .= "'$value'" . ',';
            }
            if (++$numberOfFields == count($arr) && !is_array($value)) {
                $queryValues = substr($queryValues, 0, -1); // extra ' and beginning
                $queryFields = substr($queryFields, 0, -1); // extra ' and beginning
                $sql = "INSERT INTO {$form['form_data']} ($queryFields, empresa, mother_id) VALUES ($queryValues, '$company', $motherID);";
                insertFormData($sql);
            }
        }
    }

    function checkSisters($threadID, $isMother): ?array
    {
        $sql = new sql();
        return $sql->checkSisters($threadID, $isMother) ?? NULL;
    }

    function insertFormData($query): ?string
    {
        $sql = new sql();
        return $sql->insertFormData($query) ?? NULL;
    }

    function getTopicForm($topicID): ?array
    {
        $sql = new sql();
        return $sql->getTopicForm($topicID) ?? NULL;
    }

    function checkModulesPrimavera(): ?array
    {
        $sql = new sql();
        return $sql->checkModulesPrimavera() ?? NULL;
    }

    function getTicketDetail($threadID): ?array
    {
        $sql = new sql();
        return $sql->getTicketDetail($threadID) ?? NULL;
    }

    function checkIfMultiTicket($topicID, $isChild = false): ?array
    {
        $sql = new sql();
        return $sql->checkIfMultiTicket($topicID, $isChild) ?? NULL;
    }

    function getBehalfEmailByThreadID($threadID): ?array
    {
        $sql = new sql();
        return $sql->getBehalfEmailByThreadID($threadID) ?? NULL;
    }

    function getChildByGrandAndParent($parent): ?array
    {
        $sql = new sql();
        return $sql->getChildByGrandAndParent($parent) ?? NULL;
    }

    function getChildByGrandchildID($grandID): ?array {
        $sql = new sql();
        return $sql->getChildByGrandchildID($grandID) ?? NULL;
    }

    function ticketPoolSearch($post = NULL)
    {
        $sql = new sql();

        return $sql->ticketPoolSearch($post) ?? false;
    }

    function getAllSources(): ?array
    {
        $sql = new sql();

        return $sql->getAllSources() ?? NULL;
    }

    function getAllUsers(): ?array
    {
        // Initialize sql class instance
        $sql = new sql();

        return $sql->getAllUsers() ?? NULL;
    }

    function getAllColumns(): ?array
    {
        $sql = new sql();
        return $sql->getAllColumns() ?? NULL;
    }

    function getFormColumnsByNumber($number): ?array
    {
        $sql = new sql();
        return $sql->getFormColumnsByNumber($number) ?? NULL;
    }

    function getFormByID($id): ?array
    {
        $sql = new sql();
        return $sql->getFormByID($id) ?? NULL;
    }

    function getTopicoAbertura(): ?array
    {
        $sql = new sql();
        return $sql->getTopicoAbertura() ?? NULL;
    }

    function getAllCompanies(): ?array
    {
        $sql = new sql();
        return $sql->getAllCompanies() ?? NULL;
    }

    function getAllTeams(): ?array
    {
        // Initialize sql class instance
        $sql = new sql();
        return $sql->getAllTeams() ?? NULL;
    }

    function getAllStates(): ?array
    {
        $sql = new sql();
        return $sql->getAllStates() ?? NULL;
    }

    /**
     * GET THE TEAM DESCRIPTION GIVEN A THREAD ID
     * @param $thread_id THE ID OF THE THREAD
     * @return array|null AN ARRAY WITH THE TEAM NAME OR NULL IF THE
     */

    function getTeamNameByThreadID($thread_id): ?array {
        // Initialize sql class instance
        $sql = new sql();

        return $sql->getTeamNameByThreadID($thread_id) ?? NULL;
    }

    /**
     * GET TICKET ID BY THE TICKET THREAD ID
     * @param $threadID THE ID OF THE THREAD
     * @return array|null RETURN THE DETAILS OF THE TICKET OR NULL
     */

    function getTicketByThreadID($threadID): ?array {

        // Initialize sql class instance
        $sql = new sql();

        return $sql->getTicketByThreadID($threadID) ?? NULL;
    }

    /**
     * GET TEAM USERS BY THE ID OF THE TEAM
     * @param $teamID THE ID OF THE TEAM
     * @return array|null RETURN AN ARRAY WITH THE USERS OR NULL
     */

    function getTeamUsersByTeamID($teamID): ?array {

        // Initialize sql class instance
        $sql = new sql();

        return $sql->getTeamUsersByTeamID($teamID) ?? NULL;
    }

    /**
     * GET USERS BY THE THREAD ID
     * @param $threadID THE ID
     * @return array|null RETURN THE ARRAY IF THERE'S USERS OR NULL IF IT DOESN'T ENCOUNTER ANY TEAM WITH MEMBERS
     */
    function getTeamUsersByThreadID($threadID): ?array {

        // Initialize sql class instance
        $sql = new sql();

        return $sql->getTeamUsersByThreadID($threadID) ?? NULL;
    }

    /**
     * INSERT DETAILS INTO EMAIL POOL(SEND EMAIL CHOSEN OPERATOR), A JOB WILL RUN AND THE EMAIL WILL BE SENT
     * @param string $destiny Email destination
     * @param string $origin Email origin
     * @param string $subject Email Subject
     * @param string $body The body/motive for the operator
     * @param string $ticket_thread_id
     * @return array|false|string
     */

    function insertIntoEmailPool(string $destiny, string $origin, string $subject, string $body, string $ticket_thread_id, string $note_type) {

        // Initialize sql class instance
        $sql = new sql();

        // if error null is returned to work with isset
        return $sql->insertIntoEmailPool($destiny, $origin, $subject . " - DEV", $body, $ticket_thread_id, $note_type) ?? NULL;
    }

    /**
     * Get user details from database
     * @param $userID The user id
     * @return array|null User details
     */

    function getUserDetails($userID = NULL): ?array {

        // Initialize sql class instance
        $sql = new sql();

        // Get operator details, user details is in $_SESSION or false if userid does not exist.
        return $sql->getUserDetails($userID) ?? NULL;
    }

    ### CHECK LOGIN
    function CheckLogin($param1, $param2)
    {
        $sql = new sql();
        $instanceResult = $sql->getLogin($param1, $param2);
        $instanceResult_User = json_decode($instanceResult);

        //echo $instanceResult_User[0]->user_ad;
        if (!isset($instanceResult_User[0]))
        {
            http_response_code(403);
            die(); //todo - mandar para /fo
        }
        else {

            //SET GLOBAL VARS
            $GLOBALS['USER_ID'] = $instanceResult_User[0]->user_id;
            $_SESSION['USER_ID'] = $instanceResult_User[0]->user_id; //for external scripts
            $GLOBALS['USER_NAME'] = $instanceResult_User[0]->user_name;
            $GLOBALS['USER_PROFILE_ID'] = $instanceResult_User[0]->user_profile_id;
            $GLOBALS['USER_DEPARTMENT_ID'] = $instanceResult_User[0]->department_id;
            //$GLOBALS['USER_TEAM_ID'] = $instanceResult_User[0]->team_id;
            $GLOBALS['USER_TEAM_NAME'] = $instanceResult_User[0]->team_name;

            $GLOBALS['USER_TEAM_ID'] ="";
            foreach ($instanceResult_User as &$value) {
                if ($GLOBALS['USER_TEAM_ID']==""){
                    $GLOBALS['USER_TEAM_ID'] .= $value->team_id;
                } else $GLOBALS['USER_TEAM_ID'] .= ",".$value->team_id;

            }

            //print_r($instanceResult_User); exit;
            return true;
        }
    }


    ### GET TICKET COUNT
    function TicketCount($team_id)
    {
        $sql = new sql();
        $instanceResult = $sql->getTicketCount($team_id);
        $instanceResult_Ticket_Count = json_decode($instanceResult);

        $size = sizeof($instanceResult_Ticket_Count);
        for ($i = 0; $i < $size; $i++) {
            $result_key=$instanceResult_Ticket_Count[$i]->state_id;
            $result[$result_key] = $instanceResult_Ticket_Count[$i]->count;
        }

        //echo $instanceResult_User[0]->user_ad;
        if (!isset($instanceResult_Ticket_Count[0]))
        {

        }
        else {
            //print_r($instanceResult_Ticket_Count); exit;
            return $result;
        }
    }


    ### GET TICKET COUNT OPERATOR
    function TicketCountOp($user_id)
    {
        $sql = new sql();
        $instanceResult = $sql->getTicketCountOp($user_id);
        $instanceResult_Ticket_Count = json_decode($instanceResult);


        //echo $instanceResult_User[0]->user_ad;
        if (!isset($instanceResult_Ticket_Count[0]))
        {
            //void
        }
        else {
            //print_r($instanceResult_Ticket_Count); exit;
            return $instanceResult_Ticket_Count;
        }
    }


    ### GET TICKET COUNT OWN USER
    function TicketCountOwn($user_id)
    {
        $sql = new sql();
        $instanceResult = $sql->getTicketCountOwn($user_id);
        $instanceResult_Ticket_Count = json_decode($instanceResult);


        //echo $instanceResult_User[0]->user_ad;
        if (!isset($instanceResult_Ticket_Count[0]))
        {
            //void
        }
        else {
            //print_r($instanceResult_Ticket_Count); exit;
            return $instanceResult_Ticket_Count;
        }
    }


    ### GET TICKET POOL BY STATE
    function TicketPoolbyState($state_id)
    {
        $sql = new sql();
        $instanceResult = $sql->getTicketPoolbyState($state_id);

        //echo $instanceResult_User[0]->user_ad;
        if (!isset($instanceResult[0]))
        {
            //void
        }
        else {
            //print_r($instanceResult_Ticket_Count); exit;
            return $instanceResult;
        }
    }


    ### GET TICKET POOL BY STATE AND TEAMS
    function TicketPoolbyStateAndTeams($state_id,$teams_id)
    {
        $sql = new sql();
        $instanceResult = $sql->getTicketPoolbyStateAndTeams($state_id,$teams_id);

        //echo $instanceResult_User[0]->user_ad;
        if (!isset($instanceResult[0]))
        {
            //void
        }
        else {
            //print_r($instanceResult_Ticket_Count); exit;
            return $instanceResult;
        }
    }


    ### GET TICKET POOL REDISTRIBUTION
    function TicketPoolRedistribution($state_id,$teams_id,$start_team_id)
    {
        $sql = new sql();
        $instanceResult = $sql->getTicketPoolRedistribution($state_id,$teams_id,$start_team_id);

        //echo $instanceResult_User[0]->user_ad;
        if (!isset($instanceResult[0]))
        {
            //void
        }
        else {
            //print_r($instanceResult_Ticket_Count); exit;
            return $instanceResult;
        }
    }


    ### GET TICKET POOL BY USER
    function TicketPoolbyUser($user_id)
    {
        $sql = new sql();
        $instanceResult = $sql->getTicketPoolbyUser($user_id);

        //echo $instanceResult_User[0]->user_ad;
        if (!isset($instanceResult[0]))
        {
            //void
        }
        else {
            //print_r($instanceResult_Ticket_Count); exit;
            return $instanceResult;
        }
    }


    ### GET TICKET POOL BY USER - OWN TICKETS
    function TicketPoolbyUserOwn($user_id)
    {
        $sql = new sql();
        $instanceResult = $sql->getTicketPoolbyUserOwn($user_id);

        //echo $instanceResult_User[0]->user_ad;
        if (!isset($instanceResult[0]))
        {
            //void
        }
        else {
            //print_r($instanceResult_Ticket_Count); exit;
            return $instanceResult;
        }
    }


    ### GET TICKET DETAIL BY ID
    function TicketDetail($ticket_id)
    {
        $sql = new sql();
        $instanceResult = $sql->getTicketDetail($ticket_id);

        //echo $instanceResult_User[0]->user_ad;
        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }


    ### GET TICKET CONTENT
    function TicketContent($x_form, $ticket_id)
    {
        $sql = new sql();
        $instanceResult = $sql->getTicketContent($x_form, $ticket_id);

        //echo $instanceResult_User[0]->user_ad;
        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }


    ### GET TICKET EVENT
    function TicketEvent($ticket_id)
    {
        $sql = new sql();
        $instanceResult = $sql->getTicketEvent($ticket_id);

        //echo $instanceResult_User[0]->user_ad;
        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }


    ### GET TICKET NOTE
    function TicketNote($ticket_id)
    {
        $sql = new sql();
        $instanceResult = $sql->getTicketNote($ticket_id);

        //echo $instanceResult_User[0]->user_ad;
        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }


    ### GET TICKET ATTACH
    function TicketAttach($ticket_thread_id){
        $sql = new sql();
        $instanceResult = $sql->getTicketAttach($ticket_thread_id);

        //echo $instanceResult_User[0]->user_ad;
        if (!isset($instanceResult)) {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }

    ### SET TICKET ATTACH
    function TicketAttachUpdate($ticket_thread_id, $user_id, $file_name){
        $sql = new sql();
        $instanceResult = $sql->setTicketAttach($ticket_thread_id, $user_id, $file_name);

        //echo $instanceResult_User[0]->user_id;
        if (!isset($instanceResult)){
            //void
        }
        else{
            //print_r($instanceResult);
            return $instanceResult;
        }

    }


    ### SET TICKET VIEWING
    function TicketViewing($user_id, $ticket_id)
    {
        $sql = new sql();
        $instanceResult = $sql->setTicketViewing($user_id, $ticket_id);

        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }


    ### SET TICKET VIEWING - RESET
    function TicketViewingReset($user_id)
    {
        $sql = new sql();
        $instanceResult = $sql->setTicketViewingReset($user_id);

        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }



    ### GET REF TICKET REPLY
    function TicketReplyOptions($team_id)
    {
        $sql = new sql();
        $instanceResult = $sql->getTicketReplyOptions($team_id);

        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }


    ### GET REF TICKET REPLY
    function TicketStateOptions()
    {
        $sql = new sql();
        $instanceResult = $sql->getTicketStateOptions();

        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }


    ### GET TEAM MEMBERS
    function TeamMembers($team_id, $user_id)
    {
        $sql = new sql();
        $instanceResult = $sql->getTeamMembers($team_id, $user_id);

        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }


    ### GET TEAMS
    function Teams($term1,$term2)
    {
        $sql = new sql();
        $instanceResult = $sql->getTeams($term1,$term2);

        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }


    ### GET NOTE TYPE
    function NoteTypes()
    {
        $sql = new sql();
        $instanceResult = $sql->getNoteTypes();

        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }


    ### TOPICS - LEVEL 1
    function TopicLevel($topic_id)
    {
        $sql = new sql();
        $instanceResult = $sql->getTopicLevel($topic_id);

        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }


    ### TOPICS - FORM
    function TopicLevelForm($topic_id)
    {
        $sql = new sql();
        $instanceResult = $sql->getTopicLevelForm($topic_id);

        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }


    ### SET TICKET NEW - CREATE
    function TicketNew($source_id,$topic_id,$user_id,$ticket_details,$ticket_flags,$ip_address, $onBehalfUserID, $subject, $fatherID)
    {
        $sql = new sql();
        $instanceResult = $sql->setTicketNew($source_id,$topic_id,$user_id,$ticket_details,$ticket_flags,$ip_address, $onBehalfUserID, $subject, $fatherID);

        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }


    ### SET TICKET NEW - FILL DATA
    function TicketNewData($ticket_thread_id,$form_table,$form_data)
    {
        $sql = new sql();
        $instanceResult = $sql->setTicketNewData($ticket_thread_id,$form_table,$form_data);

        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }


    ### GET MAX TICKET THREAD ID
    function MaxTicketThreadId()
    {
        $sql = new sql();
        $instanceResult = $sql->getMaxTicketThreadId();

        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }


    ### SHOW ALERT
    function showAlert($alert_id)
    {
        switch ($alert_id) {
            case 100:
                echo '<div class="alert alert-success" role="alert" id="alert">
                            Solicitação criada com sucesso!
                        </div>';
                break;
            case 101:
                echo '<div class="alert alert-success" role="alert" id="alert">
                            Alteração de estado efetuada com sucesso!
                        </div>';
                break;
            case 102:
                echo '<div class="alert alert-success" role="alert" id="alert">
                            Solicitação atribuida com sucesso!
                        </div>';
                break;
            case 103:
                echo '<div class="alert alert-success" role="alert" id="alert">
                            Solicitação redistribuida com sucesso!
                        </div>';
                break;
            case 104:
                echo '<div class="alert alert-success" role="alert" id="alert">
                            Nota adicionada com sucesso!
                        </div>';
                break;
            case 105:
                echo '<div class="alert alert-success" role="alert" id="alert">
                            Anexo adicionado com sucesso!
                        </div>';
                break;
            case 900:
                echo '<div class="alert alert-danger" role="alert" id="alert">
                            Ocorreu um erro!
                        </div>';
                break;
        }
        //todo - alert ref table
    }


    ### SET TICKET STATE
    function TicketState($user_id,$ticket_thread_id,$state_id_z,$ticket_reply,$ip_address)
    {
        $sql = new sql();
        $instanceResult = $sql->setTicketState($user_id,$ticket_thread_id,$state_id_z,$ticket_reply,$ip_address);

        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }


    ### SET TICKET OWN OPERATOR
    function TicketOwnOp($user_id,$ticket_thread_id,$ip_address)
    {
        $sql = new sql();
        $instanceResult = $sql->setTicketOwnOp($user_id,$ticket_thread_id,$ip_address);

        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }


    ### SET TICKET OTHER OPERATOR
    function TicketOp($user_id,$user_id_op,$ticket_thread_id,$ip_address,$note)
    {
        $sql = new sql();
        $instanceResult = $sql->setTicketOp($user_id,$user_id_op,$ticket_thread_id,$ip_address,$note);

        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }


    ### SET TICKET REDISTRIBUTION
    function TicketRedistribution($user_id,$ticket_thread_id,$team_id_z,$ip_address,$note)
    {
        $sql = new sql();
        $instanceResult = $sql->setTicketRedistribution($user_id,$ticket_thread_id,$team_id_z,$ip_address,$note);

        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }


    ### SET TICKET NOTE
    function TicketNoteNew($user_id,$ticket_thread_id,$note_id,$note, $time_consumed)
    {
        $sql = new sql();
        $instanceResult = $sql->setTicketNoteNew($user_id,$ticket_thread_id,$note_id,$note, $time_consumed);

        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }

    ### CUSTOM FORMS - GET MODULES
    function X_REF_MODULE()
    {
        $sql = new sql();
        $instanceResult = $sql->get_x_module();
        //echo $instanceResult_User[0]->user_ad;
        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }

    ### CUSTOM FORMS - GET SYSTEMS
    function X_REF_SYSTEM($type)
    {
        $sql = new sql();
        $instanceResult = $sql->get_x_system($type);

        //echo $instanceResult_User[0]->user_ad;
        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }


    ### CUSTOM FORMS - GET SYSTEMS
    function X_REF_COMPANY()
    {
        $sql = new sql();
        $instanceResult = $sql->get_x_company();

        //echo $instanceResult_User[0]->user_ad;
        if (!isset($instanceResult))
        {
            //void
        }
        else {
            //print_r($instanceResult);
            return $instanceResult;
        }
    }

function TeamData($ticket_thread_id){
    $sql = new sql();
    $instanceResult = $sql->getTeamData($ticket_thread_id);

    //echo $instanceResult_User[0]->user_ad;
    if (!isset($instanceResult)) {
        //void
    }
    else {
        //print_r($instanceResult);
        return $instanceResult;
    }
}

function UserData($ticket_thread_id){
    $sql = new sql();
    $instanceResult = $sql->getUserData($ticket_thread_id);

    //echo $instanceResult_User[0]->user_ad;
    if (!isset($instanceResult)) {
        //void
    }
    else {
        //print_r($instanceResult);
        return $instanceResult;
    }
}

function MonthData($ticket_thread_id){
    $sql = new sql();
    $instanceResult = $sql->getMonthData($ticket_thread_id);

    //echo $instanceResult_User[0]->user_ad;
    if (!isset($instanceResult)) {
        //void
    }
    else {
        //print_r($instanceResult);
        return $instanceResult;
    }
}

function WeekData($ticket_thread_id){
    $sql = new sql();
    $instanceResult = $sql->getWeekData($ticket_thread_id);

    //echo $instanceResult_User[0]->user_ad;
    if (!isset($instanceResult)) {
        //void
    }
    else {
        //print_r($instanceResult);
        return $instanceResult;
    }
}

function DayData($ticket_thread_id){
    $sql = new sql();
    $instanceResult = $sql->getDayData($ticket_thread_id);

    //echo $instanceResult_User[0]->user_ad;
    if (!isset($instanceResult)) {
        //void
    }
    else {
        //print_r($instanceResult);
        return $instanceResult;
    }
}