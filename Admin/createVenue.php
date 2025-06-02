<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';

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

// Add venue logic
if (isset($_POST["addVenue"])) {
    $className = $_POST["className"];
    $facultyCode = $_POST["faculty"];
    $currentStatus = $_POST["currentStatus"];
    $capacity = $_POST["capacity"];
    $classification = $_POST["classification"];
    $dateRegistered = date("Y-m-d");

    $query = mysqli_query($conn, "SELECT * FROM tblvenue WHERE className='$className'");
    $ret = mysqli_fetch_array($query);
    if ($ret > 0) { 
        $message = "Venue Already Exists";
    } else {
        $query = mysqli_query($conn, "INSERT INTO tblvenue (className, facultyCode, currentStatus, capacity, classification, dateCreated) 
        VALUES ('$className', '$facultyCode', '$currentStatus', '$capacity', '$classification', '$dateRegistered')");
        $message = "Venue Inserted Successfully";
    }
}

// Edit venue logic
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = mysqli_query($conn, "SELECT * FROM tblvenue WHERE ID = '$id'");
    $venue = mysqli_fetch_assoc($query);
    if ($venue) {
        $className = $venue['className'];
        $facultyCode = $venue['facultyCode'];
        $currentStatus = $venue['currentStatus'];
        $capacity = $venue['capacity'];
        $classification = $venue['classification'];
    }
}

// Update venue logic for editing via AJAX
if (isset($_POST['editVenue'])) {
    $className = $_POST["className"];
    $facultyCode = $_POST["faculty"];
    $currentStatus = $_POST["currentStatus"];
    $capacity = $_POST["capacity"];
    $classification = $_POST["classification"];
    $venueId = $_POST["venueId"];

    $query = mysqli_query($conn, "UPDATE tblvenue SET className='$className', facultyCode='$facultyCode', currentStatus='$currentStatus', capacity='$capacity', classification='$classification' WHERE ID='$venueId'");

    if ($query) {
        $message = "Venue Updated Successfully";
    } else {
        $message = "Error Updating Venue";
    }
}

