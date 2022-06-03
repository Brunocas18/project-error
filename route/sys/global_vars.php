<?php

//SET MANUAL GLOBAL VARS

### SET GLOBAL VARS

$__user = explode("\\",$_SERVER['AUTH_USER']);

if (empty($__user[1])) {
    $__user = explode("/",$_SERVER['AUTH_USER']);
}

$GLOBALS['USER_AD_DOMAIN'] = $__user[0];
$GLOBALS['USER_AD'] = $__user[1];

$GLOBALS['USER_IP'] = $_SERVER['REMOTE_ADDR'];
$GLOBALS['USER_SESSION_ID'] = session_id();

$GLOBALS['SITE_NAME'] = "SmartFlow DEV";
$GLOBALS['fotosDirectorio'] = "..//view/assets/img/";

