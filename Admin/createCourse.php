<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';

function getFacultyNames($conn) {
    $sql = "SELECT Id, facultyName FROM tblfaculty";
    $result = $conn->query($sql);

    $facultyNames = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $facultyNames[] = $row;
        }
    }

    return $facultyNames;
}

function getLectureNames($conn) {
    $sql = "SELECT instructorId, firstName, lastName FROM tbllecture";
    $result = $conn->query($sql);

    $lectureNames = array();  
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $lectureNames[] = $row;
        }
    }

    return $lectureNames;
}

function getCourseNames($conn) {
    $sql = "SELECT ID,name FROM tblcourse";
    $result = $conn->query($sql);

    $courseNames = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $courseNames[] = $row;
        }
    }

    return $courseNames;
}

if (isset($_POST["addCourse"])) {
    $courseName = $_POST["courseName"];
    $courseCode = $_POST["courseCode"];
    $facultyID = $_POST["faculty"];
    $dateRegistered = date("Y-m-d");

    $query = mysqli_query($conn, "SELECT * from tblcourse where courseCode='$courseCode'");
    $ret = mysqli_fetch_array($query);
    if ($ret > 0) { 
        $message = " Course Already Exists";
    } else {
        $query = mysqli_query($conn, "INSERT INTO tblcourse(name, courseCode, facultyID, dateCreated) 
            VALUES ('$courseName','$courseCode','$facultyID','$dateRegistered')");
        $message = " Course Inserted Successfully";
    }
}

if (isset($_POST["addUnit"])) {
    $unitName = $_POST["unitName"];
    $unitCode = $_POST["unitCode"];
    $courseID = $_POST["course"];
    $dateRegistered = date("Y-m-d");

    $query = mysqli_query($conn, "SELECT * from tblunit where unitCode='$unitCode'");
    $ret = mysqli_fetch_array($query);
    if ($ret > 0) { 
        $message = "Subject Already Exists";
    } else {
        $query = mysqli_query($conn, "INSERT INTO tblunit(name, unitCode, courseID, dateCreated) 
            VALUES ('$unitName','$unitCode','$courseID','$dateRegistered')");
        $message = "Subject Inserted Successfully";
    }
}

if (isset($_POST["addFaculty"])) {
    $facultyName = $_POST["facultyName"];
    $facultyCode = $_POST["facultyCode"];
    $dateRegistered = date("Y-m-d");

    $query = mysqli_query($conn, "SELECT * from tblfaculty where facultyCode='$facultyCode'");
    $ret = mysqli_fetch_array($query);
    if ($ret > 0) { 
        $message = " Department already Exists";
    } else {
        $query = mysqli_query($conn, "INSERT INTO tblfaculty(facultyName, facultyCode, dateRegistered) 
            VALUES ('$facultyName','$facultyCode','$dateRegistered')");
        $message = " Department Inserted Successfully";
    }
}

// Handle Course and Unit deletion
if (isset($_POST['deleteCourse'])) {
    $courseName = $_POST['course_name'];
    $query = mysqli_query($conn, "DELETE FROM tblcourse WHERE name='$courseName'");
    $message = "Course Deleted Successfully";
}

if (isset($_POST['deleteUnit'])) {
    $unitCode = $_POST['unit_code'];
    $query = mysqli_query($conn, "DELETE FROM tblunit WHERE unitCode='$unitCode'");
    $message = "Unit Deleted Successfully";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="Includes/pic/cvsu.png" rel="icon">
  <title>Admin | Manage Course</title>
  <link rel="stylesheet" href="css/styles.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
  <script src="./javascript/confirmation.js" defer></script>
<style>
    /* Your previous styles */
    * {
      font-family: "poppins", sans-serif;
    }

    .card {
      background-color: #D8FAD1;
    }

    .card--title {
      font-weight: 600;
    }

    .card--data button {
      background-color: #1D8907;
    }

    th,
    td {
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

    /* Tabs Style */
    .tabs {
      display: flex;
      cursor: pointer;
      border-bottom: 2px solid #ddd;
    }

    .tab {
      padding: 10px 20px;
      margin-right: 10px;
      background-color: #f1f1f1;
      border-radius: 5px 5px 0 0;
      transition: background-color 0.3s ease;
    }

    .tab:hover {
      background-color: #ddd;
    }

    .tab.active {
      background-color: #66A659;
      color: white;
    }

    .tab-content {
      display: none;
      padding: 20px;
      background-color: #fff;
    }

    .tab-content.active {
      display: block;
    }

    .main--content {
      padding-top: 50px;
    }

    /* General Modal Styling */
    #addCourseForm, #addUnitForm, #addFacultyForm{
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-80%, -50%);
      background: white;
      padding: 20px;
      z-index: 1000;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      border-radius: 8px;
      width: 400px;
      max-height: 80%;
      overflow-y: auto;
    }
    
 
#editCourseForm, #editUnitForm, #editDepartmentForm {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-40%, -50%);
      background: white;
      padding: 20px;
      z-index: 1000;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      border-radius: 8px;
      width: 400px;
      max-height: 80%;
      overflow-y: auto;
    }
    

    /* Overlay to dim the background when modal is active */
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

    /* Form Fields Styling */
    .form-container input[type="text"],
    .form-container input[type="number"],
    .form-container input[type="email"],
    .form-container select {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ddd;
      border-radius: 5px;
      background-color: #D8FAD1 !important; /* Ensure light green background with !important */
    }

    .form-container .submit {
      width: 100%;
      padding: 10px;
      background-color: #28a745;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }

    .form-container .submit:hover {
      background-color: #218838;
    }

    /* Modal Close Button Styling */
    .close {
      font-size: 24px;
      cursor: pointer;
      background: none;
      border: none;
    }

    /* Modal Header Styling */
    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .form-title p {
      font-size: 20px;
      font-weight: bold;
    }
</style>

</head>
<body>
  <?php include 'includes/topbar.php' ?>
  <section class="main">
    <?php include 'includes/sidebar.php'; ?>
    <div class="main--content">
      <div class="overview">
        <div class="title">
          <h2 class="section--title">Overview</h2>
        </div>
        <div class="cards">
          <div id="addCourse" class="card card-1">
            <?php 
            $query1 = mysqli_query($conn, "SELECT * from tblcourse");                       
            $courses = mysqli_num_rows($query1);
            ?>
            <div class="card--data">
              <div class="card--content">
                <button class="add"><i class="ri-add-line"></i>Add Course</button>
                <h1><?php echo $courses; ?> Courses</h1>
              </div>
              <i class="ri-user-2-line card--icon--lg" style=" color: #66A659; margin-top:10px"></i>
            </div>
          </div>
          <div class="card card-1" id="addUnit">
            <?php 
            $query1 = mysqli_query($conn, "SELECT * from tblunit");                       
            $unit = mysqli_num_rows($query1);
            ?>
            <div class="card--data">
              <div class="card--content">
                <button class="add"><i class="ri-add-line"></i>Add Subject</button>
                <h1><?php echo $unit; ?> Subjects</h1>
              </div>
              <i class="ri-file-text-line card--icon--lg" style=" color: #66A659; margin-top:10px"></i>
            </div>
          </div>

          <div class="card card-1" id="addFaculty">
            <?php 
            $query1 = mysqli_query($conn, "SELECT * from tblfaculty");                       
            $faculty = mysqli_num_rows($query1);
            ?>
            <div class="card--data">
              <div class="card--content">
                <button class="add"><i class="ri-add-line"></i>Add Department</button>
                <h1><?php echo $faculty; ?> Departments</h1> 
              </div>
              <i class="ri-user-line card--icon--lg" style=" color: #66A659; margin-top:10px"></i>
            </div>
          </div>
        </div>
      </div>
<br><br>
      <!-- Tabs -->
      <div class="tabs">
        <div class="tab active" id="coursesTab">Courses</div>
        <div class="tab" id="subjectsTab">Subjects</div>
        <div class="tab" id="departmentsTab">Departments</div>
      </div>

      <!-- Courses Content -->
      <div class="tab-content active" id="coursesContent">
        <div class="table-container">
          <div class="title">
            <h2 class="section--title">Courses</h2>
          </div>
          <div class="table">
            <table>
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Department</th>
                  <th>Total Subjects</th>
                  <th>Total Students</th>
                  <th>Date Created</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sql = "SELECT 
                        c.name AS course_name,
                        c.facultyID AS faculty,
                        f.facultyName AS faculty_name,
                        COUNT(u.ID) AS total_units,
                        COUNT(DISTINCT s.Id) AS total_students,
                        c.dateCreated AS date_created
                        FROM tblcourse c
                        LEFT JOIN tblunit u ON c.ID = u.courseID
                        LEFT JOIN tblstudents s ON c.courseCode = s.courseCode
                        LEFT JOIN tblfaculty f on c.facultyID=f.Id
                        GROUP BY c.ID";

                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["course_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["faculty_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["total_units"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["total_students"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["date_created"]) . "</td>";
                        echo "<td>
                                <button class='edit-button' 
                                    data-course-name='" . htmlspecialchars($row["course_name"]) . "' 
                                    data-faculty-name='" . htmlspecialchars($row["faculty_name"]) . "' 
                                    data-total-units='" . htmlspecialchars($row["total_units"]) . "' 
                                    data-total-students='" . htmlspecialchars($row["total_students"]) . "' 
                                    title='Edit Course'>
                                    <i class='ri-edit-line'></i>
                                </button>
                                <form method='POST' action='' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this course?\");'>
                                    <input type='hidden' name='course_name' value='" . htmlspecialchars($row["course_name"]) . "'>
                                    <button type='submit' name='deleteCourse' class='delete' title='Delete Course'>
                                        <i class='ri-delete-bin-line'></i>
                                    </button>
                                </form>
                             </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No records found</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
                    <!-- Edit Course Form -->
            <div id="editCourseForm" class="form-popup" style="display: none;">
    <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" name="editCourse" class="form-container">
        <div class="modal-header">
            <div class="form-title">

                <p>Edit Course</p> 
            </div>
            <div>
                <span class="close edit-close" aria-label="Close">&times;</span>
            </div>
        </div>
        <input type="text" name="courseName" id="edit-courseName" placeholder="Course Name" required>
        <input type="text" name="facultyName" id="edit-facultyName" placeholder="Faculty Name" required>
        <input type="number" name="totalUnits" id="edit-totalUnits" placeholder="Total Units" required>
        <input type="number" name="totalStudents" id="edit-totalStudents" placeholder="Total Students" required>
        <input type="submit" class="submit" value="Update Course" name="editCourse">
    </form>
</div>
      </div>

      <!-- Subjects Content -->
      <div class="tab-content" id="subjectsContent">
        <div class="table-container">
          <div class="title">
            <h2 class="section--title">Subjects</h2>
          </div>
          <div class="table">
            <table>
              <thead>
                <tr>
                  <th>Subject Code</th>
                  <th>Name</th>
                  <th>Course</th>
                  <th>Total Students</th>
                  <th>Date Created</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sql = "SELECT 
                        c.name AS course_name,
                        u.unitCode AS unit_code,
                        u.name AS unit_name,
                        u.dateCreated AS date_created,
                        COUNT(s.Id) AS total_students
                        FROM tblunit u
                        LEFT JOIN tblcourse c ON u.courseID = c.ID
                        LEFT JOIN tblstudents s ON c.courseCode = s.courseCode
                        GROUP BY u.ID";                       

                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["unit_code"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["unit_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["course_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["total_students"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["date_created"]) . "</td>";
                        echo "<td>
                                <button class='edit-button' 
                                    data-unit-code='" . htmlspecialchars($row["unit_code"]) . "' 
                                    data-unit-name='" . htmlspecialchars($row["unit_name"]) . "' 
                                    data-course-name='" . htmlspecialchars($row["course_name"]) . "' 
                                    data-total-students='" . htmlspecialchars($row["total_students"]) . "' 
                                    title='Edit Unit'>
                                    <i class='ri-edit-line'></i>
                                </button>
                                <form method='POST' action='' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this unit?\");'>
                                    <input type='hidden' name='unit_code' value='" . htmlspecialchars($row["unit_code"]) . "'>
                                    <button type='submit' name='deleteUnit' class='delete' title='Delete Unit'>
                                        <i class='ri-delete-bin-line'></i>
                                    </button>
                                </form>
                             </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No records found</td></tr>";
                }
                ?>
              </tbody>

   </table>
          </div>
        </div>
<!-- Edit Unit Modal -->
<div id="editUnitForm" class="form-popup" style="display: none;">
    <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" name="editUnit" class="form-container">
        <div class="modal-header">
            <div class="form-title">
                <p>Edit Unit</p>
            </div>
            <button class="close edit-close" aria-label="Close">&times;</button>
        </div>
        <input type="text" name="unitCode" id="edit-unitCode" placeholder="Unit Code" required>
        <input type="text" name="unitName" id="edit-unitName" placeholder="Unit Name" required>
        <input type="text" name="courseName" id="edit-courseName" placeholder="Course Name" required>
        <input type="number" name="totalStudents" id="edit-totalStudents" placeholder="Total Students" required>
        <input type="submit" class="submit" value="Update Unit" name="editUnit">
    </form>
</div>
         
      </div>

<!-- Departments Content -->
<div class="tab-content" id="departmentsContent">
  <div class="table-container">
    <div class="title">
      <h2 class="section--title">Departments</h2>
    </div>
    <div class="table">
      <table>
        <thead>
          <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Total Courses</th>
            <th>Total Students</th>
            <th>Total Instructors</th>
            <th>Date Created</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $sql = "SELECT 
                  f.facultyName AS faculty_name,
                  f.facultyCode AS faculty_code,
                  f.dateRegistered AS date_created,
                  COUNT(DISTINCT c.ID) AS total_courses,
                  COUNT(DISTINCT s.Id) AS total_students,
                  COUNT(DISTINCT l.instructorId) AS total_lectures
                  FROM tblfaculty f
                  LEFT JOIN tblcourse c ON f.Id = c.facultyID
                  LEFT JOIN tblstudents s ON f.facultyCode = s.faculty
                  LEFT JOIN tbllecture l ON f.facultyCode = l.facultyCode
                  GROUP BY f.Id";

          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . $row["faculty_code"] . "</td>";
                  echo "<td>" . $row["faculty_name"] . "</td>";
                  echo "<td>" . $row["total_courses"] . "</td>";
                  echo "<td>" . $row["total_students"] . "</td>";
                  echo "<td>" . $row["total_lectures"] . "</td>";
                  echo "<td>" . $row["date_created"] . "</td>";
                  echo "<td>
        <button class='edit-button department' 
                data-faculty-name='" . htmlspecialchars($row["faculty_name"]) . "' 
                data-faculty-code='" . htmlspecialchars($row["faculty_code"]) . "' 
                title='Edit Department'>
            <i class='ri-edit-line'></i>
        </button>
        <form method='POST' action='' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this department?\");'>
            <input type='hidden' name='faculty_code' value='" . htmlspecialchars($row["faculty_code"]) . "'>
            <button type='submit' name='deleteFaculty' class='delete' title='Delete Department'>
                <i class='ri-delete-bin-line'></i>
            </button>
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
  <!-- Edit Department Modal -->
<div id="editDepartmentForm" class="form-popup" style="display: none;">
  <form method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" name="editDepartment" class="form-container">
    <div class="modal-header">
      <div class="form-title">
        <p>Edit Department</p> 
      </div>
      <div>
        <span class="close edit-close" aria-label="Close">&times;</span>
      </div>
    </div>
    <input type="text" name="facultyName" id="edit-facultyName" placeholder="Department Name" required>
    <input type="text" name="facultyCode" id="edit-facultyCode" placeholder="Department Code" required>
    <input type="submit" class="submit" value="Update Department" name="editDepartment">
  </form>
</div>

</div>




 <div class="formDiv" id="addCourseForm"  style="display:none; ">
        
<form method="POST" action="" name="addCourse" enctype="multipart/form-data">
    <div style="display:flex; justify-content:space-around;">
        <div class="form-title">
            <p>Add Course</p>
        </div>
        <div>
            <span class="close">&times;</span>
        </div>
    </div>

    <input type="text" name="courseName" placeholder="Course Name" required>
    <input type="text" name="courseCode" placeholder="Course Code" required>


    <select required name="faculty">
        <option value="" selected>Select Department</option>
        <?php
        $facultyNames = getFacultyNames($conn);
        foreach ($facultyNames as $faculty) {
            echo '<option value="' . $faculty["Id"] . '">' . $faculty["facultyName"] . '</option>';
        }
        ?>
    </select>

    <input type="submit" class="submit" value="Save Course" name="addCourse">
</form>       
    </div>

<div class="formDiv" id="addUnitForm"  style="display:none; ">
<form method="POST" action="" name="addUnit" enctype="multipart/form-data">
    <div style="display:flex; justify-content:space-around;">
        <div class="form-title">
            <p>Add Subject</p>
        </div>
        <div>
            <span class="close">&times;</span>
        </div>
    </div>

    <input type="text" name="unitName" placeholder="Subject Name" required>
    <input type="text" name="unitCode" placeholder="Subject Code" required>

    <select required name="lecture">
        <option value="" selected>Assign Instructor</option>
        <?php
        $lectureNames = getLectureNames($conn);
        foreach ($lectureNames as $lecture) {
            echo '<option value="' . $lecture["instructorId"] . '">' . $lecture["firstName"] . ' ' . $lecture["lastName"]  .  '</option>';
        }
        ?>
    </select>
    <select required name="course">
        <option value="" selected>Select Course</option>
        <?php
        $courseNames = getCourseNames($conn);
        foreach ($courseNames as $course) {
            echo '<option value="' . $course["ID"] . '">' . $course["name"] . '</option>';
        }
        ?>
    </select>

    <input type="submit" class="submit" value="Save Subject" name="addUnit">
</form>       
 </div>
    
<div class="formDiv" id="addFacultyForm"  style="display:none; ">
<form method="POST" action="" name="addFaculty" enctype="multipart/form-data">
    <div style="display:flex; justify-content:space-around;">
        <div class="form-title">
            <p>Add Department</p>
        </div>
        <div>
            <span class="close">&times;</span>
        </div>
    </div>
    <input type="text" name="facultyName" placeholder="Department Name" required>
    <input type="text" name="facultyCode" placeholder="Department Code" required>
    <input type="submit" class="submit" value="Save Department" name="addFaculty">
</form>       
</div>
      <style>
        #editCourseForm,  {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    z-index: 1000;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    width: 400px;
}
        #editUnitForm {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    z-index: 1000;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    width: 400px;
}
        #editDepartmentForm {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    z-index: 1000;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    width: 400px;
}
                                      
/* Ensure the modal is centered */
#addCourseForm, #addUnitForm, #addFacultyForm, {
    display: none; /* Hidden by default */
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-80%, -50%);
    background: white;
    padding: 20px;
    z-index: 1000;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    width: 400px;
    max-height: 80%;
    overflow-y: auto; /* In case content overflows, scroll within the modal */
}

/* Overlay */
#overlay {
    display: none; /* Hidden by default */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
}