// Delete venue logic
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $query = mysqli_query($conn, "DELETE FROM tblvenue WHERE ID = '$id'");
    if ($query) {
        $message = "Venue Deleted Successfully";
    } else {
        $message = "Error Deleting Venue";
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

        .main--content {
            padding-top: 50px;
        }

        /* Modal Styles */
        #addClassForm {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-70%, -50%);
            background: white;
            padding: 20px;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            width: 700px;
            max-height: 80%;
            overflow-y: auto;
        }

        /* Close Button */
        .close {
            font-size: 24px;
            cursor: pointer;
            background: none;
            border: none;
        }

        /* Overlay */
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

        .edit-button {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            display: inline-flex;
            align-items: center;
        }

        .add {
            margin-left: auto;
            background-color: #1D8907;
            margin-right: 15px;
            color: #fff;
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
            <div class="title" id="addClass2">
                <h2 class="section--title">Lecture Rooms</h2>
                <button class="add" id="addClassBtn"><i class="ri-add-line"></i>Add Class</button>
            </div>

            <div class="table">
                <table>
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Class Name</th>
                        <th>Faculty</th>
                        <th>Current Status</th>
                        <th>Capacity</th>
                        <th>Classification</th>
                        <th>Settings</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    // Fetch the venues from the database
                    $sql = "SELECT * FROM tblvenue";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . (isset($row["ID"]) ? $row["ID"] : "N/A") . "</td>";
                            echo "<td>" . $row["className"] . "</td>";
                            echo "<td>" . $row["facultyCode"] . "</td>";
                            echo "<td>" . $row["currentStatus"] . "</td>";
                            echo "<td>" . $row["capacity"] . "</td>";
                            echo "<td>" . $row["classification"] . "</td>";
                            echo "<td>
                                    <span><i class='ri-edit-line edit' onclick='editVenue(" . $row["ID"] . ", \"" . $row["className"] . "\", \"" . $row["facultyCode"] . "\", \"" . $row["currentStatus"] . "\", \"" . $row["capacity"] . "\", \"" . $row["classification"] . "\")'></i>
                                    <i class='ri-delete-bin-line delete' onclick='deleteVenue(" . $row["ID"] . ")'></i></span>
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

        <!-- Add/Edit Venue Form Modal -->
        <div class="formDiv-venue" id="addClassForm">
            <form method="POST" action="" name="addVenue" id="addVenueForm" enctype="multipart/form-data">
                <div style="display:flex; justify-content:space-around;">
                    <div class="form-title">
                        <p id="modalTitle">Add Class</p>
                    </div>
                    <div>
                        <span class="close">&times;</span>
                    </div>
                </div>
                <input type="text" name="className" id="className" placeholder="Class Name" required>
                <select name="currentStatus" id="currentStatus">
                    <option value="">--Current Status--</option>
                    <option value="availlable">Available</option>
                    <option value="scheduled">Scheduled</option>
                </select>
                <input type="text" name="capacity" id="capacity" placeholder="Capacity" required>
                <select required name="classification" id="classification">
                    <option value="" selected> --Select Class Type--</option>
                    <option value="laboratory">Laboratory</option>
                    <option value="computerLab">Computer Lab</option>
                    <option value="lectureHall">Lecture Hall</option>
                    <option value="class">Class</option>
                    <option value="office">Office</option>
                </select>
                <select required name="faculty" id="faculty">
                    <option value="" selected>Select Faculty</option>
                    <?php
                    $facultyNames = getFacultyNames($conn);
                    foreach ($facultyNames as $faculty) {
                        echo '<option value="' . $faculty["facultyCode"] . '">' . $faculty["facultyName"] . '</option>';
                    }
                    ?>
                </select>
                <input type="hidden" name="venueId" id="venueId"> <!-- Hidden field for editing -->
                <input type="submit" class="submit" value="Add Class" name="addVenue" id="saveVenueBtn">
            </form>
        </div>
    </div>
</section>

<script>
    const addClassBtn = document.getElementById('addClassBtn');
    const addClassForm = document.getElementById('addClassForm');
    const overlay = document.getElementById('overlay');
    const saveVenueBtn = document.getElementById('saveVenueBtn');

    // Open the Add Class modal
    addClassBtn.addEventListener('click', function () {
        document.getElementById('modalTitle').innerText = 'Add Class';
        document.getElementById('className').value = '';
        document.getElementById('faculty').value = '';
        document.getElementById('currentStatus').value = '';
        document.getElementById('capacity').value = '';
        document.getElementById('classification').value = '';
        document.getElementById('venueId').value = ''; // Clear venue ID
        saveVenueBtn.value = "Add Class"; // Set the button text to "Add Class"
        addClassForm.style.display = 'block';
        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
    });

    // Close the modal for Add Class
    var closeButtons = document.querySelectorAll('#addClassForm .close');
    closeButtons.forEach(function (closeButton) {
        closeButton.addEventListener('click', function () {
            addClassForm.style.display = 'none';
            overlay.style.display = 'none';
            document.body.style.overflow = 'auto';
        });
    });

    // Open the Edit Class modal
    function editVenue(id, className, facultyCode, currentStatus, capacity, classification) {
        document.getElementById('modalTitle').innerText = 'Edit Class';
        document.getElementById('className').value = className;
        document.getElementById('faculty').value = facultyCode;
        document.getElementById('currentStatus').value = currentStatus;
        document.getElementById('capacity').value = capacity;
        document.getElementById('classification').value = classification;
        document.getElementById('venueId').value = id; // Set venue ID for editing
        saveVenueBtn.value = "Update Class"; // Change button text to "Update Class"
        addClassForm.style.display = 'block';
        overlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    // Delete venue function
    function deleteVenue(id) {
        if (confirm("Are you sure you want to delete this class?")) {
            window.location.href = "createVenue.php?delete_id=" + id;
        }
    }
</script>

</body>
</html>
