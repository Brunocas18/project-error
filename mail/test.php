<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* Exception class. */
require 'Exception.php';

/* The main PHPMailer class. */
require 'PHPMailer.php';

/* SMTP class, needed if you want to use SMTP. */
require 'SMTP.php';

/* Create a new PHPMailer object. Passing TRUE to the constructor enables exceptions. */
$mail = new PHPMailer(TRUE);

try {

    /* Tells PHPMailer to use SMTP. */
    $mail->isSMTP();

    /* SMTP server address. */
    $mail->Host = 'smtp.office365.com';

    $mail->SMTPDebug = 1;

    /* Set the SMTP port. */
    $mail->Port = 587;

	$mail->SMTPSecure = '';

    /* Use SMTP authentication. */
    $mail->SMTPAuth = TRUE;

    /* Set the encryption system. */
    $mail->SMTPSecure = 'tls';

    /* SMTP authentication username. */
    $mail->Username = 'noreply-smartflow@Smartdev-group.com';

    /* SMTP authentication password. */
    $mail->Password = 'Smartdev2020@';

	$mail->From = "noreply-smartflow@Smartdev-group.com";
    $mail->FromName = "Noreply Smartflow";
	$mail->AddAddress("pedrovenancio@smartdev-group.com");
	$mail->AddAddress("pedrosousa@smartdev-group.com");

	$mail->Subject = 'notification test';
    $mail->Body = 'Test 1 2 3...';
	
    /* Finally send the mail. */
    $mail->send();

} catch (Exception $e)
{
    echo $e->errorMessage();
}

