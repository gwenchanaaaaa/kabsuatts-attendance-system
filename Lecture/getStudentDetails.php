<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['studentId'])) {
    $studentId = $conn->real_escape_string($_POST['studentId']);
    
    $query = "SELECT firstName, lastName FROM tblstudents WHERE registrationNumber = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        header('Content-Type: application/json');
        echo json_encode($row);
    } else {
        http_response_code(404);
        echo "Student not found";
    }
    
    $stmt->close();
} else {
    http_response_code(400);
    echo "Invalid request";
}
?> 