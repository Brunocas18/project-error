<?php
### SET TICKET OPERATOR

session_start();
require_once ("../sys/dbconn.php");             //DB CONN
require_once ("../sys/sql.php");                //SQL
require_once ('../sys/global_vars.php');        //GLOBAL VARS
require_once ('../sys/global_functions.php');   //GLOBAL FUNCTIONS


try {

    ### SET LOCAL VARS
    $__ticket_thread_id = $_SESSION['TICKET_ID'];

    if (isset($_POST['form_modal_redistribuir_equipa']) && $_POST['form_modal_redistribuir_equipa']!='')  {

        $__note = str_replace("'", "", $_POST['form_modal_redistribuir_motivo']); //clean text
        //SET TICKET STATE - OTHER OPERATOR
        $ticket_redistribution = TicketRedistribution($_SESSION['USER_ID'], $_POST['form_modal_redistribuir_equipa'], $__ticket_thread_id, $_SESSION['USER_IP'],$__note);

        $teamID = $_POST['form_modal_redistribuir_equipa'];
        $users = getTeamUsersByTeamID($teamID) ?? false;

        ['user_name' => $userName, 'user_email' => $originEmail] = $_SESSION['USER'][0];

        if ($users !== false) {

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
            // the solicitor name and the solicitor email
            insertIntoEmailPool($emails, $originEmail, "Redistribuição de equipa por $userName", $__note, $__ticket_thread_id, 'equipa');
        } else {
            $team = getTeamNameByThreadID($__ticket_thread_id)[0]['team_description'];
            insertIntoEmailPool('NULL',
                $originEmail,
                "Sem utilizadores na equipa $team",
                "NULL",
                $__ticket_thread_id, 'erro');
        }

    } else {
        $_SESSION['RETURN_CODE'] = 900;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    //SET RETURN CODE
    $_SESSION['RETURN_CODE'] = 103;

} catch (exception $e) {

    $_SESSION['RETURN_CODE'] = 900;

}


//RETURN
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
