<?php
### SYS
session_start();
require_once ("../sys/dbconn.php");        //DB CONN
require_once ("../sys/sql.php");           //SQL
require_once ('../sys/global_vars.php');   //GLOBAL VARS
require_once ('../sys/global_functions.php');   //GLOBAL FUNCTIONS


print_r(ticketPoolSearch($_POST));



