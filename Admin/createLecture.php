<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Enable error reporting for debugging (Disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include Composer's autoloader for PHPMailer
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to fetch faculty names
function getFacultyNames($conn) {
    $sql = "SELECT facultyCode, facultyName FROM tblfaculty";
    $result = $conn->query($sql);

    $facultyNames = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $facultyNames[] = $row;
        }
    }

    return $facultyNames;
}

$facultyNames = getFacultyNames($conn);

// Function to generate a random temporary password
function generateTempPassword($length = 10) {
    return bin2hex(random_bytes($length / 2)); // Generates a hexadecimal string
}

// Handle Add Lecture
if (isset($_POST["addLecture"])) {
    // Sanitize user input to prevent SQL injection
    $firstName = mysqli_real_escape_string($conn, trim($_POST["firstName"]));
    $lastName = mysqli_real_escape_string($conn, trim($_POST["lastName"]));
    $email = mysqli_real_escape_string($conn, trim($_POST["email"]));
    $phoneNumber = mysqli_real_escape_string($conn, trim($_POST["phoneNumber"]));
    $faculty = mysqli_real_escape_string($conn, trim($_POST["faculty"]));
    $dateRegistered = date("Y-m-d");

    // Check if lecture already exists (case-insensitive)
    $stmt = $conn->prepare("SELECT * FROM tbllecture WHERE LOWER(emailAddress) = LOWER(?)");
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $ret = $stmt->get_result()->fetch_assoc();
    
    if ($ret) { 
        die("Lecture Already Exists");
    } else {
        // Generate a temporary password
        $tempPassword = generateTempPassword();
        $hashedTempPassword = password_hash($tempPassword, PASSWORD_DEFAULT);

        // Create a variable for password reset required
        $passwordResetRequired = 1; // 1 means password reset required

        // Insert new lecture with password and passwordResetRequired fields
        $stmt_insert = $conn->prepare("INSERT INTO tbllecture (firstName, lastName, emailAddress, phoneNo, facultyCode, dateCreated, password, passwordResetRequired) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt_insert) {
            die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }
        $stmt_insert->bind_param("sssssssi", $firstName, $lastName, $email, $phoneNumber, $faculty, $dateRegistered, $hashedTempPassword, $passwordResetRequired);

        if ($stmt_insert->execute()) {
            // Send the temporary password via email
            $mail = new PHPMailer(true);
            try {
                // SMTP configuration
                $mail->SMTPDebug = 0; // Set to 2 for detailed debugging
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // Replace with your SMTP server
                $mail->SMTPAuth   = true;
                $mail->Username = 'cvsuimus.alumniassoc@gmail.com'; // SMTP username
                $mail->Password = 'kwwy jeip tgbx ppea'; // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587; // TCP port

                // Recipients
                $mail->setFrom('your-email@gmail.com', 'KabsuAttS System');
                $mail->addAddress($email, "$firstName $lastName");

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Your Temporary Password';
                $mail->Body    = "
                    <h3>Welcome to KabsuAttS</h3>
                    <p>Dear $firstName $lastName,</p>
                    <p>Your account has been created. Please use the temporary password below to log in and set your personal password:</p>
                    <p><strong>Temporary Password:</strong> $tempPassword</p>
                    <p>Best regards,<br>KabsuAttS Team</p>
                ";

                $mail->send();
                // Success message
                $message = "Lecture Added Successfully. Temporary password has been sent to the email.";
                // After adding, redirect to the same page to refresh the list
                header("Location: " . $_SERVER['PHP_SELF'] . "?message=" . urlencode($message));
                exit();
            } catch (Exception $e) {
                die("Lecture added, but email could not be sent. Mailer Error: {$mail->ErrorInfo}");
            }
        } else {
            die("Execute failed: (" . $stmt_insert->errno . ") " . $stmt_insert->error);
        }
        $stmt_insert->close();
    }
    $stmt->close();
}

