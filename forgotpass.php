<?php
include 'Includes/dbcon.php';
require 'vendor/autoload.php';
session_start();

date_default_timezone_set('Asia/Manila');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$message = ''; // Initialize the message variable
$showMessage = false; // Control the visibility of messageDiv

if (isset($_POST['send_link'])) {
    $email = trim($_POST['email']);

    // Sanitize the email input for security
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email address.";
        $showMessage = true;
    } else {
        // Check if the email exists in either tbladmin or tbllecture
        $query = "SELECT emailAddress FROM (
                    SELECT emailAddress FROM tbladmin
                    UNION
                    SELECT emailAddress FROM tbllecture
                  ) AS combined WHERE LOWER(emailAddress) = LOWER(?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Generate a token
            $token = bin2hex(random_bytes(32));
            $created_at = date('Y-m-d H:i:s');
            $expires_at = date('Y-m-d H:i:s', strtotime('+30 minutes'));

            // Save token in the database
            $insertQuery = "INSERT INTO password_resets (email, token, created_at, expires_at)
                            VALUES (?, ?, ?, ?)
                            ON DUPLICATE KEY UPDATE token = VALUES(token), created_at = VALUES(created_at), expires_at = VALUES(expires_at)";
            $insertStmt = $conn->prepare($insertQuery);
            $insertStmt->bind_param("ssss", $email, $token, $created_at, $expires_at);
            $insertStmt->execute();

            $resetLink = "http://localhost/faceRecognition/changepass.php?email=" . urlencode($email) . "&token=" . urlencode($token);

            // Send the reset link via email using PHPMailer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                            $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server
                            $mail->SMTPAuth   = true;
                            $mail->Username = 'cvsuimus.alumniassoc@gmail.com'; // SMTP username
                $mail->Password = 'kwwy jeip tgbx ppea'; // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('kabsuatts@gmail.com', 'KabsuAttS');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Password Reset';
                $mail->Body = "Click here to reset your password: <a href='$resetLink'>$resetLink</a>";

                $mail->send();
                $message = "A reset link has been sent to your email.";
                $showMessage = true;
            } catch (Exception $e) {
                // Handle PHPMailer errors gracefully
                $message = "Failed to send reset link. Please try again later.";
                $showMessage = true;
            }
        } else {
            $message = "Email not found.";
            $showMessage = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css/pic/cvsu.png" rel="icon">
    <title>KabsuAttS - Forgot Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/loginStyle.css">
    <style>
        body {
            background-image: url('css/pic/background.png');
            height: 100%;
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .middle {
            padding-top: 4%;
        }
        .image {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;
        }
        #forgot-password {
            position: relative;
            padding: 50px;
            width: 500px;
            margin: 50px auto;
            padding-bottom: 50px;
            border-radius: 45px;
            background-color: rgba(255, 255, 255, 0.51);
            overflow: hidden;
        }
        #forgot-password::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(12px);
            border-radius: 15px;
            z-index: -1;
        }
        #forgot-password h1 {
            font-size: 30px;
            margin-bottom: 10px;
            margin-top: -10px;
        }
        form input {
            width: 100%;
            background: rgb(223, 245, 227);
            border: 1px solid rgb(216, 231, 220);
            padding: 12px 14px;
            margin-bottom: 10px;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
        }
        form input:focus {
            background-color: rgb(183, 235, 190);
            border: 2.2px solid #dfe9f5;
        }
        .btn-login {
            background-color: #30C81F;
            font-weight: 500;
            width: 50%;
        }
        .text {
            text-decoration: none;
            color: rgb(56, 56, 56);
            font-size: 15px;
            margin-top: 10px;
        }
        #messageDiv {
            margin-top: 10px;
            font-size: 16px;
            color: #FFFFFF;
            text-align: center;
            display: <?php echo $showMessage ? 'block' : 'none'; ?>;
        }
    </style>
</head>
<body>
<div class="middle">
    <div class="middle-content">
        <div class="image">
            <img src="css/pic/logo.png" alt="Logo">
        </div>
        <div class="container" id="forgot-password">
            <h1>Forgot Password</h1>
            <p class="text">Enter your email address, and we'll send you a reset link.</p>
            <div id="messageDiv" class="messageDiv"><?php echo htmlspecialchars($message); ?></div>
            <form method="post" action="">
                <input type="email" name="email" placeholder="Enter your email" required>
                <input type="submit" class="btn-login" value="Send Reset Link" name="send_link">
            </form>
        </div>
    </div>
</div>
</body>
</html>
