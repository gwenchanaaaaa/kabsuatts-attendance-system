function editAttendance(element) {
    const row = element.closest('tr');
    const modal = document.getElementById('editModal');
    const studentId = row.cells[0].textContent.trim();
    
    // Fetch student details
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'getStudentDetails.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const student = JSON.parse(xhr.responseText);
            
            // Populate the form
            document.getElementById('editStudentId').value = studentId;
            document.getElementById('editFirstName').value = student.firstName;
            document.getElementById('editLastName').value = student.lastName;
            document.getElementById('editAttendance').value = row.cells[5].textContent.trim();
            
            // Show the modal
            modal.style.display = 'flex';
        }
    };
    
    xhr.send('studentId=' + encodeURIComponent(studentId));
}

function closeModal() {
    const modal = document.getElementById('editModal');
    modal.style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target === modal) {
        closeModal();
    }
}

// Handle form submission
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const studentId = document.getElementById('editStudentId').value;
    const firstName = document.getElementById('editFirstName').value;
    const lastName = document.getElementById('editLastName').value;
    const attendance = document.getElementById('editAttendance').value;
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'updateStudent.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                // Update the table row
                const rows = document.querySelectorAll('#studentTableContainer tr');
                for (let row of rows) {
                    if (row.cells[0].textContent.trim() === studentId) {
                        row.cells[1].textContent = firstName + ' ' + lastName;
                        row.cells[5].textContent = attendance;
                        break;
                    }
                }
                
                showMessage('Student details updated successfully');
                closeModal();
            } else {
                showMessage('Error updating student details');
            }
        }
    };
    
    xhr.send(
        'studentId=' + encodeURIComponent(studentId) +
        '&firstName=' + encodeURIComponent(firstName) +
        '&lastName=' + encodeURIComponent(lastName) +
        '&attendance=' + encodeURIComponent(attendance)
    );
});

function deleteAttendance(element) {
    const row = element.closest('tr');
    const registrationNumber = row.cells[0].textContent.trim();
    const course = row.cells[2].textContent.trim();
    const unit = row.cells[3].textContent.trim();
    
    if (confirm('Are you sure you want to delete this attendance record?')) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'deleteAttendance.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    row.remove();
                    showMessage('Attendance record deleted successfully');
                } else {
                    showMessage('Error deleting attendance record');
                }
            }
        };
        
        xhr.send('registrationNumber=' + encodeURIComponent(registrationNumber) + 
                '&course=' + encodeURIComponent(course) + 
                '&unit=' + encodeURIComponent(unit));
    }
}

function showMessage(message) {
    const messageDiv = document.getElementById('messageDiv');
    if (messageDiv) {
        messageDiv.style.display = 'block';
        messageDiv.textContent = message;
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 3000);
    }
} 