<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../../../../vendors/PHPMailer/src/Exception.php';
require_once '../../../../vendors/PHPMailer/src/PHPMailer.php';
require_once '../../../../vendors/PHPMailer/src/SMTP.php';

require_once '../../../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../../');
$dotenv->load();

/**
 * Send verification email
 *
 * @param string $user_email
 * @param string $user_name
 * @param string $verification_token
 * @return bool
 */
function send_mail($user_email, $user_name, $verification_token)
{
    try {
        $mail = new PHPMailer(true);

        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];

        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom("sumitrathor142272@gmail.com", "Sumit Rathor (No Reply)");
        $mail->addAddress($user_email);
        $mail->addAddress("sumitrathor142272@gmail.com"); // Optional: BCC to admin

        $mail->isHTML(true);
        $mail->Subject = "Verify your email - Sumit Rathor";

        // Construct verification link
        $host = $_SERVER['HTTP_HOST'];            // e.g., localhost or yourdomain.com
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http"; // auto-detect http/https
        $baseURL = $protocol . "://" . $host;

        $verification_link = $baseURL . "//Medimind/register.php?token=" . urlencode($verification_token);


        // HTML body
        $message = '
        <html>
        <head>
            <title>Email Verification</title>
        </head>
        <body>
            <h2>Hello, ' . htmlspecialchars($user_name) . '</h2>
            <p>Thank you for registering.</p>
            <p>Please click the link below to verify your email:</p>
            <a href="' . $verification_link . '" target="_blank" style="padding: 10px 20px; background-color: green; color: white; text-decoration: none;">Verify Email</a>
            <p>If you did not request this, you can ignore this email.</p>
        </body>
        </html>';

        $mail->Body = $message;

        $mail->send();
        return true; // Mail sent
    } catch (Exception $e) {
        return false; // Mail failed
    }
}