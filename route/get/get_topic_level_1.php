<?php

### SYS
session_start();
require_once("../sys/dbconn.php");        //DB CONN
require_once("../sys/sql.php");           //SQL
require_once('../sys/global_vars.php');   //GLOBAL VARS
require_once('../sys/global_functions.php');   //GLOBAL FUNCTIONS

### GET DATA
$options_topic_level_1 = TopicLevel($_POST["topic_id"]); //GET OPTIONS - TOPICS LEVEL 0

if (sizeof($options_topic_level_1['item']) > 0) {

    echo '<option selected disabled value="">Selecione um tópico nivel 1</option>';

    for ($i = 0; $i < sizeof($options_topic_level_1['item']); $i++) {

        echo '<option value="' . $options_topic_level_1['item'][$i]['topic_id'] . '">' . $options_topic_level_1['item'][$i]['topic_description'] . '</option>';
    }
} else echo '<option selected disabled>Sem opções</option>';