/* Prevent body scroll when modal is active */
body.modal-active {
    overflow: hidden;
}

/* Modal Close Button */
.modal-header .close {
    font-size: 24px;
    cursor: pointer;
    background: none;
    border: none;
    margin-left: 10px;
    padding: 0;
}

/* Input Fields in Modal */
.form-container input[type="text"],
.form-container input[type="number"],
.form-container input[type="email"],
.form-container select {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f5faff;
}

/* Submit Button in Modal */
.form-container .submit {
    width: 100%;
    padding: 10px;
    background-color: #28a745;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.form-container .submit:hover {
    background-color: #218838;
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
.delete, .edit-button {
            background-color: transparent;
            border: none;
            cursor: pointer;
            font-size: 18px;
            color: #4CAF50; /* Set to green to match submit button */
        }

        .edit-button:hover {
            background: none;
        }
        .delete {
            color: red; /* Change delete button color to red */
        }

        .delete:hover {
            background: none;
            opacity: 0.8;
        }
/* General Styling for Modals */
.form-popup {
    width: 400px; /* Ensure the modal is the same width */
    margin: auto;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    text-align: center;
    font-family: Arial, sans-serif;
}

/* Header Styling (Title and Close Button) */
.modal-header {
    display: flex;
    justify-content: space-between; /* Space between title and close button */
    align-items: center; /* Vertical alignment */
    margin-bottom: 15px;
    padding: 0 10px; /* Optional: Add padding for spacing within the modal */
}

/* Title Styling */
.modal-header .form-title {
    flex-grow: 1; /* Ensure the title takes up available space */
}

.modal-header .form-title p {
    font-size: 20px;
    font-weight: bold;
    margin: 0;
}

/* Close Button Styling */
.modal-header .close {
    font-size: 24px;
    cursor: pointer;
    background: none;
    border: none;
    margin-left: 10px; /* Ensure some space from the title */
    padding: 0;
    line-height: 1; /* Keeps the button from stretching */
}


/* Input Fields */
.form-container input[type="text"],
.form-container input[type="number"],
.form-container input[type="email"],
.form-container select {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f5faff; /* Match the reference input background */
}

/* Button Styling */
.form-container .submit {
    width: 100%;
    padding: 10px;
    background-color: #28a745; /* Match the green color */
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.form-container .submit:hover {
    background-color: #218838;
}

</style>
      

</section>
<script src="javascript/main.js"></script>
<script src="javascript/addCourse.js"></script>
<script src="javascript/confirmation.js"></script>
<script>
// Close Edit Course Form
document.querySelector('.edit-close').addEventListener('click', function() {
    document.getElementById('editCourseForm').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
});

// Handle Edit Course Form Display
const editButtons = document.querySelectorAll('.edit-button');
editButtons.forEach(button => {
    button.addEventListener('click', function () {
        // Populate the form fields with data attributes
        document.getElementById('edit-courseName').value = this.getAttribute('data-course-name');
        document.getElementById('edit-facultyName').value = this.getAttribute('data-faculty-name');
        document.getElementById('edit-totalUnits').value = this.getAttribute('data-total-units');
        document.getElementById('edit-totalStudents').value = this.getAttribute('data-total-students');

        // Display the modal and overlay
        document.getElementById('editCourseForm').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
    });
});

// Close the Edit Course Form
document.querySelector('.edit-close').addEventListener('click', function () {
    document.getElementById('editCourseForm').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
});

// Close modals when clicking outside of the form
window.addEventListener('click', function (event) {
    const editForm = document.getElementById('editCourseForm');
    const overlay = document.getElementById('overlay');
    if (event.target === overlay) {
        editForm.style.display = 'none';
        overlay.style.display = 'none';
    }
});

// Close Edit Unit Form
document.querySelector('.edit-close').addEventListener('click', function() {
    document.getElementById('editUnitForm').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
});

// Handle Edit Unit Form Display
const editUnitButtons = document.querySelectorAll('.edit-button');
editUnitButtons.forEach(button => {
    button.addEventListener('click', function () {
        // Populate the form fields with data attributes
        document.getElementById('edit-unitCode').value = this.getAttribute('data-unit-code');
        document.getElementById('edit-unitName').value = this.getAttribute('data-unit-name');
        document.getElementById('edit-courseName').value = this.getAttribute('data-course-name');
        document.getElementById('edit-totalStudents').value = this.getAttribute('data-total-students');

        // Display the modal and overlay
        document.getElementById('editUnitForm').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
    });
});

// Close the Edit Unit Form
document.querySelector('.edit-close').addEventListener('click', function () {
    document.getElementById('editUnitForm').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
});

// Close modals when clicking outside of the form
window.addEventListener('click', function (event) {
    const editForm = document.getElementById('editUnitForm');
    const overlay = document.getElementById('overlay');
    if (event.target === overlay) {
        editForm.style.display = 'none';
        overlay.style.display = 'none';
    }
});

// Close Edit Department Form
document.querySelector('.edit-close').addEventListener('click', function() {
    document.getElementById('editDepartmentForm').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
});

// Handle Edit Department Form Display
const editDepartmentButtons = document.querySelectorAll('.edit-button.department');
editDepartmentButtons.forEach(button => {
    button.addEventListener('click', function () {
        // Populate the form fields with data attributes
        document.getElementById('edit-facultyName').value = this.getAttribute('data-faculty-name');
        document.getElementById('edit-facultyCode').value = this.getAttribute('data-faculty-code');

        // Display the modal and overlay
        document.getElementById('editDepartmentForm').style.display = 'block';
        document.getElementById('overlay').style.display = 'block';
    });
});

// Close modals when clicking outside of the form
window.addEventListener('click', function (event) {
    const editForm = document.getElementById('editDepartmentForm');
    const overlay = document.getElementById('overlay');
    if (event.target === overlay) {
        editForm.style.display = 'none';
        overlay.style.display = 'none';
    }
});


</script>
<?php if(isset($message)){
    echo "<script>showMessage('" . $message . "');</script>";
} 
?>
  <script src="javascript/main.js"></script>
  <script>
    // JavaScript to handle tab switching
    const tabs = document.querySelectorAll('.tab');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        // Remove active class from all tabs and contents
        tabs.forEach(t => t.classList.remove('active'));
        tabContents.forEach(content => content.classList.remove('active'));

        // Add active class to the clicked tab and corresponding content
        tab.classList.add('active');
        const targetContent = document.getElementById(tab.id.replace('Tab', 'Content'));
        targetContent.classList.add('active');
      });
    });
  </script>

</body>

</html>