// Handle Edit Lecture
if (isset($_POST["editLecture"])) {
    // Sanitize user input
    $instructorId = intval($_POST["instructorId"]);
    $firstName = mysqli_real_escape_string($conn, trim($_POST["firstName"]));
    $lastName = mysqli_real_escape_string($conn, trim($_POST["lastName"]));
    $email = mysqli_real_escape_string($conn, trim($_POST["email"]));
    $phoneNumber = mysqli_real_escape_string($conn, trim($_POST["phoneNumber"]));
    $faculty = mysqli_real_escape_string($conn, trim($_POST["faculty"]));

    // Check if the email is being updated to an existing one
    $stmt = $conn->prepare("SELECT * FROM tbllecture WHERE LOWER(emailAddress) = LOWER(?) AND Id != ?");
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $stmt->bind_param("si", $email, $instructorId);
    $stmt->execute();
    $ret = $stmt->get_result()->fetch_assoc();

    if ($ret) { 
        die("Another Lecture with this email already exists");
    } else {
        // Update the lecture details
        $stmt_update = $conn->prepare("UPDATE tbllecture SET firstName = ?, lastName = ?, emailAddress = ?, phoneNo = ?, facultyCode = ? WHERE Id = ?");
        if (!$stmt_update) {
            die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        }
        $stmt_update->bind_param("sssssi", $firstName, $lastName, $email, $phoneNumber, $faculty, $instructorId);

        if ($stmt_update->execute()) {
            // Success message
            $message = "Lecture Updated Successfully.";
            // After updating, redirect to the same page to refresh the list and trigger alert
            header("Location: " . $_SERVER['PHP_SELF'] . "?message=" . urlencode($message) . "&action=update");
            exit();
        } else {
            die("Execute failed: (" . $stmt_update->errno . ") " . $stmt_update->error);
        }
        $stmt_update->close();
    }
    $stmt->close();
}

