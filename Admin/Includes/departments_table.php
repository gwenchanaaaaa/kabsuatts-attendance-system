<?php
$sql = "SELECT 
        f.facultyCode as faculty_code,
        f.facultyName as faculty_name,
        COUNT(DISTINCT c.ID) as total_courses,
        COUNT(DISTINCT s.Id) as total_students,
        COUNT(DISTINCT l.Id) as total_lectures,
        f.dateRegistered as date_created
        FROM tblfaculty f
        LEFT JOIN tblcourse c ON f.Id = c.facultyID
        LEFT JOIN tblstudents s ON f.facultyCode = s.faculty
        LEFT JOIN tbllecture l ON f.facultyCode = l.facultyCode
        GROUP BY f.Id, f.facultyCode, f.facultyName, f.dateRegistered";

$result = $conn->query($sql);

if ($result === false) {
    // Query failed
    echo "<tr><td colspan='7'>Error executing query: " . $conn->error . "</td></tr>";
} else if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row["faculty_code"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["faculty_name"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["total_courses"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["total_students"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["total_lectures"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["date_created"]) . "</td>";
        echo "<td>
            <button type='button' class='edit-button'
                data-type='Faculty'
                data-id='" . htmlspecialchars($row["faculty_code"]) . "'
                data-name='" . htmlspecialchars($row["faculty_name"]) . "'>
                <i class='ri-edit-line'></i>
            </button>
            <form method='POST' action='' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this department?\");'>
                <input type='hidden' name='faculty_code' value='" . htmlspecialchars($row["faculty_code"]) . "'>
                <button type='submit' name='deleteFaculty' class='delete'>
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