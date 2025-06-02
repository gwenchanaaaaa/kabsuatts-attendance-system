<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Buffer all output
ob_start();

include 'Includes/dbcon.php';
session_start();

// Clear any existing session data
session_unset();
session_destroy();
session_start();

$errorMsg = ''; // Initialize error message variable

if (isset($_POST['login'])) {
    $email = strtolower(trim($_POST['email']));
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $errorMsg = 'All fields are required.';
    } else {
        // First, check in the admin table
        $query = "SELECT * FROM tbladmin WHERE LOWER(emailAddress) = LOWER(?)";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            $errorMsg = "Error preparing the query: " . $conn->error;
        } else {
            $stmt->bind_param("s", $email);
            if (!$stmt->execute()) {
                $errorMsg = "Error executing query: " . $stmt->error;
            } else {
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    if (password_verify($password, $user['password'])) {
                        $_SESSION['userId'] = $user['Id'];
                        $_SESSION['emailAddress'] = $user['emailAddress'];
                        $_SESSION['firstName'] = $user['firstName'];
                        $_SESSION['userType'] = 'admin';
                        
                        header("Location: Admin/index.php");
                        exit();
                    } else {
                        $errorMsg = 'Invalid email or password.';
                    }
                } else {
                    // If no match in admin table, check in the lecture table
                    $query2 = "SELECT * FROM tbllecture WHERE LOWER(emailAddress) = LOWER(?)";
                    $stmt = $conn->prepare($query2);
                    if ($stmt === false) {
                        $errorMsg = "Error preparing the query: " . $conn->error;
                    } else {
                        $stmt->bind_param("s", $email);
                        if (!$stmt->execute()) {
                            $errorMsg = "Error executing query: " . $stmt->error;
                        } else {
                            $result = $stmt->get_result();

                            if ($result->num_rows > 0) {
                                $user = $result->fetch_assoc();
                                if (password_verify($password, $user['password'])) {
                                    $_SESSION['userId'] = $user['Id'];
                                    $_SESSION['emailAddress'] = $user['emailAddress'];
                                    $_SESSION['firstName'] = $user['firstName'];
                                    $_SESSION['lastName'] = $user['lastName'];
                                    $_SESSION['userType'] = 'instructor';
                                    
                                    // Check if password reset is required
                                    if (isset($user['passwordresetrequired']) && $user['passwordresetrequired'] == 1) {
                                        header("Location: change_temp_password.php");
                                        exit();
                                    } else {
                                        header("Location: lecture/takeAttendance.php");
                                        exit();
                                    }
                                } else {
                                    $errorMsg = 'Invalid email or password.';
                                }
                            } else {
                                $errorMsg = 'Email not found in our records.';
                            }
                        }
                    }
                }
            }
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
    <title>KabsuAttS - Login</title>
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
            padding: 20px;
            width: 450px;
            margin: 50px auto;
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
            margin-left: 35px;
            color: rgb(92, 92, 92);
            font-size: 15px;
        }
        form input {
            width: 85%;
            background: rgb(223, 245, 227);
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
            background: rgb(223, 245, 227);
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
        form input:focus {
            background-color: rgb(183, 235, 190);
            border: 2.2px solid #dfe9f5;
        }
        /* Rotate arrow when dropdown is expanded */
        form select:focus,
        form select.expanded {
            background-color: rgb(230, 230, 230);

            /* Custom arrow - rotated */
            background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" stroke="black" fill="none" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"/></svg>');
        }
        .btn-login {
            background-color: #30C81F;
            font-weight: 500;
        }
        .text {
            margin-top: 10px;
        }
        .text a {
            text-decoration: none;
            color: rgb(92, 92, 92);
            font-size: 15px;
        }
        .text a:hover {
            text-decoration: underline;
        }

        /* Add styles for the error message */
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
        <img src="css/pic/admin.png" alt="Admin">
        <h1>Log In</h1>

        <!-- Display error message if it exists -->
        <?php if (!empty($errorMsg)): ?>
            <div class="error-message"><?php echo htmlspecialchars($errorMsg); ?></div>
        <?php endif; ?>

        <form method="post" action="">
          <label class="label">Email</label>
          <input type="email" name="email" placeholder="Enter Email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">

          <label class="label">Password</label>
          <input type="password" name="password" placeholder="Enter Password">

          <input type="submit" class="btn-login" value="Log In" name="login" />
        </form>
        <p class="text">
            <a href="forgotpass.php">Forgot your password?</a>
        </p>
      </div> 
    </div>
  </div>
</body>
</html>
<?php
// Flush the output buffer and send output to browser
ob_end_flush();
?>
