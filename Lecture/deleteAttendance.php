<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registrationNumber = $conn->real_escape_string($_POST['registrationNumber']);
    $course = $conn->real_escape_string($_POST['course']);
    $unit = $conn->real_escape_string($_POST['unit']);
    $date = date('Y-m-d');

    $query = "DELETE FROM tblattendance 
              WHERE studentRegistrationNumber = ? 
              AND course = ? 
              AND unit = ? 
              AND DATE(dateMarked) = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssss", $registrationNumber, $course, $unit, $date);
    
    if ($stmt->execute()) {
        http_response_code(200);
        echo "Attendance record deleted successfully";
    } else {
        http_response_code(500);
        echo "Error deleting attendance record: " . $conn->error;
    }
    
    $stmt->close();
} else {
    http_response_code(405);
    echo "Method not allowed";
}
?> 