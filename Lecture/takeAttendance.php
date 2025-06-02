<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../Includes/dbcon.php';
include 'includes/session.php';

function getCourseNames($conn) {
    $sql = "SELECT courseCode, name FROM tblcourse";
    $result = $conn->query($sql);
    $courseNames = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $courseNames[] = $row;
        }
    }
    return $courseNames;
}

function getVenueNames($conn) {
    $sql = "SELECT className FROM tblvenue";
    $result = $conn->query($sql);
    $venueNames = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $venueNames[] = $row;
        }
    }
    return $venueNames;
}

function getUnitNames($conn) {
    $sql = "SELECT unitCode, name FROM tblunit";
    $result = $conn->query($sql);
    $unitNames = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $unitNames[] = $row;
        }
    }
    return $unitNames;
}

// Handle attendance POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attendanceData = json_decode(file_get_contents("php://input"), true);

    if (!empty($attendanceData)) {
        foreach ($attendanceData as $data) {
            $studentID = $data['studentID'];
            $attendanceStatus = $data['attendanceStatus'];
            $course = $data['course'];
            $unit = $data['unit'];
            $date = date("Y-m-d");

            $sql = "INSERT INTO tblattendance(studentRegistrationNumber, course, unit, attendanceStatus, dateMarked)  
                    VALUES ('$studentID', '$course', '$unit', '$attendanceStatus', '$date')";

            if (!$conn->query($sql)) {
                echo "Error inserting attendance data: " . $conn->error . "<br>";
            }
        }
    } else {
        echo "No attendance data received.<br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link href="Includes/pic/cvsu.png" rel="icon" />
  <title>KabsuAttS | Instructor</title>
  <link rel="stylesheet" href="css/styles.css" />

  <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet" />

  <style>
    * {
      font-family: "poppins", sans-serif;
    }

    .attendance-button button {
      background-color: #1d8907;
    }

    select,
    option {
      background-color: rgb(208, 247, 200);
    }

    #messageDiv {
      display: none;
    }

    .video-container {
      display: none;
      background: #000;
      padding: 20px;
      border-radius: 8px;
      margin: 20px auto;
      position: relative;
      width: 640px;
      height: 480px;
    }

    #video {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    canvas {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
    }

    .messageDiv {
      position: fixed;
      top: 20px;
      right: 20px;
      background: rgba(0, 0, 0, 0.8);
      color: white;
      padding: 10px 20px;
      border-radius: 5px;
      z-index: 1000;
      display: none;
    }

    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 1000;
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      width: 400px;
      position: relative;
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .modal-header h2 {
      margin: 0;
      color: #1d8907;
    }

    .close-modal {
      cursor: pointer;
      font-size: 24px;
      color: #666;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      color: #333;
    }

    .form-group input, .form-group select {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
      box-sizing: border-box;
    }

    .modal-buttons {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 20px;
    }

    .modal-buttons button {
      padding: 8px 15px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .save-btn {
      background-color: #1d8907;
      color: white;
    }

    .cancel-btn {
      background-color: #666;
      color: white;
    }
  </style>
</head>

<body>
  <?php include 'includes/topbar.php'; ?>
  <section class="main">
    <?php include 'includes/sidebar.php'; ?>
    
    <!-- Add the modal HTML -->
    <div id="editModal" class="modal">
      <div class="modal-content">
        <div class="modal-header">
          <h2>Edit Student Details</h2>
          <span class="close-modal">&times;</span>
        </div>
        <form id="editForm">
          <input type="hidden" id="editStudentId">
          <div class="form-group">
            <label for="editFirstName">First Name:</label>
            <input type="text" id="editFirstName" required>
          </div>
          <div class="form-group">
            <label for="editLastName">Last Name:</label>
            <input type="text" id="editLastName" required>
          </div>
          <div class="form-group">
            <label for="editAttendance">Attendance Status:</label>
            <select id="editAttendance" required>
              <option value="Present">Present</option>
              <option value="Absent">Absent</option>
            </select>
          </div>
          <div class="modal-buttons">
            <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
            <button type="submit" class="save-btn">Save Changes</button>
          </div>
        </form>
      </div>
    </div>

    <div class="main--content">

      <div id="messageDiv" class="messageDiv"></div>

      <form class="lecture-options" id="selectForm">
        <select required name="course" id="courseSelect" onChange="updateTable()">
          <option value="" selected>Select Course</option>
          <?php
          $courseNames = getCourseNames($conn);
          foreach ($courseNames as $course) {
            echo '<option value="' . $course["courseCode"] . '">' . $course["name"] . '</option>';
          }
          ?>
        </select>

        <select required name="unit" id="unitSelect" onChange="updateTable()">
          <option value="" selected>Select Unit</option>
          <?php
          $unitNames = getUnitNames($conn);
          foreach ($unitNames as $unit) {
            echo '<option value="' . $unit["unitCode"] . '">' . $unit["name"] . '</option>';
          }
          ?>
        </select>

        <select required name="venue" id="venueSelect" onChange="updateTable()">
          <option value="" selected>Select Venue</option>
          <?php
          $venueNames = getVenueNames($conn);
          foreach ($venueNames as $venue) {
            echo '<option value="' . $venue["className"] . '">' . $venue["className"] . '</option>';
          }
          ?>
        </select>
      </form>

      <div class="attendance-button">
        <button id="startButton" class="add">Launch Facial Recognition</button>
        <button id="endButton" class="add" style="display:none">End Attendance Process</button>
        <button id="endAttendance" class="add">END Attendance Taking</button>
      </div>

      <div class="video-container">
        <video id="video" autoplay muted></video>
        <canvas id="overlay"></canvas>
      </div>

      <div class="table-container">
        <div id="studentTableContainer"></div>
      </div>

    </div>
  </section>

  <!-- Load face-api.js and your script with defer -->
  <script defer src="face-api.min.js"></script>
  <script defer src="script.js"></script>
  <script defer src="attendanceActions.js"></script>
  <!-- Temporarily comment out conflicting script -->
  <!-- <script defer src="../admin/javascript/main.js"></script> -->

</body>

</html>