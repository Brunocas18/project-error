<?php
    if (empty($_GET['state']) && empty($_GET['ticket_id'])) {
        header("Location: ?state=1");
    }
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    ### SYS
    ini_set('session.gc_maxlifetime', 43200); // 12 horas
    session_set_cookie_params(43200); // 12 horas
    session_start();

    require_once ("./route/sys/dbconn.php");        //DB CONN
    require_once ("./route/sys/sql.php");           //SQL
    require_once ('./route/sys/global_vars.php');   //GLOBAL VARS
    require_once ('./route/sys/global_functions.php');   //GLOBAL FUNCTIONS

    ### CHECK LOGIN
    
    if (CheckLogin($GLOBALS['USER_AD_DOMAIN'], $GLOBALS['USER_AD'])) {

        $sql = new sql(); 
        $_SESSION['USER_ID'] = $GLOBALS['USER_ID'];
        $_SESSION['USER'] = $sql->getUserDetails($GLOBALS['USER_ID']);
        $_SESSION['USERS'] = $sql->getAllUsers();
        $_SESSION['USER_TEAM_ID'] = $GLOBALS['USER_TEAM_ID'];
        $_SESSION['USER_IP'] = $GLOBALS['USER_IP'];
        include ('./view/html/bo/main.php');
    }

