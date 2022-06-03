<?php

    ### SYS
    session_start();
    require_once ("../sys/dbconn.php");        //DB CONN
    require_once ("../sys/sql.php");           //SQL
    require_once ('../sys/global_vars.php');   //GLOBAL VARS
    require_once ('../sys/global_functions.php');   //GLOBAL FUNCTIONS

    //echo $_POST['topic_id'];

    ### GET DATA
    $options_topic_level_2_form = TopicLevelForm($_POST["topic_id"]); //GET OPTIONS - TOPICS LEVEL 1
    //echo $options_topic_level_2_form['item'][0]['form_html'];

    $flag_edit="";

    include ('../../view/html/bo/forms/'.$options_topic_level_2_form['item'][0]['form_html']);