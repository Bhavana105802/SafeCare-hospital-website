<?php
// filepath: c:\xampp\htdocs\project-main\help_mail.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect form data
    $fullName = htmlspecialchars($_POST['full-name']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $queryType = htmlspecialchars($_POST['query-type']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'abkbhanukiran@gmail.com'; // Replace with your Gmail address
        $mail->Password = 'xgys hkth ryrn lwnp'; // Replace with your Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email details
        $mail->setFrom('your-email@gmail.com', 'SafeCare Hospital'); // Replace with your email
        $mail->addAddress('bhavs662@gmail.com'); // Replace with the recipient's email
        $mail->addReplyTo($email, $fullName);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = "
            <html>
            <head>
                <title>Support Query</title>
            </head>
            <body>
                <h2>New Support Query</h2>
                <p><strong>Full Name:</strong> $fullName</p>
                <p><strong>Phone:</strong> $phone</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Query Type:</strong> $queryType</p>
                <p><strong>Subject:</strong> $subject</p>
                <p><strong>Message:</strong></p>
                <p>$message</p>
            </body>
            </html>
        ";

        // Send email
        $mail->send();
        echo json_encode(["status" => "success", "message" => "Your query has been submitted successfully."]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Failed to send email. Error: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>