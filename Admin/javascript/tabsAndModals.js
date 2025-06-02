// Tab functionality
function openTab(evt, tabName) {
    var i, tabContent, tabLinks;
    
    // Hide all tab content
    tabContent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabContent.length; i++) {
        tabContent[i].style.display = "none";
    }
    
    // Remove "active" class from all tabs
    tabLinks = document.getElementsByClassName("tab");
    for (i = 0; i < tabLinks.length; i++) {
        tabLinks[i].className = tabLinks[i].className.replace(" active", "");
    }
    
    // Show the selected tab content and add "active" class to the button
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

// Modal functionality for Course editing
function openEditModal(courseId, courseName, courseCode, facultyId) {
    document.getElementById('editCourseModal').style.display = 'block';
    document.getElementById('edit_course_id').value = courseId;
    document.getElementById('edit_course_name').value = courseName;
    document.getElementById('edit_course_code').value = courseCode;
    document.getElementById('edit_faculty').value = facultyId;
    document.getElementById('overlay').style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editCourseModal').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
}

// Modal functionality for Unit editing
function openEditUnitModal(unitCode, unitName, courseName, totalStudents) {
    document.getElementById('editUnitForm').style.display = 'block';
    document.getElementById('edit-unitCode').value = unitCode;
    document.getElementById('edit-unitName').value = unitName;
    document.getElementById('edit-courseName').value = courseName;
    document.getElementById('edit-totalStudents').value = totalStudents;
    document.getElementById('overlay').style.display = 'block';
}

// Modal functionality for Department editing
function openEditDepartmentModal(facultyName, facultyCode) {
    document.getElementById('editDepartmentForm').style.display = 'block';
    document.getElementById('edit-facultyName').value = facultyName;
    document.getElementById('edit-facultyCode').value = facultyCode;
    document.getElementById('overlay').style.display = 'block';
}

// Close any modal when clicking the close button
document.addEventListener('DOMContentLoaded', function() {
    // Set initial active tab
    document.getElementById('coursesContent').style.display = 'block';
    
    // Add click handlers for all close buttons
    var closeButtons = document.querySelectorAll('.close-btn, .edit-close');
    closeButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var modals = document.querySelectorAll('.modal, .form-popup');
            modals.forEach(function(modal) {
                modal.style.display = 'none';
            });
            document.getElementById('overlay').style.display = 'none';
        });
    });
    
    // Add click handlers for edit buttons
    var editButtons = document.querySelectorAll('.edit-button');
    editButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            if (button.classList.contains('department')) {
                openEditDepartmentModal(
                    button.getAttribute('data-faculty-name'),
                    button.getAttribute('data-faculty-code')
                );
            } else if (button.hasAttribute('data-unit-code')) {
                openEditUnitModal(
                    button.getAttribute('data-unit-code'),
                    button.getAttribute('data-unit-name'),
                    button.getAttribute('data-course-name'),
                    button.getAttribute('data-total-students')
                );
            }
        });
    });
}); 