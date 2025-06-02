<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $conn->real_escape_string($_POST['studentId']);
    $firstName = $conn->real_escape_string($_POST['firstName']);
    $lastName = $conn->real_escape_string($_POST['lastName']);
    $attendance = $conn->real_escape_string($_POST['attendance']);
    $date = date('Y-m-d');

    // Start transaction
    $conn->begin_transaction();

    try {
        // Update student details
        $updateStudent = "UPDATE tblstudents 
                         SET firstName = ?, lastName = ? 
                         WHERE registrationNumber = ?";
        $stmt1 = $conn->prepare($updateStudent);
        $stmt1->bind_param("sss", $firstName, $lastName, $studentId);
        $stmt1->execute();

        // Update attendance status
        $updateAttendance = "UPDATE tblattendance 
                            SET attendanceStatus = ? 
                            WHERE studentRegistrationNumber = ? 
                            AND DATE(dateMarked) = ?";
        $stmt2 = $conn->prepare($updateAttendance);
        $stmt2->bind_param("sss", $attendance, $studentId, $date);
        $stmt2->execute();

        // Commit transaction
        $conn->commit();
        
        http_response_code(200);
        echo "Update successful";
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        http_response_code(500);
        echo "Error updating record: " . $e->getMessage();
    }

    $stmt1->close();
    $stmt2->close();
} else {
    http_response_code(405);
    echo "Method not allowed";
}
?> 