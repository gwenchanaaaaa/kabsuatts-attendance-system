<?php
include '../Includes/dbcon.php';

// Add subjectName column to tblvenue table
$sql = "ALTER TABLE tblvenue ADD COLUMN subjectName VARCHAR(255) AFTER className";
if ($conn->query($sql) === TRUE) {
    echo "Column subjectName added successfully";
} else {
    echo "Error adding column: " . $conn->error;
}

$conn->close();
?> 