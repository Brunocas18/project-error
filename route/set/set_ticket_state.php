<?php
### SET TICKET STATE

session_start();
require_once ("../sys/dbconn.php");             //DB CONN
require_once ("../sys/sql.php");                //SQL
require_once ('../sys/global_vars.php');        //GLOBAL VARS
require_once ('../sys/global_functions.php');   //GLOBAL FUNCTIONS


try {

    if (isset($_POST['form_modal_responder_estado']) && $_POST['form_modal_responder_estado']!='')
    {
        if ($_POST['form_modal_responder_estado'] === '3') {

            ['topic_id' => $topicID, 'ticket_details' => $ticketDetails, 'ticket_thread_id' => $motherID,
            'subject' => $subject, 'on_behalf_user_id' => $onBehalf] = $_SESSION["ticket_detail"];
            ['user_email' => $userMail, 'user_name' => $userName] = $_SESSION['USER'][0];

            $isMultiTicket = checkIfMultiTicket($topicID) ?? false;
            if ($isMultiTicket !== false) {
                foreach($isMultiTicket as $ticket) {
                    $form = getTopicForm($ticket['topic_child'])[0]['form_data'];
                    TicketNew(1, $ticket['topic_child'], $_SESSION['USER_ID'], $ticketDetails, NULL, $_SESSION['USER_IP'], $onBehalf, $subject, $motherID);
                    $threadID = MaxTicketThreadId()['item'][0]['max_ticket_thread_id'];
                    $users = getTeamUsersByThreadID($threadID);
                    $emails = implode(',', array_column($users, 'user_email'));
                    insertIntoEmailPool($emails,
                        $userMail,
                        "Nova solicitação criada por $userName",
                        "$ticketDetails",
                        $threadID,
                        'novo');
                    $sql = "UPDATE $form SET ticket_thread_id = $threadID WHERE mother_id = $motherID";
                    insertFormData($sql);

                }
                $motherForm = getTopicForm($isMultiTicket[0]['topic_parent'])[0]['form_data'];
                $sql = "UPDATE $motherForm SET ticket_thread_id = $motherID WHERE mother_id = $motherID";
                insertFormData($sql);
            }
        }
        ### SET LOCAL VARS
        $__ticket_thread_id = $_SESSION['TICKET_ID'];
        $__new_state = $_POST['form_modal_responder_estado'];
        $__new_state_reply = str_replace("'", "", $_POST['form_modal_responder_resposta']); //clean text

        //SET TICKET STATE
        $ticket_state = TicketState($_SESSION['USER_ID'], $__ticket_thread_id, $__new_state, $__new_state_reply, $_SESSION['USER_IP']);

        $user = $_SESSION['USER'][0];
        $users = getTeamUsersByThreadID($__ticket_thread_id);
        $emails = implode(',', array_column($users, 'user_email'));
        $onBehalf = getBehalfEmailByThreadID($__ticket_thread_id)[0]['user_name_behalf_email'] ?? 'no';

        if (preg_match("/\b$onBehalf\b/", $emails)) {
            $emails = preg_replace("/\b(\,|\,\,)*$onBehalf(\,|\,\,)*\b/", ',', $emails);
            if ($emails[0] === ',') {
                $emails[0] = ' ';
            };
            $emails .= ",$onBehalf,on_behalf";
            $emails = preg_replace('/,,+/', ',', $emails);
            $emails = trim($emails);
        } else if ($onBehalf !== 'no'){
            $emails .= ",$onBehalf,on_behalf";
        }

        insertIntoEmailPool($emails,
            $user['user_email'],
            "Estado mudado por {$user['user_name']}",
            "$__new_state_reply",
            $__ticket_thread_id, 'estado');

    } else {
        $_SESSION['RETURN_CODE'] = 900;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    //SET RETURN CODE
    $_SESSION['RETURN_CODE'] = 101;

} catch (exception $e) {

    $_SESSION['RETURN_CODE'] = 900;

}


//RETURN
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;