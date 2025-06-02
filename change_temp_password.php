<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'Includes/dbcon.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['userId']) || !isset($_SESSION['emailAddress'])) {
    header("Location: index.php");
    exit();
}

$errorMsg = '';
$successMsg = '';

if (isset($_POST['changePassword'])) {
    $newPassword = trim($_POST['newPassword']);
    $confirmPassword = trim($_POST['confirmPassword']);
    $userId = $_SESSION['userId'];
    $email = $_SESSION['emailAddress'];

    // Validate passwords
    if (empty($newPassword) || empty($confirmPassword)) {
        $errorMsg = 'Both fields are required.';
    } elseif ($newPassword !== $confirmPassword) {
        $errorMsg = 'Passwords do not match.';
    } else {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password and reset required flag
        $updateQuery = "UPDATE tbllecture SET password = ?, passwordresetrequired = 0 WHERE Id = ? AND emailAddress = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sis", $hashedPassword, $userId, $email);
        
        if ($stmt->execute()) {
            // Update successful
            $_SESSION['passwordChanged'] = true;
            header("Location: lecture/takeAttendance.php");
            exit();
        } else {
            $errorMsg = 'Failed to update password. Please try again.';
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
            margin-bottom: 20px;
            margin-top: -10px;
        }
        .label {
            text-align: left; 
            display: flex; 
            color: rgb(92, 92, 92);
            font-size: 15px;
            margin-bottom: 5px;
        }
        form input {
            width: 100%;
            background: rgb(223, 245, 227);
            border: 1px solid rgb(216, 231, 220);
            padding: 12px 14px;
            margin-bottom: 15px;
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
            width: 100%;
            margin-top: 10px;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
  <div class="middle">
    <div class="middle-content">
      <div class="image">
        <img src="css/pic/logo.png" alt="Logo">
      </div>
      <div class="container" id="signin">
        <h1>Change Your Password</h1>
        <p style="margin-bottom: 20px; color: #666;">Please set your new password to continue.</p>

        <?php if (!empty($errorMsg)): ?>
            <div class="error-message"><?php echo htmlspecialchars($errorMsg); ?></div>
        <?php endif; ?>

        <form method="post" action="">
          <label class="label">New Password</label>
          <input type="password" name="newPassword" placeholder="Enter new password" required>

          <label class="label">Confirm Password</label>
          <input type="password" name="confirmPassword" placeholder="Confirm new password" required>

          <input type="submit" class="btn-login" value="Change Password" name="changePassword">
        </form>
      </div> 
    </div>
  </div>
</body>
</html> 