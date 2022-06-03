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
    $url = 'http://172.16.2.34:9090/?ticket_id=';
    $value = [
        'idaf_pool' => 38,
        'subject' => 'new Ticket redistribution from Pedro Sousa',
        'body' => 'VPN nao funciona...',
        'ticket_thread_id' => 30,
        'note_type' => 'novo',
        'assunto' => 'VPN'
    ];


    $topic = 'Suporte Sistemas Internos - Anomalia - Dados';
    $team = 'IT DEV';
    $state = 'concluida';
    $mail->CharSet = 'UTF-8';
    $mail->addAddress('pedrosousa@smartdev-group.com', 'SmartFlow');
    $mail->Subject = 'SmartFlow';

    if ($value['note_type'] === 'novo') {
        $mail->Body = '<table align="center" class="MsoNormalTable" style="width: 450.0pt; border-bottom: solid #CCCCCC 1.0pt;" border="0" width="600" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="border: solid #CCCCCC 1.0pt; border-bottom: none; background: #CCCCCC; padding: 30.0pt 0cm 22.5pt 0cm;">
<p class="xmsonormal" style="text-align: center;" align="center"><strong><span style="font-size: 16.0pt; color: #153643;">SmartFlow - Nova solicita&ccedil;&atilde;o #'.$value['ticket_thread_id'].'</span></strong></p>
</td>
</tr>
<tr>
<td style="border-top: none; border-left: solid #CCCCCC 1.0pt; border-bottom: none; border-right: solid #CCCCCC 1.0pt; background: white; padding: 30.0pt 22.5pt 30.0pt 22.5pt;">
<table class="MsoNormalTable" style="width: 100%; height: 630px;" border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr style="height: 84px;">
<td style="padding: 0cm; height: 84px; width: 531.35px;">
<p class="xmsonormal"><strong><span style="font-size: 10.0pt; color: #153643;">Registada nova solicita&ccedil;&atilde;o</span><br /></strong><strong><span style="color: gray;">TICKET '.$value['ticket_thread_id'].'<br />EQUIPA:</span></strong> <strong><span style="color: gray;">'. $team . '</span></strong></p>
</td>
</tr>
<tr style="height: 48px;">
<td style="padding: 15pt 0cm; height: 48px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="color: gray;">T&oacute;pico de Abertura: </span><span style="color: #153643;"> '. $topic . '</span></p>
</td>
</tr>
<tr style="height: 10px;">
<td style="padding: 5pt 0cm; height: 10px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="color: gray;">Assunto: </span><span style="color: #153643;">' . $value['assunto'] . '</span></p>
</td>
</tr>
<tr style="height: 0;">
<td style="padding: 15pt 0cm; height: 0; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="color: gray;">Detalhes: </span><span style="color: #153643;">' . $value['body'] . ' </span></p>
</td>
</tr>
<tr style="height: 48px;">
<td style="padding: 15pt 0cm; height: 48px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="color: gray;">Refer&ecirc;ncia Externa: </span></p>
</td>
</tr>
<tr style="height: 115px;">
<td style="padding: 15pt 0cm; height: 115px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="font-size: 10.0pt; color: #153643;"><a title="Actionflow" href="'.$url.$value['ticket_thread_id'].'" target="_blank">Clique para visualizar solicita&ccedil;&atilde;o</a></span></p>
<p class="xmsonormal" style="margin-bottom: 12.0pt;">&nbsp;</p>
<p><span style="font-size: 8.0pt; color: gray;">Esta mensagem foi gerada automaticamente</span></p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>';
    } elseif ($value['note_type'] === 'operador') {
        $mail->Body = '<table align="center" class="MsoNormalTable" style="width: 450.0pt;" border="0" width="600" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="border-bottom: none; background: #CCCCCC; padding: 30.0pt 0cm 22.5pt 0cm;">
<p class="xmsonormal" style="text-align: center;" align="center"><strong><span style="font-size: 16.0pt; color: #153643;">SmartFlow - Solicita&ccedil;&atilde;o #'.$value['ticket_thread_id'].' atribuída ao seu utilizador</span></strong></p>
</td>
</tr>
<tr>
<td style="border-top: none; border-left: solid #CCCCCC 1.0pt; border-bottom: none; border-right: solid #CCCCCC 1.0pt; background: white; padding: 30.0pt 22.5pt 30.0pt 22.5pt;">
<table class="MsoNormalTable" style="width: 100%; height: 630px;" border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr style="height: 84px;">
<td style="padding: 0cm; height: 84px; width: 531.35px;">
<p class="xmsonormal"><strong><span style="font-size: 10.0pt; color: #153643;">Solicitação atribuida ao seu utilizador</span><br /></strong><strong><span style="color: gray;">TICKET '.$value['ticket_thread_id'].'<br />EQUIPA:</span></strong> <strong><span style="color: gray;">'. $team . '</span></strong></p>
</td>
</tr>
<tr style="height: 48px;">
<td style="padding: 15pt 0cm; height: 48px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="color: gray;">T&oacute;pico de Abertura: </span><span style="color: #153643;"> '. $topic . '</span></p>
</td>
</tr>
<tr style="height: 48px;">
<td style="padding: 15pt 0cm; height: 48px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="color: gray;">Detalhes: </span><span style="color: #153643;">' . $value['body'] . ' </span></p>
</td>
</tr>
<tr style="height: 18px;">
<td style="padding: 0cm; height: 18px; width: 531.35px;">&nbsp;</td>
</tr>
<tr style="height: 48px;">
<td style="padding: 15pt 0cm; height: 48px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="color: gray;">Assunto: </span><span style="color: #153643;">Dados incorretos</span></p>
</td>
</tr>
<tr style="height: 48px;">
<td style="padding: 15pt 0cm; height: 48px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="color: gray;">Refer&ecirc;ncia Externa: </span></p>
</td>
</tr>
<tr style="height: 115px;">
<td style="padding: 15pt 0cm; height: 115px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="font-size: 10.0pt; color: #153643;"><a title="Actionflow" href="http://actionflow.telecom.pt:90/?ticket_id=9769" target="_blank">Clique para visualizar solicita&ccedil;&atilde;o</a></span></p>
<p class="xmsonormal" style="margin-bottom: 12.0pt;">&nbsp;</p>
<p><span style="font-size: 8.0pt; color: gray;">Esta mensagem foi gerada automaticamente</span></p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>';
    } elseif ($value['note_type'] === 'equipa') {
        $mail->Body = '<table align="center" class="MsoNormalTable" style="width: 450.0pt;" border="0" width="600" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="border: solid #CCCCCC 1.0pt; border-bottom: none; background: #CCCCCC; padding: 30.0pt 0cm 22.5pt 0cm;">
<p class="xmsonormal" style="text-align: center;" align="center"><strong><span style="font-size: 16.0pt; color: #153643;">SmartFlow - solicitação #'.$value['ticket_thread_id'].' atribuída à sua equipa</span></strong></p>
</td>
</tr>
<tr>
<td style="border-top: none; border-left: solid #CCCCCC 1.0pt; border-bottom: none; border-right: solid #CCCCCC 1.0pt; background: white; padding: 30.0pt 22.5pt 30.0pt 22.5pt;">
<table class="MsoNormalTable" style="width: 100%; height: 630px;" border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr style="height: 84px;">
<td style="padding: 0cm; height: 84px; width: 531.35px;">
<p class="xmsonormal"><strong><span style="font-size: 10.0pt; color: #153643;">Solicitação redistribuida para a sua equipa</span><br /></strong><strong><span style="color: gray;">TICKET '.$value['ticket_thread_id'].'<br />EQUIPA:</span></strong> <strong><span style="color: gray;">'. $team . '</span></strong></p>
</td>
</tr>
<tr style="height: 48px;">
<td style="padding: 15pt 0cm; height: 48px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="color: gray;">T&oacute;pico de Abertura: </span><span style="color: #153643;"> '. $topic . '</span></p>
</td>
</tr>
<tr style="height: 48px;">
<td style="padding: 15pt 0cm; height: 48px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="color: gray;">Detalhes: </span><span style="color: #153643;">' . $value['body'] . ' </span></p>
</td>
</tr>
<tr style="height: 18px;">
<td style="padding: 0cm; height: 18px; width: 531.35px;">&nbsp;</td>
</tr>
<tr style="height: 48px;">
<td style="padding: 15pt 0cm; height: 48px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="color: gray;">Assunto: </span><span style="color: #153643;">Dados incorretos</span></p>
</td>
</tr>
<tr style="height: 48px;">
<td style="padding: 15pt 0cm; height: 48px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="color: gray;">Refer&ecirc;ncia Externa: </span></p>
</td>
</tr>
<tr style="height: 115px;">
<td style="padding: 15pt 0cm; height: 115px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="font-size: 10.0pt; color: #153643;"><a title="Actionflow" href="http://actionflow.telecom.pt:90/?ticket_id=9769" target="_blank">Clique para visualizar solicita&ccedil;&atilde;o</a></span></p>
<p class="xmsonormal" style="margin-bottom: 12.0pt;">&nbsp;</p>
<p><span style="font-size: 8.0pt; color: gray;">Esta mensagem foi gerada automaticamente</span></p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>';
    } elseif ($value['note_type'] === 'estado') {
        $mail->Body = '<table align="center" class="MsoNormalTable" style="width: 450.0pt;" border="0" width="600" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="border: solid #CCCCCC 1.0pt; border-bottom: none; background: #CCCCCC; padding: 30.0pt 0cm 22.5pt 0cm;">
<p class="xmsonormal" style="text-align: center;" align="center"><strong><span style="font-size: 16.0pt; color: #153643;">SmartFlow - solicitação #'.$value['ticket_thread_id'].' mudou para estado ' . $state . '</span></strong></p>
</td>
</tr>
<tr>
<td style="border-top: none; border-left: solid #CCCCCC 1.0pt; border-bottom: none; border-right: solid #CCCCCC 1.0pt; background: white; padding: 30.0pt 22.5pt 30.0pt 22.5pt;">
<table class="MsoNormalTable" style="width: 100%; height: 630px;" border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr style="height: 84px;">
<td style="padding: 0cm; height: 84px; width: 531.35px;">
<p class="xmsonormal"><strong><span style="font-size: 10.0pt; color: #153643;">Solicitação atribuida ao seu utilizador</span><br /></strong><strong><span style="color: gray;">TICKET '.$value['ticket_thread_id'].'<br />EQUIPA:</span></strong> <strong><span style="color: gray;">'. $team . '</span></strong></p>
</td>
</tr>
<tr style="height: 48px;">
<td style="padding: 15pt 0cm; height: 48px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="color: gray;">T&oacute;pico de Abertura: </span><span style="color: #153643;"> '. $topic . '</span></p>
</td>
</tr>
<tr style="height: 48px;">
<td style="padding: 15pt 0cm; height: 48px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="color: gray;">Detalhes: </span><span style="color: #153643;">' . $value['body'] . ' </span></p>
</td>
</tr>
<tr style="height: 18px;">
<td style="padding: 0cm; height: 18px; width: 531.35px;">&nbsp;</td>
</tr>
<tr style="height: 48px;">
<td style="padding: 15pt 0cm; height: 48px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="color: gray;">Assunto: </span><span style="color: #153643;">Dados incorretos</span></p>
</td>
</tr>
<tr style="height: 48px;">
<td style="padding: 15pt 0cm; height: 48px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="color: gray;">Refer&ecirc;ncia Externa: </span></p>
</td>
</tr>
<tr style="height: 115px;">
<td style="padding: 15pt 0cm; height: 115px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="font-size: 10.0pt; color: #153643;"><a title="Actionflow" href="http://actionflow.telecom.pt:90/?ticket_id=9769" target="_blank">Clique para visualizar solicita&ccedil;&atilde;o</a></span></p>
<p class="xmsonormal" style="margin-bottom: 12.0pt;">&nbsp;</p>
<p><span style="font-size: 8.0pt; color: gray;">Esta mensagem foi gerada automaticamente</span></p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>';
    } elseif ($value['note_type'] === 'nota') {
        $mail->Body = '<table align="center" class="MsoNormalTable" style="width: 450.0pt;" border="0" width="600" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="border: solid #CCCCCC 1.0pt; border-bottom: none; background: #CCCCCC; padding: 30.0pt 0cm 22.5pt 0cm;">
<p class="xmsonormal" style="text-align: center;" align="center"><strong><span style="font-size: 16.0pt; color: #153643;">SmartFlow - Nota adicionada à solicitação #'.$value['ticket_thread_id'].'</span></strong></p>
</td>
</tr>
<tr>
<td style="border-top: none; border-left: solid #CCCCCC 1.0pt; border-bottom: none; border-right: solid #CCCCCC 1.0pt; background: white; padding: 30.0pt 22.5pt 30.0pt 22.5pt;">
<table class="MsoNormalTable" style="width: 100%; height: 630px;" border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr style="height: 84px;">
<td style="padding: 0cm; height: 84px; width: 531.35px;">
<p class="xmsonormal"><strong><span style="font-size: 10.0pt; color: #153643;">Solicitação atribuida ao seu utilizador</span><br /></strong><strong><span style="color: gray;">TICKET '.$value['ticket_thread_id'].'<br />EQUIPA:</span></strong> <strong><span style="color: gray;">'. $team . '</span></strong></p>
</td>
</tr>
<tr style="height: 48px;">
<td style="padding: 15pt 0cm; height: 48px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="color: gray;">T&oacute;pico de Abertura: </span><span style="color: #153643;"> '. $topic . '</span></p>
</td>
</tr>
<tr style="height: 48px;">
<td style="padding: 15pt 0cm; height: 48px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="color: gray;">Nota: </span><span style="color: #153643;">' . $value['body'] . ' </span></p>
</td>
</tr>
<tr style="height: 18px;">
<td style="padding: 0cm; height: 18px; width: 531.35px;">&nbsp;</td>
</tr>
<tr style="height: 115px;">
<td style="padding: 15pt 0cm; height: 115px; width: 531.35px;">
<p class="xmsonormal" style="line-height: 15.0pt;"><span style="font-size: 10.0pt; color: #153643;"><a title="Actionflow" href="http://actionflow.telecom.pt:90/?ticket_id=9769" target="_blank">Clique para visualizar solicita&ccedil;&atilde;o</a></span></p>
<p class="xmsonormal" style="margin-bottom: 12.0pt;">&nbsp;</p>
<p><span style="font-size: 8.0pt; color: gray;">Esta mensagem foi gerada automaticamente</span></p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>';
    }


    $mail->From = "noreply-smartflow@Smartdev-group.com";
    $mail->FromName = "Noreply Smartflow";
    $mail->isHTML(true);
    /* Tells PHPMailer to use SMTP. */
    $mail->isSMTP();

    /* SMTP server address. */
    $mail->Host = 'smtp.office365.com';

    /* Use SMTP authentication. */
    $mail->SMTPAuth = TRUE;

    /* Set the encryption system. */
    $mail->SMTPSecure = 'tls';

    /* SMTP authentication username. */
    $mail->Username = 'noreply-smartflow@Smartdev-group.com';

    /* SMTP authentication password. */
    $mail->Password = 'Smartdev2020@';

    /* Set the SMTP port. */
    $mail->Port = 587;

    /* Finally send the mail. */
    $mail->send();
} catch (Exception $e)
{
    echo $e->errorMessage();
}

