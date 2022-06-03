<?php
session_start();
require_once("../sys/dbconn.php");        //DB CONN
require_once("../sys/sql.php");           //SQL
require_once('../sys/global_vars.php');   //GLOBAL VARS
require_once('../sys/global_functions.php');   //GLOBAL FUNCTIONS

$id = $_POST['id'];

$getFormNumber = getFormByID($id)[0]['form_id'];

foreach(getFormColumnsByNumber($getFormNumber) as $value) : ?>
        <div class="form-check-inline d-inline-flex align-content-center">
            <input type="checkbox" class="form-check-input" id="<?= $value['Field'] ?>">
            <label class="form-check-label" for="exampleCheck1"><?= $value['Field'] ?></label>
        </div>
<?php endforeach ?>