// Handle Delete Lecture
if (isset($_POST["deleteLecture"])) {
    $instructorId = intval($_POST["Id"]); // Changed from instructorId to Id

    // Check if Id is valid
    if ($instructorId <= 0) {
        echo "<script>alert('Invalid Instructor ID.');</script>";
        exit();
    }

    // Prepare and execute the delete query
    $deleteStmt = $conn->prepare("DELETE FROM tbllecture WHERE Id = ?");
    if (!$deleteStmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }
    $deleteStmt->bind_param("i", $instructorId);

    if ($deleteStmt->execute()) {
        // Success: Redirect to refresh the page
        $message = "Lecture deleted successfully.";
        header("Location: " . $_SERVER['PHP_SELF'] . "?message=" . urlencode($message));
        exit();
    } else {
        // Error during execution
        echo "<script>alert('Failed to delete lecture. Error: " . $deleteStmt->error . "');</script>";
    }
    $deleteStmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="Includes/pic/cvsu.png" rel="icon">
    <title>Admin | Manage Instructor</title>
    <!-- External CSS -->
    <link rel="stylesheet" href="css/styles.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Remixicon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
    
    <!-- Inline CSS for Modals and Buttons -->
    <style>
        /* Apply box-sizing to all elements */
        *, *::before, *::after {
            box-sizing: border-box;
        }

        /* Overlay */
        #overlay {
            position: fixed;
            display: none; /* Hidden by default */
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            background-color: rgba(0,0,0,0.5); /* Black with opacity */
            z-index: 999; /* Sit on top */
            cursor: pointer;
        }

        /* Modal Container */
        .form-popup {
            position: fixed;
            display: none; /* Hidden by default */
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000; /* Sit above the overlay */
            background-color: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            width: 90%;
            max-width: 500px;
        }

        /* Form Container */
        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center; /* Center the form fields horizontally */
            width: 100%;
        }

        /* Close Button */
        .form-container .close {
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
        }

        /* Modal Header */
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-bottom: 15px;
        }

        /* Form Inputs */
        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container select {
            width: 100%; /* Ensure inputs take full width of the form container */
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* Submit Button */
        .form-container .submit {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            width: 100%;
            font-size: 16px;
        }

        .form-container .submit:hover {
            background-color: #45a049;
        }

        /* Title Styling */
        .form-title p {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
            text-align: center; /* Center the title text */
        }

        /* Table Styles */
        .table-container {
            padding: 20px;
        }

        .table table {
            width: 100%;
            border-collapse: collapse;
            height: 100%;
        }

        table{
            height: 100%;
        }

        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #f2f2f2;
        }

        /* Buttons */
        .add, .delete, .edit-button {
            background-color: transparent;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #4CAF50; /* Set to green to match submit button */
        }

        .add:hover, .edit-button:hover {
            /* No hover effect to maintain consistency */
        }

        /* Add Instructor Button Styling */
        .add {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 16px;
            margin-left: auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .add i {
            margin-right: 8px;
        }

        /* Delete Button Specific Styling */
        .delete {
            color: red; /* Change delete button color to red */
        }

        .delete:hover {
            /* Optionally, you can add a slight opacity change on hover */
            opacity: 0.8;
        }

        /* Message Div */
        .messageDiv {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px;
            border-radius: 4px;
            z-index: 1001;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            font-size: 16px;
        }

        /* Responsive Design for Modals */
        @media (max-width: 600px) {
            .form-popup {
                padding: 15px 20px;
            }

            .form-container input[type="text"],
            .form-container input[type="email"],
            .form-container select {
                max-width: 100%;
            }

            .form-container .submit {
                max-width: 100%;
            }
        }
    </style>
     <style>
    *{
        font-family:"poppins",sans-serif;
    }    
    .card{
        background-color: #D8FAD1;
    }
    .card--title{
        font-weight: 600;
    }
    .title button{
        background-color: #1D8907;
    }
    th,td{
        padding-left: 40px;
    }
    .table{
        border-radius: 10px;
        height: 100%;
    }
    .table thead{
        background-color: #EBEBEB;
    }
    .table tbody{
        background-color: #F3F3F3;
    }
    /* Table Rows Hover Effect */
.table tbody tr:hover {
    background-color: #f9f9f9;
}

/* Scrollbar Styling */
.table::-webkit-scrollbar {
    width: 8px;
}

.table::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.table::-webkit-scrollbar-thumb {
    background: #bbb;
    border-radius: 10px;
}

.table::-webkit-scrollbar-thumb:hover {
    background: #888;
}
.main--content{
    padding-top: 50px;
}
</style>
</head>
<body>
    <?php include "Includes/topbar.php";?>

    <section class="main">
        <?php include "Includes/sidebar.php";?>
        <div class="main--content"> 
            <div id="overlay"></div>
            <div id="messageDiv" class="messageDiv" style="display:none;"></div>

            <div class="table-container">
                <div class="title" id="addLecture" style="display: flex; align-items: center;">
                    <h2 class="section--title" style="flex-grow: 1;">Instructors</h2>
                    <button class="add"><i class="ri-add-line"></i>Add Instructor</button>
                </div>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Instructor ID</th>
                                <th>Name</th>
                                <th>Email Address</th>
                                <th>Phone No</th>
                                <th>Faculty</th>
                                <th>Date Registered</th>
                                <th>Settings</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM tbllecture";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row["Id"]) . "</td>"; 
                                    echo "<td>" . htmlspecialchars($row["firstName"]) . " " . htmlspecialchars($row["lastName"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["emailAddress"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["phoneNo"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["facultyCode"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["dateCreated"]) . "</td>";
                                    echo "<td>
                                            <button class='edit-button' data-id='" . htmlspecialchars($row["Id"]) . "' data-firstname='" . htmlspecialchars($row["firstName"]) . "' data-lastname='" . htmlspecialchars($row["lastName"]) . "' data-email='" . htmlspecialchars($row["emailAddress"]) . "' data-phone='" . htmlspecialchars($row["phoneNo"]) . "' data-faculty='" . htmlspecialchars($row["facultyCode"]) . "' title='Edit Instructor'><i class='ri-pencil-line'></i></button>
                                            <form method='POST' action='' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this instructor?\");'>
                                                <input type='hidden' name='Id' value='" . htmlspecialchars($row["Id"]) . "'>
                                                <button type='submit' name='deleteLecture' class='delete' title='Delete Instructor'><i class='ri-delete-bin-line'></i></button>
                                            </form>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='7'>No records found</td></tr>"; 
                            }
                            ?>                     
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add Lecture Form -->
            <div id="addLectureForm" class="form-popup">
                <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" name="addLecture" enctype="multipart/form-data" class="form-container">
                    <div class="modal-header">
                        <div class="form-title">
                            <p>Add Instructor</p>
                        </div>
                        <div>
                            <span class="close add-close" aria-label="Close">&times;</span>
                        </div>
                    </div>
                    <input type="text" name="firstName" id="add-firstName" placeholder="First Name" required>
                    <input type="text" name="lastName" id="add-lastName" placeholder="Last Name" required>
                    <input type="email" name="email" id="add-email" placeholder="Email Address" required>
                    <input type="text" name="phoneNumber" id="add-phoneNumber" placeholder="Phone Number" required>
                    <select required name="faculty" id="add-faculty">
                        <option value="" selected>Select Faculty</option>
                        <?php
                        foreach ($facultyNames as $faculty) {
                            echo '<option value="' . htmlspecialchars($faculty["facultyCode"]) . '">' . htmlspecialchars($faculty["facultyName"]) . '</option>';
                        }
                        ?>
                    </select>
                    <input type="submit" class="submit" value="Add Instructor" name="addLecture">
                </form>       
            </div>

            <!-- Edit Lecture Form -->
            <div id="editLectureForm" class="form-popup">
                <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" name="editLecture" enctype="multipart/form-data" class="form-container">
                    <div class="modal-header">
                        <div class="form-title">
                            <p>Edit Instructor</p>
                        </div>
                        <div>
                            <span class="close edit-close" aria-label="Close">&times;</span>
                        </div>
                    </div>
                    <input type="hidden" name="instructorId" id="edit-instructorId">
                    <input type="text" name="firstName" id="edit-firstName" placeholder="First Name" required>
                    <input type="text" name="lastName" id="edit-lastName" placeholder="Last Name" required>
                    <input type="email" name="email" id="edit-email" placeholder="Email Address" required>
                    <input type="text" name="phoneNumber" id="edit-phoneNumber" placeholder="Phone Number" required>
                    <select required name="faculty" id="edit-faculty">
                        <option value="" selected>Select Faculty</option>
                        <?php
                        foreach ($facultyNames as $faculty) {
                            echo '<option value="' . htmlspecialchars($faculty["facultyCode"]) . '">' . htmlspecialchars($faculty["facultyName"]) . '</option>';
                        }
                        ?>
                    </select>
                    <input type="submit" class="submit" value="Update Instructor" name="editLecture">
                </form>       
            </div>
        </div>
    </section>

    <!-- External JavaScript Files -->
    <script src="javascript/main.js"></script>
    <script src="javascript/addLecture.js"></script>
    <script src="./javascript/confirmation.js"></script>

    <!-- Inline JavaScript for Handling Modals -->
    <script>
        // Function to show message and alert
        function showMessage(message, action = '') {
            const messageDiv = document.getElementById('messageDiv');
            messageDiv.textContent = message;
            messageDiv.style.display = 'block';
            // Show alert if action is update
            if (action === 'update') {
                alert(message);
            }
            // Hide after 3 seconds
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 3000);
        }

        // Handle Add Lecture Form Display
        document.querySelector('.add').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('addLectureForm').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        });

        // Close Add Lecture Form
        document.querySelector('.add-close').addEventListener('click', function() {
            document.getElementById('addLectureForm').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        });

        // Handle Edit Lecture Form Display
        const editButtons = document.querySelectorAll('.edit-button');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Populate the edit form with data attributes
                document.getElementById('edit-instructorId').value = this.getAttribute('data-id');
                document.getElementById('edit-firstName').value = this.getAttribute('data-firstname');
                document.getElementById('edit-lastName').value = this.getAttribute('data-lastname');
                document.getElementById('edit-email').value = this.getAttribute('data-email');
                document.getElementById('edit-phoneNumber').value = this.getAttribute('data-phone');
                document.getElementById('edit-faculty').value = this.getAttribute('data-faculty');

                // Show the edit form and overlay
                document.getElementById('editLectureForm').style.display = 'block';
                document.getElementById('overlay').style.display = 'block';
            });
        });

        // Close Edit Lecture Form
        document.querySelector('.edit-close').addEventListener('click', function() {
            document.getElementById('editLectureForm').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        });

        // Close modals when clicking outside the form
        window.addEventListener('click', function(event) {
            const addForm = document.getElementById('addLectureForm');
            const editForm = document.getElementById('editLectureForm');
            const overlay = document.getElementById('overlay');
            if (event.target === overlay) {
                addForm.style.display = 'none';
                editForm.style.display = 'none';
                overlay.style.display = 'none';
            }
        });

        // Display messages based on URL parameters
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if(urlParams.has('message')){
                const message = urlParams.get('message');
                const action = urlParams.get('action') || '';
                showMessage(message, action);
            }
        }
    </script>

    <?php 
    if(isset($_GET['message'])){
        $sanitizedMessage = htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8');
        $action = isset($_GET['action']) ? htmlspecialchars($_GET['action'], ENT_QUOTES, 'UTF-8') : '';
        echo "<script>showMessage('" . addslashes($sanitizedMessage) . "', '" . addslashes($action) . "');</script>";
    } 
    ?>
</body>
</html>