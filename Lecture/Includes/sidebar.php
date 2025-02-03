<style>
/* Sidebar Styling for Sticky Behavior */
.sidebar {
    position: fixed; /* Makes sidebar sticky */
    top: 60px;
    left: 0;
    width: 300px;
    height: 95vh; /* Full height of the viewport */
    background-color: #fff;
    padding: 30px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    border-right: 2px solid #f1f1f1;
    transition: width 0.3s;
    overflow: hidden;
}

.sidebar.active {
    width: 103px;
}

.sidebar.active .sidebar--item {
    display: none;
}

li {
    list-style: none;
}

a {
    text-decoration: none;
}

.sidebar--items a,
.sidebar--bottom-items a {
    display: flex;
    align-items: center;
    padding: 10px;
    border-radius: 10px;
}

.sidebar--bottom-items li:last-child a {
    margin-bottom: 0;
}

.icon {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    font-size: 1.3rem;
}

/* Optional: Adjust main content to avoid overlapping with sidebar */
.main-content {
    margin-left: 300px;
    padding: 20px;
    height: 100vh;
    overflow-y: auto;
}
    .sidebar--items a:hover,
.sidebar--bottom-items a:hover {
    background-color:#D8FAD1;
    color: black;
}

#active--link{
    background-color:#1D8907;
    color: #fff;
}
#active--link i{
    color: white;
}
.icon-1{
    color: black;
}
</style>
<div class="sidebar">
            <ul class="sidebar--items">
              
                <li>
                    <a href="takeAttendance.php">
                        <span class="icon icon-1"><i class="ri-file-text-line"></i></span>
                        <span class="sidebar--item">Take Attendance</span>
                    </a>
                </li>
                <li>
                    <a href="viewAttendance.php">
                        <span class="icon icon-1"><i class="ri-map-pin-line"></i></span>
                        <span class="sidebar--item" style="white-space: nowrap;">View Attendance</span>
                    </a>
                </li>
                <li>
                    <a href="viewStudents.php">
                        <span class="icon icon-1"><i class="ri-user-line"></i></span>
                        <span class="sidebar--item">Students</span>
                    </a>
                </li>
                
            </ul>
            <ul class="sidebar--bottom-items">
                <li>
                    <a href="../logout.php">
                        <span class="icon icon-2"><i class="ri-logout-box-r-line"></i></span>
                        <span class="sidebar--item">Logout</span>
                    </a>
                </li>
            </ul>
        </div>
        

        <script>
        document.addEventListener("DOMContentLoaded", function() {
        var currentUrl = window.location.href;
        var links = document.querySelectorAll('.sidebar a');
        links.forEach(function(link) {
            if (link.href === currentUrl) {
                link.id = 'active--link';
            }
        });
    });
</script>