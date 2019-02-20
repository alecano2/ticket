<?php
function sendmail($email_address, $nickname, $code) {
    $subject = "Recupero password";
    $mail_body = "Il codice per recuperare la password dell'account $nickname è: $code";
    mail($email_address, $subject, $mail_body);
}
?>