<?php

    session_start();
    require_once ("../sys/dbconn.php");             //DB CONN
    require_once ("../sys/sql.php");                //SQL
    require_once ('../sys/global_vars.php');        //GLOBAL VARS
    require_once ('../sys/global_functions.php');   //GLOBAL FUNCTIONS

    $filetype = "";
    $store = "../../store/".$_POST['h_ticket_id']."//";

    if (!empty($_FILES['validatedCustomFile']) && $_FILES['validatedCustomFile']['error'] !== 4) {
        $filetype = strtolower(explode('.', $_FILES['validatedCustomFile']['name'])[1]);
    }

    if (!empty($_FILES['validatedCustomFile']) && $_FILES['validatedCustomFile']['error'] !== 4) {
        $filetype = strtolower(pathinfo($_FILES['validatedCustomFile']['name'])['extension']);
    }

    if(!empty($_FILES['validatedCustomFile']) &&
        $_FILES['validatedCustomFile']['error'] !== 4 &&
        $filetype !== 'js' &&
        $filetype !== 'php'  &&
        $filetype !== 'html' &&
        $filetype !== 'bat')
    {
        mkdir("../../store/".$_POST['h_ticket_id']);
        $filename = preg_replace("/\s+/", "", basename($_FILES["validatedCustomFile"]["name"]));
        $res_file = move_uploaded_file($_FILES["validatedCustomFile"]["tmp_name"], $store.$filename);
        $res = TicketAttachUpdate($_POST['h_ticket_id'], $_POST['h_user_id'], $filename);
    }

    if ($res_file) {$_SESSION['RETURN_CODE'] = 105;}
    else {$_SESSION['RETURN_CODE'] = 900;}

    //header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;

