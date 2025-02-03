<?php
include 'Includes/dbcon.php';
session_start();

$message = ''; 

// Check if email and token are passed in the URL for password reset (from reset link)
if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];



    // Verify the token from the database
    $query = "SELECT * FROM password_resets WHERE email = ? AND token = ? AND expires_at > NOW()";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Token is valid, allow the user to reset their password
        if (isset($_POST['changePassword'])) {
            $newPassword = trim($_POST['newPassword']);
            $confirmPassword = trim($_POST['confirmPassword']);

            // Debugging - remove echo after testing
            echo "New Password: " . $newPassword;  // Debugging
            echo "Confirm Password: " . $confirmPassword;  // Debugging

            // Validate the new password and confirmation
            if (empty($newPassword) || empty($confirmPassword)) {
                $message = 'All fields are required.';
            } elseif ($newPassword !== $confirmPassword) {
                $message = 'Passwords do not match.';
            } else {
                // Hash the new password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// After hashing the password, update the password and set passwordresetrequired to 0
$query = "UPDATE tbllecture SET password = ?, passwordresetrequired = 0 WHERE emailAddress = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $hashedPassword, $email);

// Execute the password update query
if ($stmt->execute()) {
    // Check if any rows were affected (password updated)
    if ($stmt->affected_rows > 0) {
        // Successfully updated the password, delete the reset token
        $deleteTokenQuery = "DELETE FROM password_resets WHERE email = ?";
        $deleteStmt = $conn->prepare($deleteTokenQuery);
        $deleteStmt->bind_param("s", $email);
        if ($deleteStmt->execute()) {
            $message = 'Password changed successfully. You can now log in with your new password.';
            // Redirect to login page after password update
            header("Location: index.php");
            exit();
        } else {
            $message = 'Error deleting token.';
        }
    } else {
        // If no rows were affected, something went wrong with the password update
        $message = 'No changes made to the password. Please check your email and try again.';
    }
} else {
    // If the query fails, capture the error message
    $message = 'Failed to update password. Please try again. Error: ' . $stmt->error;
}

            }
        }
    } else {
        $message = 'Invalid or expired token.';
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
    .middle{
      padding-top: 4%;
    }
    .image{
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .container{
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
#signin h1{
  font-size: 30px;
  margin-bottom: 10px;
  margin-top: -10px;
}
.label{
  text-align: left; 
  display:flex; 
  color: rgb(92, 92, 92);
  font-size: 15px;
}
form input{
  width: 100%;
    background:rgb(223, 245, 227);
    border: 1px solid  rgb(216, 231, 220);
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
form select {
    width: 85%;
    outline: none;
    border: none;
    background:rgb(223, 245, 227);
    box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;
    border: 1px solid  rgb(216, 231, 220);
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

    /* Custom arrow - line-based (default) */
    background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" stroke="black" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>');
    background-repeat: no-repeat;
    background-position: right 14px center;
    background-size: 20px;
}
form input:focus{
  background-color:rgb(183, 235, 190);
  border:2.2px solid #dfe9f5;
}
/* Rotate arrow when dropdown is expanded */
form select:focus,
form select.expanded {
    background-color: rgb(230, 230, 230);

    /* Custom arrow - rotated */
    background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" stroke="black" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>');
}
.btn-login{
  background-color: #30C81F;
  font-weight: 500;
  width: 50%;
}
.text{
  text-decoration: none;
  color: rgb(56, 56, 56);
  font-size: 15px;
  margin-top: 10px;
}
.text a{
  text-decoration: none;
  color: rgb(92, 92, 92);
  font-size: 15px;
  padding-bottom: 0;
  margin-bottom: 0;
}
.text a:hover{
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

    <form method="post" action="" >
        <input type="password" name="password"placeholder="Enter Password">
        <input type="confirmpassword" name="confirmpassword"placeholder="Confirm Password">
        
        <input type="submit" class="btn-login" value="Reset Password" name="login" />
    </form>
    </div>
    
   </div>
   <script>
  function showMessage(message) {
  var messageDiv = document.getElementById('messageDiv');
  messageDiv.style.display="block";
  messageDiv.innerHTML = message;
  messageDiv.style.opacity = 1;
  setTimeout(function() {
    messageDiv.style.opacity = 0;
  }, 5000);
}



   </script>