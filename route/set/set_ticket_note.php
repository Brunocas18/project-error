<?php
### SET TICKET NOTE

session_start();
require_once ("../sys/dbconn.php");             //DB CONN
require_once ("../sys/sql.php");                //SQL
require_once ('../sys/global_vars.php');        //GLOBAL VARS
require_once ('../sys/global_functions.php');   //GLOBAL FUNCTIONS


try {

    ### SET LOCAL VARS
    $__ticket_thread_id = $_SESSION['TICKET_ID'];

    if (empty($_POST['form_time_consumed'])) {
        $_POST['form_time_consumed'] = 0;
    }

    if (isset($_POST['form_modal_notas_tipo_nota']) && $_POST['form_modal_notas_tipo_nota']!='')  {
        $__note = str_replace("'", "", $_POST['form_modal_notas_nota']); //clean text

        //SET TICKET STATE - OTHER OPERATOR
        $ticket_note = TicketNoteNew($_SESSION['USER_ID'], $__ticket_thread_id, $_POST['form_modal_notas_tipo_nota'],$__note, $_POST['form_time_consumed']);

        $user = $_SESSION['USER'][0];

        $users = getTeamUsersByThreadID($__ticket_thread_id) ?? false;

        if ($users !== false && empty($_POST['form_time_consumed'])) {
            $emails = implode(',', array_column($users, 'user_email'));
            insertIntoEmailPool($emails,
                $user['user_email'],
                "Nota adicionada por {$user['user_name']}",
                $__note,
                $__ticket_thread_id,
                'nota');
        } else if ($users === false) {
            $team = getTeamNameByThreadID($__ticket_thread_id)[0]['team_description'];
            insertIntoEmailPool('pedrosousa@smartdev-group.com',
                'NULL',
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
    $_SESSION['RETURN_CODE'] = 104;

} catch (exception $e) {

    $_SESSION['RETURN_CODE'] = 900;

}


//RETURN
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;

