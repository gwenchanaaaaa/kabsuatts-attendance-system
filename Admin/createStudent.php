<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';
require 'vendor/autoload.php'; // PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

function getCourseNames($conn) {
    $sql = "SELECT courseCode, name FROM tblcourse";
    $result = $conn->query($sql);
    $courseNames = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $courseNames[] = $row;
        }
    }
    return $courseNames;
}

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

// Add student logic
if (isset($_POST['submitStudent'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $registrationNumber = mysqli_real_escape_string($conn, $_POST['registrationNumber']);
    $courseCode = $_POST['course'];
    $faculty = $_POST['faculty'];
    $dateRegistered = date("Y-m-d");

    // Handle captured images from form
    $capturedImage1 = $_POST['capturedImage1'] ?? null;
    $capturedImage2 = $_POST['capturedImage2'] ?? null;

    if ($capturedImage1 && $capturedImage2) {
        $base64Data1 = explode(',', $capturedImage1)[1];
        $base64Data2 = explode(',', $capturedImage2)[1];
        $imageData1 = base64_decode($base64Data1);
        $imageData2 = base64_decode($base64Data2);
        $folderPath = "../Lecture/labels/{$registrationNumber}/";
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
        file_put_contents($folderPath . '1.png', $imageData1);
        file_put_contents($folderPath . '2.png', $imageData2);
    }

    if (isset($_POST['studentId']) && $_POST['studentId'] != '') {
        // Edit existing student
        $studentId = $_POST['studentId'];
        $query = "UPDATE tblstudents SET firstName='$firstName', lastName='$lastName', email='$email', registrationNumber='$registrationNumber', faculty='$faculty', courseCode='$courseCode' WHERE Id='$studentId'";
        mysqli_query($conn, $query);
        $message = "Student updated successfully!";
    } else {
        // Add new student
        $query = mysqli_query($conn, "SELECT * FROM tblstudents WHERE registrationNumber = '$registrationNumber'");
        $ret = mysqli_fetch_array($query);

        if (!empty($ret)) {
            $message = "Student with the given Registration No: $registrationNumber already exists!";
        } else {
            $query = mysqli_query($conn, "INSERT INTO tblstudents (firstName, lastName, email, registrationNumber, faculty, courseCode, dateRegistered) 
            VALUES ('$firstName', '$lastName', '$email', '$registrationNumber', '$faculty', '$courseCode', '$dateRegistered')");
            $message = "Student : $registrationNumber added successfully";
        }
    }
}

if (isset($_POST['uploadExcel'])) {
    $file = $_FILES['excelFile']['tmp_name'];
    
    if ($file) {
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        $rows = array_slice($rows, 7); // Skip header

        foreach ($rows as $row) {
            $registrationNumber = mysqli_real_escape_string($conn, $row[0]);
            $firstName = $row[1];
            $lastName = $row[2];
            $email = $row[3];
            $faculty = $row[4];
            $courseCode = $row[5];
            $dateRegistered = date("Y-m-d");

            $query = mysqli_query($conn, "SELECT * FROM tblstudents WHERE registrationNumber = '$registrationNumber'");
            $ret = mysqli_fetch_array($query);

            if (empty($ret)) {
                $query = mysqli_query($conn, "INSERT INTO tblstudents(firstName, lastName, email, registrationNumber, faculty, courseCode, dateRegistered) 
                VALUES ('$firstName', '$lastName', '$email', '$registrationNumber', '$faculty', '$courseCode', '$dateRegistered')");
            }
        }
        $message = "Students uploaded successfully!";
    } else {
        $message = "Please upload a valid Excel file.";
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['Id'])) {
    $studentId = $_GET['Id'];
    $deleteQuery = "DELETE FROM tblstudents WHERE Id = $studentId";
    if (mysqli_query($conn, $deleteQuery)) {
        $message = "Student deleted successfully!";
    } else {
        $message = "Failed to delete student.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="Includes/pic/cvsu.png" rel="icon">
    <title>Admin | Manage Room</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
    <style>
        /* Your existing styles here */
        * {
            font-family: "poppins", sans-serif;
        }
        .card {
            background-color: #D8FAD1;
        }
        .card--title {
            font-weight: 600;
        }
        .title button {
            background-color: #1D8907;
        }
        th, td {
            padding-left: 40px;
        }
        .table {
            border-radius: 10px;
            height: 100%;
        }
        .table thead {
            background-color: #EBEBEB;
        }
        .table tbody {
            background-color: #F3F3F3;
        }
        .table tbody tr:hover {
            background-color: #f9f9f9;
        }
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
        .main--content {
            padding-top: 50px;
        }
        #addStudentForm {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 25px 30px;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            width: 900px;
            height: auto;
            max-height: 800px;
        }
        #studentForm {
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .form-container {
            display: flex;
            flex-direction: column;
            gap: 0;
            padding: 0 15px;
        }
        .form-row {
            display: flex;
            gap: 40px;
            width: 100%;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .form-group label {
            display: block;
            margin: 0;
            font-weight: 600;
            color: #333;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .form-group input,
        .form-group select {
            width: 100%;
            height: 35px;
            padding: 8px 10px;
            border: 1px solid #E4E6EB;
            border-radius: 6px;
            font-size: 14px;
            line-height: 1.5;
            background-color: #F0F2F5;
            color: #65676B;
        }
        .form-group input::placeholder {
            color: #65676B;
            opacity: 1;
        }
        .form-group select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2365676B' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 30px;
        }
        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #1D8907;
            box-shadow: 0 0 0 2px rgba(29, 137, 7, 0.1);
        }
        .camera-container {
            display: flex;
            gap: 15px;
            justify-content: space-between;
            margin: 10px 0 5px;
            padding-top: 10px;
            padding-right: 10px;
            border-top: 1px solid #eee;
        }
        .camera-box {
            flex: 1;
            text-align: center;
            padding-right: 5px;
        }
        .camera-box h3 {
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .camera-box video,
        .camera-box canvas {
            width: 300px;
            height: 225px;
            border: 2px solid #ddd;
            border-radius: 8px;
            margin-bottom: 8px;
        }
        .camera-box button {
            background: #1D8907;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            height: 32px;
        }
        .camera-box button:hover {
            background: #166906;
        }
        .form-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 15px;
            margin-top: 5px;
            border-top: 1px solid #eee;
        }
        .form-buttons button {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            height: 35px;
            min-width: 80px;
            font-size: 14px;
        }
        .submit-btn {
            background: #1D8907;
            color: white;
        }
        .cancel-btn {
            background: #666;
            color: white;
        }
        .close-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            background: none;
            border: none;
            font-size: 22px;
            cursor: pointer;
            color: #666;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .close-btn:hover {
            color: #333;
        }
        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
        .title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .title .button-group {
            display: flex;
            gap: 10px;
            margin-left: auto;
        }
        .add {
            background-color: #1D8907;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 14px;
            height: 35px;
        }
        .add i {
            font-size: 16px;
        }
        .add:hover {
            background-color: #166906;
        }
        #excelUploadForm {
            display: inline-flex;
            align-items: center;
        }
        #uploadButton {
            height: 35px;
            white-space: nowrap;
        }
        .modal-header {
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .modal-header h2 {
            color: #1D8907;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }
    </style>
</head>

<body>
<?php include 'includes/topbar.php' ?>
<section class="main">
    <?php include 'includes/sidebar.php'; ?>
    <div class="main--content">
        <div id="overlay"></div>
        <div id="messageDiv" class="messageDiv" style="display:none;"></div>

    <div class="table-container">
        <div class="title">
            <h2 class="section--title">Students</h2>
            <div class="button-group">
                <button class="add" onclick="showForm()"><i class="ri-add-line"></i>Add Student</button>
                <form id="excelUploadForm" action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="uploadExcel" value="1">
                    <button type="button" class="add" id="uploadButton"><i class="ri-file-excel-line"></i>Upload Students (Excel)</button>
                    <input type="file" name="excelFile" accept=".xls,.xlsx" id="excelFileInput" style="display:none;" required />
                </form>
            </div>
        </div>

        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>Registration No</th>
                        <th>Name</th>
                        <th>Faculty</th>
                        <th>Course</th>
                        <th>Email</th>
                        <th>Settings</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT * FROM tblstudents";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$row['registrationNumber']}</td>";
                            echo "<td>{$row['firstName']} {$row['lastName']}</td>";
                            echo "<td>{$row['faculty']}</td>";
                            echo "<td>{$row['courseCode']}</td>";
                            echo "<td>{$row['email']}</td>";
                            echo '<td>
    <button class="edit-button" 
        data-id="' . $row['Id'] . '" 
        data-first-name="' . $row['firstName'] . '" 
        data-last-name="' . $row['lastName'] . '" 
        data-email="' . $row['email'] . '" 
        data-registration-number="' . $row['registrationNumber'] . '" 
        data-faculty="' . $row['faculty'] . '" 
        data-course="' . $row['courseCode'] . '">
        <i class="ri-edit-line edit"></i> 
    </button>
    <a href="?Id=' . $row['Id'] . '&action=delete" onclick="return confirm(\'Are you sure?\');">
        <i class="ri-delete-bin-line delete"></i>
    </a>
</td>';
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Updated Add Student Form -->
        <div id="addStudentForm">
            <button class="close-btn" onclick="closeForm()">&times;</button>
            <div class="modal-header">
                <h2>Add New Student</h2>
            </div>
            <form method="POST" id="studentForm">
                <input type="hidden" name="studentId" id="studentId">
                <div class="form-container">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" name="firstName" id="firstName" required placeholder="Enter first name">
                        </div>
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" name="lastName" id="lastName" required placeholder="Enter last name">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" required placeholder="Enter email address">
                        </div>
                        <div class="form-group">
                            <label for="registrationNumber">Registration Number</label>
                            <input type="text" name="registrationNumber" id="registrationNumber" required placeholder="Enter registration number">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="faculty">Faculty</label>
                            <select name="faculty" id="faculty" required>
                                <option value="">Select Faculty</option>
                                <?php
                                $facultyNames = getFacultyNames($conn);
                                foreach ($facultyNames as $faculty) {
                                    echo '<option value="' . $faculty["facultyCode"] . '">' . $faculty["facultyName"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="course">Course</label>
                            <select name="course" id="course" required>
                                <option value="">Select Course</option>
                                <?php
                                $courseNames = getCourseNames($conn);
                                foreach ($courseNames as $course) {
                                    echo '<option value="' . $course["courseCode"] . '">' . $course["name"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="camera-container">
                        <div class="camera-box">
                            <h3>First Picture</h3>
                            <video id="video1" autoplay playsinline></video>
                            <canvas id="canvas1" style="display:none;"></canvas>
                            <input type="hidden" name="capturedImage1" id="capturedImage1">
                            <button type="button" onclick="captureImage(1)">Capture Image 1</button>
                        </div>
                        <div class="camera-box">
                            <h3>Second Picture</h3>
                            <video id="video2" autoplay playsinline></video>
                            <canvas id="canvas2" style="display:none;"></canvas>
                            <input type="hidden" name="capturedImage2" id="capturedImage2">
                            <button type="button" onclick="captureImage(2)">Capture Image 2</button>
                        </div>
                    </div>
                </div>

                <div class="form-buttons">
                    <button type="button" class="cancel-btn" onclick="closeForm()">Cancel</button>
                    <button type="submit" name="submitStudent" class="submit-btn">Save Student</button>
                </div>
            </form>
        </div>
    </div>
</div>
</section>

<script src="js/script.js"></script>
<script>
document.getElementById('uploadButton').addEventListener('click', function() {
    document.getElementById('excelFileInput').click();
});

document.getElementById('excelFileInput').addEventListener('change', function() {
    if (this.files.length > 0) {
        document.getElementById('excelUploadForm').submit();
    }
});

// Edit button functionality
document.querySelectorAll('.edit-button').forEach(button => {
    button.addEventListener('click', function() {
        const form = document.getElementById('addStudentForm');
        document.getElementById('studentId').value = this.dataset.id;
        document.getElementById('firstName').value = this.dataset.firstName;
        document.getElementById('lastName').value = this.dataset.lastName;
        document.getElementById('email').value = this.dataset.email;
        document.getElementById('registrationNumber').value = this.dataset.registrationNumber;
        document.getElementById('faculty').value = this.dataset.faculty;
        document.getElementById('course').value = this.dataset.course;
        
        showForm();
    });
});

// Show message if it exists
<?php if(isset($message)): ?>
    const messageDiv = document.getElementById('messageDiv');
    messageDiv.textContent = <?php echo json_encode($message); ?>;
    messageDiv.style.display = 'block';
    setTimeout(() => {
        messageDiv.style.display = 'none';
    }, 3000);
<?php endif; ?>
</script>

</body>
</html>
