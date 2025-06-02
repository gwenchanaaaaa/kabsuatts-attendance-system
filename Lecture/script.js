var labels = [];
let detectedFaces = [];
let sendingData = false; 
let videoStream = null;

function updateTable() {
    var selectedCourseID = document.getElementById('courseSelect').value;
    var selectedUnitCode = document.getElementById('unitSelect').value;
    var selectedVenue = document.getElementById("venueSelect").value;

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'manageFolder.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.status === 'success') {
                labels = response.data;
                if (selectedCourseID && selectedUnitCode && selectedVenue) {
                    updateOtherElements();
                }             
                document.getElementById('studentTableContainer').innerHTML = response.html;
            } else {
                console.error('Error:', response.message);
            }
        }
    };
    xhr.send('courseID=' + encodeURIComponent(selectedCourseID) +
             '&unitID=' + encodeURIComponent(selectedUnitCode) +
             '&venueID=' + encodeURIComponent(selectedVenue));
}

function markAttendance(detectedFaces) {
    document.querySelectorAll('#studentTableContainer tr').forEach(row => {
        const registrationNumber = row.cells[0].innerText.trim();
        if (detectedFaces.includes(registrationNumber)) {
            row.cells[5].innerText = 'Present';
        }
    });
}

function updateOtherElements() {
    const video = document.getElementById("video");
    const videoContainer = document.querySelector(".video-container");
    const startButton = document.getElementById("startButton");
    let webcamStarted = false;
    let modelsLoaded = false;

    // Set video dimensions
    video.width = 640;
    video.height = 480;

    // Load face-api.js models
    Promise.all([
        faceapi.nets.ssdMobilenetv1.loadFromUri("./models"),
        faceapi.nets.faceRecognitionNet.loadFromUri("./models"),
        faceapi.nets.faceLandmark68Net.loadFromUri("./models"),
    ]).then(() => {
        modelsLoaded = true;
        console.log("Models loaded successfully");
    }).catch(error => {
        console.error("Error loading models:", error);
    });

    startButton.addEventListener("click", async () => {
        videoContainer.style.display = "flex";
        if (!webcamStarted && modelsLoaded) {
            startWebcam();
            webcamStarted = true;
        }
    });

    function startWebcam() {
        navigator.mediaDevices
            .getUserMedia({
                video: {
                    width: 640,
                    height: 480,
                    facingMode: "user"
                },
                audio: false,
            })
            .then((stream) => {
                video.srcObject = stream;
                videoStream = stream;
            })
            .catch((error) => {
                console.error("Error accessing webcam:", error);
                showMessage("Error accessing webcam. Please check your camera permissions.");
            });
    }

    async function getLabeledFaceDescriptions() {
        const labeledDescriptors = [];
        console.log("Processing labels:", labels);

        for (const label of labels) {
            const descriptions = [];
            console.log("Processing label:", label);

            for (let i = 1; i <= 2; i++) {
                try {
                    const img = await faceapi.fetchImage(`./labels/${label}/${i}.png`);
                    console.log(`Processing image ${i} for label ${label}`);
                    
                    const detection = await faceapi
                        .detectSingleFace(img)
                        .withFaceLandmarks()
                        .withFaceDescriptor();
                    
                    if (detection) {
                        descriptions.push(detection.descriptor);
                        console.log(`Face detected in ${label}/${i}.png`);
                    } else {
                        console.log(`No face detected in ${label}/${i}.png`);
                    }
                } catch (error) {
                    console.error(`Error processing ${label}/${i}.png:`, error);
                }
            }

            if (descriptions.length > 0) {
                labeledDescriptors.push(new faceapi.LabeledFaceDescriptors(label, descriptions));
                console.log(`Added descriptor for ${label}`);
            }
        }

        return labeledDescriptors;
    }

    video.addEventListener("play", async () => {
        console.log("Video started playing");
        const labeledFaceDescriptors = await getLabeledFaceDescriptions();
        console.log("Labeled descriptors:", labeledFaceDescriptors);
        
        const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.6);
        const canvas = faceapi.createCanvasFromMedia(video);
        videoContainer.appendChild(canvas);

        const displaySize = { width: video.width, height: video.height };
        faceapi.matchDimensions(canvas, displaySize);

        setInterval(async () => {
            const detections = await faceapi
                .detectAllFaces(video)
                .withFaceLandmarks()
                .withFaceDescriptors();

            const resizedDetections = faceapi.resizeResults(detections, displaySize);
            canvas.getContext("2d").clearRect(0, 0, canvas.width, canvas.height);

            const results = resizedDetections.map((d) => {
                return faceMatcher.findBestMatch(d.descriptor);
            });

            results.forEach((result, i) => {
                const box = resizedDetections[i].detection.box;
                const drawBox = new faceapi.draw.DrawBox(box, {
                    label: result.toString(),
                    boxColor: "green",
                    drawLabelOptions: {
                        fontSize: 20,
                        fontStyle: "bold"
                    }
                });
                drawBox.draw(canvas);
            });

            const newDetectedFaces = results.map(result => result.label);
            if (JSON.stringify(newDetectedFaces) !== JSON.stringify(detectedFaces)) {
                detectedFaces = newDetectedFaces;
                markAttendance(detectedFaces);
            }
        }, 100);
    });
}

function sendAttendanceDataToServer() {
    const attendanceData = [];

    document.querySelectorAll('#studentTableContainer tr').forEach((row, index) => {
        if (index === 0) return; 
        const studentID = row.cells[0].innerText.trim(); 
        const course = row.cells[2].innerText.trim();
        const unit = row.cells[3].innerText.trim();
        const attendanceStatus = row.cells[5].innerText.trim(); 

        attendanceData.push({ studentID, course, unit, attendanceStatus });
    });

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'takeAttendance.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                showMessage('Attendance recorded successfully.');
            } else {
                showMessage('Error: Unable to record attendance.');
            }
        }
    };

    xhr.send(JSON.stringify(attendanceData));
}

function showMessage(message) {
    var messageDiv = document.getElementById('messageDiv');
    messageDiv.style.display = "block";
    messageDiv.innerHTML = message;
    console.log(message);
    messageDiv.style.opacity = 1;
    setTimeout(function() {
        messageDiv.style.opacity = 0;
    }, 5000);
}

function stopWebcam() {
    if (videoStream) {
        const tracks = videoStream.getTracks();
        tracks.forEach((track) => {
            track.stop();
        });
        video.srcObject = null;
        videoStream = null;
    }
}

document.getElementById("endAttendance").addEventListener("click", function() {
    sendAttendanceDataToServer();
    const videoContainer = document.querySelector(".video-container");
    videoContainer.style.display = "none";
    stopWebcam();
});
