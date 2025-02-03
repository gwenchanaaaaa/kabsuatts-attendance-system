<?php
$fullName = ""; 
if (isset($_SESSION['userId'])) {
    $userId = $conn->real_escape_string($_SESSION['userId']);

    $query = "SELECT * FROM tbladmin WHERE Id = $userId";

    $rs = $conn->query($query);

    if ($rs) {
        $num = $rs->num_rows;

        if ($num > 0) {
            $row = $rs->fetch_assoc();

            $fullName = $row['firstName'] . " " . $row['lastName'];
            
                } else {
            echo "Admin not found";
        }
    } else {
        echo "Error: " . $conn->error;
    }
} else {
 header('location: ../index.php');
}


?>
<style>
    .header{
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 60px; /* Adjust this based on your topbar height */
    background-color: #fff;
    border-bottom: 2px solid #f1f1f1;
    z-index: 999; /* Ensures it's above other content */
    display: flex;
    align-items: center;
    }
    .search-icon:hover{
        background-color:rgb(221, 255, 223);
    }

</style>
<section class="header">
    <div class="logo">
        <i class="ri-menu-line icon icon-0 menu"></i>
        <a href="index.php"><img src="Includes/pic/logo2.png" style="height:35px; width: 120px; margin-left: 80px; margin-top: 10px"></a>
    </div>
    
    <div class="search--notification--profile">
        <div id="searchInput" class="search">
            <input type="text" id="searchText" placeholder="Search">
            <button class="search-icon" onclick="searchItems()"><i class="ri-search-2-line" style="color: green"></i></button>
        </div>
        <div class="notification--profile">
            <div class="picon lock" style="background-color: white; color:black;">
                <?php echo $fullName; ?>
            </div>
            <div class="picon profile">
                <img src="img/user.png" alt="">
            </div>
        </div>
    </div>
</section>



<script>
    function searchItems() {
        var input = document.getElementById('searchText').value.toLowerCase();
        var rows = document.querySelectorAll('table tr'); 

        rows.forEach(function(row) {
            var cells = row.querySelectorAll('td'); 
            var found = false;

            cells.forEach(function(cell) {
                if (cell.innerText.toLowerCase().includes(input)) { 
                    found = true;
                }
            });

            if (found) {
                row.style.display = ''; 
            } else {
                row.style.display = 'none'; 
            }
        });
    }
</script>