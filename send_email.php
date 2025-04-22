<?php
// filepath: c:\xampp\htdocs\project-main\send_email.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullName = htmlspecialchars($_POST['full-name']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $age = htmlspecialchars($_POST['age']);
    $gender = htmlspecialchars($_POST['gender']);
    $package = htmlspecialchars($_POST['package']);
    $branch = htmlspecialchars($_POST['branch']);
    $appointmentDate = htmlspecialchars($_POST['appointment-date']);
    $specialRequests = htmlspecialchars($_POST['special-requests']);

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'safecare884@gmail.com'; // Replace with your Gmail address
        $mail->Password = 'zejo nwxo hzrm rnbo'; // Replace with your Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email details
        $mail->setFrom('safecare884@gmail.com', 'SafeCare Hospital'); // Replace with your email
        $mail->addAddress($email); // Replace with the recipient's email
        $mail->addReplyTo($email, $fullName);

        $mail->isHTML(true);
        $mail->Subject = 'New Health Checkup Appointment';
        $mail->Body = "
            <html>
            <head>
                <title>New Health Checkup Appointment</title>
            </head>
            <body>
                <h2>New Appointment Details</h2>
                <p><strong>Full Name:</strong> $fullName</p>
                <p><strong>Phone:</strong> $phone</p>
                <p><strong>Email:</strong> $email</p>
                <p><strong>Age:</strong> $age</p>
                <p><strong>Gender:</strong> $gender</p>
                <p><strong>Package:</strong> $package</p>
                <p><strong>Branch:</strong> $branch</p>
                <p><strong>Preferred Date:</strong> $appointmentDate</p>
                <p><strong>Special Requests:</strong> $specialRequests</p>
            </body>
            </html>
        ";

        // Send email
        $mail->send();
        echo json_encode(["status" => "success", "message" => "Email sent successfully."]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Failed to send email. Error: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>