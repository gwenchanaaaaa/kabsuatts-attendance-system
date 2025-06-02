let stream1 = null;
let stream2 = null;

function showForm() {
    document.getElementById('addStudentForm').style.display = 'block';
    document.getElementById('overlay').style.display = 'block';
    startCameras();
}

function closeForm() {
    document.getElementById('addStudentForm').style.display = 'none';
    document.getElementById('overlay').style.display = 'none';
    stopCameras();
}

function startCameras() {
    const video1 = document.getElementById('video1');
    const video2 = document.getElementById('video2');

    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video1.srcObject = stream;
            stream1 = stream;
            return navigator.mediaDevices.getUserMedia({ video: true });
        })
        .then(stream => {
            video2.srcObject = stream;
            stream2 = stream;
        })
        .catch(err => {
            console.error('Error accessing camera:', err);
            alert('Error accessing camera. Please make sure you have granted camera permissions.');
        });
}

function stopCameras() {
    if (stream1) {
        stream1.getTracks().forEach(track => track.stop());
    }
    if (stream2) {
        stream2.getTracks().forEach(track => track.stop());
    }
    
    const video1 = document.getElementById('video1');
    const video2 = document.getElementById('video2');
    if (video1) video1.srcObject = null;
    if (video2) video2.srcObject = null;
}

function captureImage(cameraNumber) {
    const video = document.getElementById('video' + cameraNumber);
    const canvas = document.getElementById('canvas' + cameraNumber);
    const capturedImageInput = document.getElementById('capturedImage' + cameraNumber);
    
    // Set canvas dimensions to match video
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    
    // Draw the video frame to the canvas
    const context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    // Convert the canvas to base64 image data
    const imageData = canvas.toDataURL('image/png');
    capturedImageInput.value = imageData;
    
    // Show the captured image
    video.style.display = 'none';
    canvas.style.display = 'block';
    
    // Add retake button
    const cameraBox = video.parentElement;
    const captureButton = cameraBox.querySelector('button');
    captureButton.textContent = 'Retake Picture';
    captureButton.onclick = () => retakeImage(cameraNumber);
}

function retakeImage(cameraNumber) {
    const video = document.getElementById('video' + cameraNumber);
    const canvas = document.getElementById('canvas' + cameraNumber);
    const capturedImageInput = document.getElementById('capturedImage' + cameraNumber);
    const cameraBox = video.parentElement;
    const captureButton = cameraBox.querySelector('button');
    
    // Clear the captured image
    capturedImageInput.value = '';
    canvas.style.display = 'none';
    video.style.display = 'block';
    
    // Reset the capture button
    captureButton.textContent = 'Capture Image ' + cameraNumber;
    captureButton.onclick = () => captureImage(cameraNumber);
}

// Form validation
function validateForm() {
    const form = document.getElementById('studentForm');
    const image1 = document.getElementById('capturedImage1').value;
    const image2 = document.getElementById('capturedImage2').value;
    
    if (!image1 || !image2) {
        alert('Please capture both student pictures before submitting.');
        return false;
    }
    
    return true;
}

// Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('studentForm');
    if (form) {
        form.onsubmit = validateForm;
    }
}); 