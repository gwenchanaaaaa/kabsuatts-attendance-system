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
            color: inherit; /* Ensure links inherit text color */
        }

        .sidebar--items a,
        .sidebar--bottom-items a,
        .sidebar--bottom-items button {
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 10px;
            color: inherit; /* Inherit text color */
            background: none; /* Remove default button background */
            border: none; /* Remove default button border */
            cursor: pointer; /* Pointer cursor on hover */
            width: 100%; /* Full width */
            text-align: left; /* Left-align text */
        }

        .sidebar--bottom-items li:last-child a,
        .sidebar--bottom-items li:last-child button {
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
        .sidebar--bottom-items a:hover,
        .sidebar--bottom-items button:hover {
            background-color: #D8FAD1;
            color: black;
        }

        #active--link {
            background-color: #1D8907;
            color: #fff;
        }

        #active--link i {
            color: white;
        }

        .icon-1 {
            color: black;
        }

        /* Confirmation Modal Styles */

        /* Modal Overlay */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
            display: none; /* Hidden by default */
            justify-content: center; /* Center horizontally */
            align-items: center; /* Center vertically */
            z-index: 1000; /* Ensure it's on top of other elements */
        }

        /* Modal Container */
        .modal {
            background-color: #ffffff; /* White background */
            padding: 20px 30px; /* Inner spacing */
            border-radius: 8px; /* Rounded corners */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3); /* Subtle shadow */
            max-width: 400px; /* Maximum width */
            width: 90%; /* Responsive width */
            text-align: center; /* Centered text */
        }

        /* Modal Header */
        .modal h2 {
            margin-top: 0;
            font-size: 24px;
            color: #333333;
        }

        /* Modal Message */
        .modal p {
            font-size: 16px;
            color: #555555;
            margin: 20px 0;
        }

        /* Modal Buttons Container */
        .modal-buttons {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        /* Modal Buttons */
        .modal-buttons button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            min-width: 100px;
            transition: background-color 0.3s ease;
            color: inherit; /* Inherit text color */
        }

        /* Confirm Logout Button */
        .btn-confirm {
            background-color: #e74c3c; /* Red background */
            color: #ffffff; /* White text */
        }

        .btn-confirm:hover {
            background-color: #c0392b; /* Darker red on hover */
        }

        /* Cancel Button */
        .btn-cancel {
            background-color: #bdc3c7; /* Grey background */
            color: #2c3e50; /* Dark text */
        }

        .btn-cancel:hover {
            background-color: #95a5a6; /* Darker grey on hover */
        }

        /* Responsive Adjustments */
        @media (max-width: 500px) {
            .modal {
                padding: 15px 20px;
            }

            .modal h2 {
                font-size: 20px;
            }

            .modal p {
                font-size: 14px;
            }

            .modal-buttons button {
                padding: 8px 16px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="sidebar--items">
            <li>
                <a href="index.php">
                    <span class="icon icon-1"><i class="ri-layout-grid-line"></i></span>
                    <span class="sidebar--item">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="createCourse.php">
                    <span class="icon icon-1"><i class="ri-file-text-line"></i></span>
                    <span class="sidebar--item">Manage Courses</span>
                </a>
            </li>
            <li>
                <a href="createVenue.php">
                    <span class="icon icon-1"><i class="ri-map-pin-line"></i></span>
                    <span class="sidebar--item" style="white-space: nowrap;">Create Venue</span>
                </a>
            </li>
            <li>
                <a href="createLecture.php">
                    <span class="icon icon-1"><i class="ri-user-line"></i></span>
                    <span class="sidebar--item">Manage Instructors</span>
                </a>
            </li>
            <li>
                <a href="createStudent.php">
                    <span class="icon icon-1"><i class="ri-user-line"></i></span>
                    <span class="sidebar--item">Manage Students</span>
                </a>
            </li>
        </ul>
        <ul class="sidebar--bottom-items">
            <li>
                <!-- Changed from <a> to <button> for better accessibility -->
                <button id="logout-button">
                    <span class="icon icon-2"><i class="ri-logout-box-r-line"></i></span>
                    <span class="sidebar--item" style="font-family: poppins,sans-serif; font-size: 17px; font-weight: bold;">Logout</span>
                </button>
            </li>
        </ul>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal-overlay" id="logout-modal" role="dialog" aria-modal="true" aria-labelledby="logout-modal-title">
        <div class="modal">
            <h2 id="logout-modal-title">Confirm Logout</h2>
            <p>Are you sure you want to logout?</p>
            <div class="modal-buttons">
                <button class="btn-confirm" id="confirm-logout">Logout</button>
                <button class="btn-cancel" id="cancel-logout">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Existing JavaScript for Active Link Highlighting -->
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

    <!-- JavaScript for Logout Confirmation Modal -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Get modal elements
            var logoutButton = document.getElementById('logout-button');
            var logoutModal = document.getElementById('logout-modal');
            var confirmLogout = document.getElementById('confirm-logout');
            var cancelLogout = document.getElementById('cancel-logout');

            // Show Modal on Logout Button Click
            logoutButton.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default button behavior
                logoutModal.style.display = 'flex';
                confirmLogout.focus(); // Set focus to the Logout button in modal
            });

            // Confirm Logout
            confirmLogout.addEventListener('click', function() {
                window.location.href = '../logout.php'; // Redirect to logout script
            });

            // Cancel Logout
            cancelLogout.addEventListener('click', function() {
                logoutModal.style.display = 'none'; // Hide the modal
                logoutButton.focus(); // Return focus to the Logout button
            });

            // Close Modal when clicking outside the modal content
            window.addEventListener('click', function(event) {
                if (event.target === logoutModal) {
                    logoutModal.style.display = 'none';
                    logoutButton.focus();
                }
            });

            // Optional: Close Modal on Esc Key Press
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && logoutModal.style.display === 'flex') {
                    logoutModal.style.display = 'none';
                    logoutButton.focus();
                }
            });
        });
    </script>