<?php
// config/email.php
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sendOTPEmail($recipientEmail, $otp) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'smartevent.reservation.team@gmail.com';
        $mail->Password = 'ykbx evhk ucog qala'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        
        // Remove any SSL verification issues
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Recipients
        $mail->setFrom('smartevent.reservation.team@gmail.com', 'Smart Event Team');
        $mail->addAddress($recipientEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code - Smart Event Reservation';
        $mail->Body = "
            <h2>Smart Event Reservation - OTP Verification</h2>
            <p>Your verification code is: <strong style='font-size: 24px; color: #ffb703;'>$otp</strong></p>
            <p>Enter this code on the verification page to complete your registration.</p>
            <p>This code expires in 5 minutes.</p>
        ";
        
        $mail->AltBody = "Your OTP Code: $otp - Enter this on the verification page.";

        if($mail->send()) {
            return true;
        } else {
            return false;
        }

    } catch (Exception $e) {
        error_log("Email error: " . $e->getMessage());
        return false;
    }
}
?>