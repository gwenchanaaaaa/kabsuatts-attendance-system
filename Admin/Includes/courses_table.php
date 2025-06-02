<?php
$sql = "SELECT 
        c.ID as course_id,
        c.name AS course_name,
        c.courseCode AS course_code,
        c.facultyID AS faculty_id,
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
                <button type='button' class='edit-button' 
                    data-type='Course'
                    data-id='" . htmlspecialchars($row["course_id"]) . "'
                    data-name='" . htmlspecialchars($row["course_name"]) . "'
                    data-code='" . htmlspecialchars($row["course_code"]) . "'
                    data-faculty='" . htmlspecialchars($row["faculty_id"]) . "'>
                    <i class='ri-edit-line'></i>
                </button>
                <form method='POST' action='' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this course?\");'>
                    <input type='hidden' name='course_name' value='" . htmlspecialchars($row["course_name"]) . "'>
                    <button type='submit' name='deleteCourse' class='delete'>
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