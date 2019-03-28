<?php

require 'class/SMTPMailer.php';
$mail = new SMTPMailer();

$mail->addTo('someaccount@hotmail.com');

$mail->Subject('Pictures for you');
$mail->Body(
    '<h3>Some Images</h3>
    I send you some pictures.<br>
    Greetings!'
);

$mail->File('images/landscape.jpg');
$mail->File('images/monkey.jpg');

if ($mail->Send()) echo 'Mail sent successfully';
else               echo 'Mail failure';
