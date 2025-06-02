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
                <button type='button' class='edit-button'
                    data-type='Unit'
                    data-id='" . htmlspecialchars($row["unit_code"]) . "'
                    data-name='" . htmlspecialchars($row["unit_name"]) . "'
                    data-course='" . htmlspecialchars($row["course_name"]) . "'>
                    <i class='ri-edit-line'></i>
                </button>
                <form method='POST' action='' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this unit?\");'>
                    <input type='hidden' name='unit_code' value='" . htmlspecialchars($row["unit_code"]) . "'>
                    <button type='submit' name='deleteUnit' class='delete'>
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