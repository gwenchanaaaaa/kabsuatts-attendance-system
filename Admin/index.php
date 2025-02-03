<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';


 

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="Includes/pic/cvsu.png" rel="icon">
  <title>Admin | Dashboard</title>
  <link rel="stylesheet" href="css/styles.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.2.0/remixicon.css" rel="stylesheet">
<style>
    *{
        font-family:"poppins",sans-serif;
    }    
    .card{
        background-color: #D8FAD1;
    }
    .card--content h1{
        font-size: 30px;
        margin: 0;
        padding: 0;
    }
    .card--title{
        font-weight: 600;
    }
    .title button{
        background-color: #1D8907;
    }
    th,td{
        padding-left: 40px;
    }
    .table{
        border-radius: 10px;
    }
    .table thead{
        background-color: #EBEBEB;
    }
    .table tbody{
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
.main--content{
    z-index: -9999;
    padding-top: 50px;
}
</style>
</head>
<body>
<?php include 'includes/topbar.php';?>
    <section class="main">
        <?php include 'includes/sidebar.php';?>
    <div class="main--content">
        <div class="overview">
                <div class="title">
                    <h2 class="section--title">Overview</h2>
                </div>
                <div class="cards">
                <div class="card card-1">
                        <?php 
                        $query1=mysqli_query($conn,"SELECT * from tblstudents");                       
                        $students = mysqli_num_rows($query1);
                        ?>
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Students</h5>
                                <h1><?php echo $students;?></h1>
                            </div>
                            <i class="ri-user-2-line card--icon--lg" style=" color: #66A659; margin-top:10px"></i>
                        </div>
                       
                    </div>
                    <div class="card card-1">
                        <?php 
                        $query1=mysqli_query($conn,"SELECT * from tbllecture");                       
                        $totalLecture = mysqli_num_rows($query1);
                        ?>
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Instructors</h5>
                                <h1><?php echo $totalLecture;?></h1>
                            </div>
                            <i class="ri-user-line card--icon--lg" style=" color:  #66A659; margin-top:10px"></i>
                        </div>
                       
                    </div>
                    <div class="card card-1">
                        <?php 
                        $query1=mysqli_query($conn,"SELECT * from tblcourse");                       
                        $students = mysqli_num_rows($query1);
                        ?>
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Courses</h5>
                                <h1><?php echo $students;?></h1>
                            </div>
                            <i class="ri-user-2-line card--icon--lg" style=" color: #66A659; margin-top:10px"></i>
                        </div>
                       
                    </div>
                    
                    <div class="card card-1">
                        <?php 
                        $query1=mysqli_query($conn,"SELECT * from tblunit");                       
                        $unit = mysqli_num_rows($query1);
                        ?>
                        <div class="card--data">
                            <div class="card--content">
                                <h5 class="card--title">Units</h5>
                                <h1><?php echo $unit;?></h1>
                            </div>
                            <i class="ri-file-text-line card--icon--lg" style=" color:  #66A659; margin-top:10px"></i>
                        </div>
                        
                    </div>
                   
                    
                </div>
            </div>
           
            <div class="table-container">
            <a href="createLecture.php" style="text-decoration:none; color: black;"> <div class="title">
                    <h2 class="section--title">Instructors</h2>
                </div>
            </a>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email Address</th>
                                <th>Phone No</th>
                                <th>Faculty</th>
                                <th>Date Registered</th>                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                        <?php
                         $sql = "SELECT l.*, f.facultyName
                         FROM tbllecture l
                         LEFT JOIN tblfaculty f ON l.facultyCode = f.facultyCode";
                          $result = $conn->query($sql);
                          if ($result->num_rows > 0) {
                         while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["firstName"] . "</td>";
                            echo "<td>" . $row["emailAddress"] . "</td>";
                            echo "<td>" . $row["phoneNo"] . "</td>";
                            echo "<td>" . $row["facultyName"] . "</td>";
                            echo "<td>" . $row["dateCreated"] . "</td>";
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
            <div class="table-container">
            <a href="createStudent.php" style="text-decoration:none;color: black;"> <div class="title">
                    <h2 class="section--title">Students</h2>
                </div>
            </a>
                <div class="table">
                    <table>
                        <thead >
                            <tr>
                                <th>Registration No</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Faculty</th>
                                <th>Course</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sql = "SELECT * FROM tblstudents";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["registrationNumber"] . "</td>";
                            echo "<td>" . $row["firstName"] . "</td>";
                            echo "<td>" . $row["lastName"] . "</td>";
                            echo "<td>" . $row["faculty"] . "</td>";
                            echo "<td>" . $row["courseCode"] . "</td>";
                            echo "<td>" . $row["email"] . "</td>";
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
        <div class="table-container">
            <a href="createCourse.php" style="text-decoration:none;color: black;"><div class="title">
                    <h2 class="section--title">Courses</h2>
                </div>
            </a>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Faculty</th>
                                <th>Total Units</th>
                                <th>Total Students</th>
                                <th>Date Created</th>
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
                            echo "<td>" . $row["course_name"] . "</td>";
                            echo "<td>" . $row["faculty_name"] . "</td>";
                            echo "<td>" . $row["total_units"] . "</td>";
                            echo "<td>" . $row["total_students"] . "</td>";
                            echo "<td>" . $row["date_created"] . "</td>";
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
        </div>
    </section>
    <script src="javascript/main.js"></script>
      <?php include 'includes/footer.php';?>
     
 

</body>

</html>