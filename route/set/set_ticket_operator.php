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

    if (isset($_POST['form_modal_atribuir_operador']) && $_POST['form_modal_atribuir_operador']!='')
    {
        $__note = str_replace("'", "", $_POST['form_modal_atribuir_motivo']); //clean text

        //SET TICKET STATE - OTHER OPERATOR
        $ticket_operator = TicketOp($_SESSION['USER_ID'], $_POST['form_modal_atribuir_operador'], $__ticket_thread_id, $_SESSION['USER_IP'],$__note);

        // the solicitor name and the solicitor email
        ['user_name' => $user, 'user_email' => $originEmail] = $_SESSION['USER'][0];

        // get the operator and the operator email
        ['user_name' => $operator, 'user_email' => $operatorEmail] = getUserDetails($_POST['form_modal_atribuir_operador'])[0];

        insertIntoEmailPool($operatorEmail, $originEmail, "$user adicinou-o como operador", $__note, $__ticket_thread_id, 'operador');
    }
    else {

        //SET TICKET STATE - OWN OPERATOR
        $ticket_operator = TicketOwnOp($_SESSION['USER_ID'], $__ticket_thread_id, $_SESSION['USER_IP']);

    }

    //SET RETURN CODE
    $_SESSION['RETURN_CODE'] = 102;

} catch (exception $e) {
    $_SESSION['RETURN_CODE'] = 900;
}


//RETURN
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;