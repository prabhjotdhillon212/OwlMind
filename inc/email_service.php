<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


function sendWelcomeEmail($toEmail, $toName) {

    // generate random 5 digit number and add it to email. For verification.
    $num_digits = 5;
    $verify_code = str_pad(rand(0, pow(10, $num_digits)-1), $num_digits, '0', STR_PAD_LEFT);
    $_SESSION['code'] = $verify_code;

    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'owlmind.app@gmail.com'; // Your Gmail
        $mail->Password   = 'eojw jsym qiln vwjz';    // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Use TLS
        $mail->Port       = 587;
       


        // Sender & Recipient
        $mail->setFrom('owlmind.app@gmail.com', 'Owl Mind');
        $mail->addAddress($toEmail, $toName);
        $mail->addReplyTo('owlmind.app@gmail.com', 'Owl Mind Support');

        // Content
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Welcome to Owl Mind! 🦉';
        $mail->Body    = "
            <div style='font-family: Arial, sans-serif; color:#333;'>
                <h1 style='color:#1d3557;'>Welcome to Owl Mind</h1>
                <p>Hi " . htmlspecialchars($toName) . ",</p>
                <p>We’re excited to have you here! Start journaling, track your moods, and take care of your mental wellness with us.</p>
                <p><strong>Let’s get started!</strong></p>
                <p><strong>Enter the following code on the verification page:</strong></p>
                <p> $verify_code </p>
                <p style='color:#888;'>– The Owl Mind Team</p>
            </div>
        ";
        $mail->AltBody = "Hi " . htmlspecialchars($toName) . ",\nWelcome to Owl Mind!\n\nStart journaling and tracking your moods.\n\n- The Owl Mind Team";

        // Debugging (set to 0 in production)
        $mail->SMTPDebug = 0; // Change to SMTP::DEBUG_SERVER for debug info

        // Send
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("📧 PHPMailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
