<?php
include 'Includes/dbcon.php';
session_start();

$errorMsg = '';
$successMsg = '';

// Check if the email and token are provided
if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    // Verify the token from the password_resets table
    $query = "SELECT * FROM password_resets WHERE email = ? AND token = ? AND expires_at > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid, proceed to reset password
        if (isset($_POST['changePassword'])) {
            $newPassword = trim($_POST['newPassword']);
            $confirmPassword = trim($_POST['confirmPassword']);

            // Validate passwords
            if (empty($newPassword) || empty($confirmPassword)) {
                $errorMsg = 'Both fields are required.';
            } elseif ($newPassword !== $confirmPassword) {
                $errorMsg = 'Passwords do not match.';
            } else {
                // Hash the new password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Start transaction to ensure both password and reset status update together
                $conn->begin_transaction();

                try {
                    // Update the password in the tbllecture table
                    $updateQuery = "UPDATE tbllecture SET password = ?, passwordresetrequired = 0 WHERE emailAddress = ?";
                    $updateStmt = $conn->prepare($updateQuery);
                    $updateStmt->bind_param("ss", $hashedPassword, $email);
                    $updateStmt->execute();

                    // Check if the update was successful
                    if ($updateStmt->affected_rows > 0) {
                        // Successfully updated the password, now delete the reset token
                        $deleteTokenQuery = "DELETE FROM password_resets WHERE email = ?";
                        $deleteStmt = $conn->prepare($deleteTokenQuery);
                        $deleteStmt->bind_param("s", $email);
                        $deleteStmt->execute();

                        if ($deleteStmt->affected_rows > 0) {
                            // Commit the transaction
                            $conn->commit();
                            $successMsg = 'Your password has been successfully updated.';
                            // Redirect to login page
                            header("Location: index.php");
                            exit();
                        } else {
                            // Rollback if deleting the token fails
                            $conn->rollback();
                            $errorMsg = 'Error deleting the reset token.';
                        }
                    } else {
                        // Rollback if updating the password fails
                        $conn->rollback();
                        $errorMsg = 'Failed to update password. Please try again.';
                    }
                } catch (Exception $e) {
                    // Rollback the transaction in case of an error
                    $conn->rollback();
                    $errorMsg = 'An error occurred: ' . $e->getMessage();
                }
            }
        }
    } else {
        $errorMsg = 'Invalid or expired token.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="css/pic/cvsu.png" rel="icon">
    <title>KabsuAttS - Change Password</title>
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
        #signin {
            position: relative;
            padding: 50px;
            width: 500px;
            margin: 50px auto;
            padding-bottom: 50px;
            border-radius: 45px;
            background-color: rgba(255, 255, 255, 0.51);
            overflow: hidden;
        }

        #signin::before {
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
        #signin h1 {
            font-size: 30px;
            margin-bottom: 10px;
            margin-top: -10px;
        }
        .label {
            text-align: left; 
            display: flex; 
            color: rgb(92, 92, 92);
            font-size: 15px;
        }
        form input {
            width: 100%;
            background: rgb(223, 245, 227);
            border: 1px solid rgb(216, 231, 220);
            padding: 12px 14px;
            margin-bottom: 10px;
            border-radius: 10px;
            appearance: none;
            -moz-appearance: none;
            -webkit-appearance: none;
            position: relative;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease-in-out;
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
        .text a {
            text-decoration: none;
            color: rgb(92, 92, 92);
            font-size: 15px;
            padding-bottom: 0;
            margin-bottom: 0;
        }
        .text a:hover {
            font-weight: 600;
        }

    </style>
</head>
<body>
  <div class="middle">
    <div class="middle-content">
    <div class="image">
        <img src="css/pic/logo.png">
    </div>
    <div class="container" id="signin">
        <h1>Reset account password</h1>

        <div id="messageDiv" class="messageDiv" style="display:none;"></div>

        <form method="post" action="">
            <input type="password" name="newPassword" placeholder="Enter New Password">
            <input type="password" name="confirmPassword" placeholder="Confirm Password">

            <input type="submit" class="btn-login" value="Reset Password" name="changePassword" />
        </form>
    </div>
  </div>
</body>
</html>
