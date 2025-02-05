<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

function registerMail($recipientEmail)
{
    $htmlTemplate = file_get_contents('../Html/mail.html');
    $message = $htmlTemplate;

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = 0;                                       // Enable verbose debug output
        $mail->isSMTP();                                            // Set mailer to use SMTP
        $mail->Host       = 'smtp.gmail.com';                     // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'ohmasan56@gmail.com';               // SMTP username
        $mail->Password   = 'kaiksbqdkplytzwj';                  // SMTP password
        $mail->SMTPSecure = "tls";                                 // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = 587;                                    // TCP port to connect to

        // Recipients
        $mail->setFrom('ohmasan56@gmail.com', 'From Doctor Appointment System');
        $mail->addAddress($recipientEmail);

        // Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = 'Registration';
        $mail->Body    = $message;

        $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
    }
}
