<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$hospitalData = [];
$doctorAvailableDays = [];
$doctorAvailableTimes = [];
$selected_doctor_id = [];
$selected_doctor_name = [];

if (isset($_POST['hospital_id']) && isset($_POST['email'])) {
    $hospital_id = intval($_POST['hospital_id']);
    $useremail = htmlspecialchars($_POST['email']);

    // Fetch doctor details
    $sql = "SELECT h.name, h.location, h.specialty, h.contact, h.rating
            FROM hospitals h 
            JOIN doctor_hospital dh ON h.hospital_id = dh.hospital_id 
            WHERE h.hospital_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $hospital_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $hospitalData = $result->fetch_assoc();
    }
    $stmt->close();

    // Fetch available days & times
    $sql = "SELECT available_day, available_time, doctor_id FROM doctor_hospital WHERE hospital_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $hospital_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $day = strtoupper($row['available_day']); // Convert day to uppercase (MON, TUE, etc.)
        $hospitalAvailableDays[$day][] = $row['available_time']; // Store time slots under respective day
        $selected_doctor_id[] = $row['doctor_id'];
    }
    $stmt->close();

    $sql = "SELECT name FROM doctors WHERE doctor_id IN (" . implode(',', $selected_doctor_id) . ") ORDER BY FIELD(doctor_id, " . implode(',', $selected_doctor_id) . ")";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $selected_doctor_name[] = $row['name'];
    }
    $stmt->close();
}

$conn->close();

// Get today's date & weekday
date_default_timezone_set('Asia/Yangon'); // Adjust timezone as needed
$current_date = date('Y-m-d');
$current_day = strtoupper(date('D')); // Example: "MON"

// Find the nearest available date
$selected_date = $current_date;
$selected_times = [];

if (!array_key_exists($current_day, $hospitalAvailableDays)) {
    // If today is not available, find the next available date
    for ($i = 1; $i <= 7; $i++) {
        $next_date = date('Y-m-d', strtotime("+$i days"));
        $next_day = strtoupper(date('D', strtotime("+$i days")));

        if (array_key_exists($next_day, $hospitalAvailableDays)) {
            $selected_date = $next_date;
            $selected_times = $hospitalAvailableDays[$next_day]; // Get time slots for that day
            break;
        }
    }
} else {
    $selected_times = $hospitalAvailableDays[$current_day]; // Use today's time slots if available
}

// Format selected date as "FEB 12 WED"
$display_date = strtoupper(date('M d D', strtotime($selected_date)));

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointment Booking</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notiflix@3.2.7/dist/notiflix-3.2.7.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f9fc;
            color: #333;
        }

        .navbar {
            background-color: #1a73e8;
        }

        .navbar a {
            color: #fff;
        }

        .container {
            max-width: 900px;
            margin: 50px auto;
        }

        .card {
            background-color: #fff;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 30px;
        }

        h2 {
            font-size: 28px;
            color: #1a73e8;
        }

        .doctor-info {
            text-align: center;
            margin-bottom: 30px;
        }

        .doctor-info img {
            border-radius: 50%;
            border: 4px solid #1a73e8;
        }

        .doctor-info h4 {
            font-size: 24px;
            color: #333;
            margin-top: 15px;
        }

        .doctor-info p {
            font-size: 16px;
            color: #555;
        }

        .info-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fafbfc;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
        }

        .info-box .label {
            font-size: 18px;
            font-weight: bold;
            color: #1a73e8;
        }

        .info-box .content {
            font-size: 16px;
            color: #333;
            max-width: 65%;
        }

        .date-box,
        .hospital-box,
        .time-box {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .option-box {
            width: 130px;
            height: 130px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #e7f3ff;
            border: 2px solid #1a73e8;
            border-radius: 12px;
            cursor: pointer;
            font-weight: bold;
            color: #1a73e8;
            transition: all 0.3s ease;
        }

        .option-box:hover {
            background-color: #1a73e8;
            color: #fff;
            transform: scale(1.05);
        }

        .option-box.selected {
            background-color: #1a73e8;
            color: #fff;
        }

        .option-box span {
            display: block;
            text-align: center;
        }

        .option-box .month,
        .option-box .day,
        .option-box .hospital-name,
        .option-box .time {
            font-size: 16px;
        }

        .form-section {
            margin-top: 30px;
        }

        .form-section .form-label {
            font-size: 16px;
            font-weight: bold;
            color: #1a73e8;
            margin-bottom: 10px;
        }

        .form-section .form-control {
            padding: 12px;
            border-radius: 8px;
            border: 2px solid #ccc;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .form-section .form-control:focus {
            border-color: #1a73e8;
            box-shadow: 0px 0px 8px rgba(26, 115, 232, 0.3);
        }

        .btn-primary {
            background-color: #1a73e8;
            border-color: #1a73e8;
            padding: 12px;
            font-size: 18px;
            border-radius: 8px;
            width: 100%;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #155ea7;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Doctor Appointment System</a>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <div class="doctor-info">
                <img id="doctor-img" src="https://via.placeholder.com/150" alt="Hospital Image" class="rounded-circle mb-3" width="150">
                <h4 id="doctor"><?php echo $hospitalData['name'] ?? 'Hospital Name'; ?></h4>
            </div>

            <div class="info-box">
                <div class="label">Hospital Speciality</div>
                <div class="content"><?php echo $hospitalData['specialty'] ?? 'Hospital Speciality'; ?></div>
            </div>

            <div class="info-box">
                <div class="label">Contact number</div>
                <div class="content"><?php echo $hospitalData['contact'] ?? 'Contact number'; ?></div>
            </div>

            <div class="info-box">
                <div class="label">Rating</div>
                <div class="content"><?php echo $hospitalData['rating'] ?? 'rating'; ?></div>
            </div>

            <div class="info-box">
                <div class="label">Location</div>
                <div class="content"><?php echo $hospitalData['location'] ?? 'location'; ?></div>
            </div>

            <div class="form-section">
                <label class="form-label">Available Doctors</label>
                <div class="hospital-box" id="hospital">
                    <?php foreach ($selected_doctor_name as $index => $doctor_name):
                    ?>
                        <div class="option-box" data-doctor-id="<?php echo $selected_doctor_id[$index]; ?>">
                            <span class="hospital-name">
                                <?php echo $doctor_name ?? 'Hospital Name'; ?>
                            </span>
                        </div>
                    <?php endforeach;
                    ?>
                </div>
            </div>

            <div class="form-section">
                <label class="form-label">Select Available Date</label>
                <div class="date-box" id="date-box"></div>
            </div>

            <div class="form-section">
                <label class="form-label">Available Time</label>
                <div class="time-box" id="time-box"></div>
            </div>

            <form id="booking-form">
                <!-- existing patient name & symptoms inputs -->
                <div class="form-section">
                    <label class="form-label">Patient Name</label>
                    <input type="text" class="form-control" name="patient_name" id="patient_name" required placeholder="Enter your name">
                </div>
                <div class="form-section">
                    <label class="form-label">Symptoms</label>
                    <textarea class="form-control" name="symptoms" id="symptoms" rows="3" required placeholder="Describe your symptoms"></textarea>
                </div>

                <!-- Hidden inputs to store selections -->
                <input type="hidden" name="hospital_id" id="hospital_id" value="<?php echo $hospital_id; ?>">
                <input type="hidden" name="useremail" id="useremail" value="<?php echo $useremail; ?>">
                <input type="hidden" name="doctor_id" id="doctor_id" value="">
                <input type="hidden" name="appointment_date" id="appointment_date" value="">
                <input type="hidden" name="appointment_time" id="appointment_time" value="">
                <div id="message-box"></div>

                <button type="submit" class="btn btn-primary">Book Appointment</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get elements
            const dateBox = document.getElementById('date-box');
            const timeBox = document.getElementById('time-box');
            const hospitalBoxes = document.querySelectorAll('.hospital-box .option-box');
            const messageBox = document.querySelector("#message-box");
            hospitalBoxes.forEach(box => {
                box.addEventListener('click', function() {
                    Notiflix.Loading.standard("Getting available dates...");
                    hospitalBoxes.forEach(b => b.classList.remove('selected'));
                    this.classList.add('selected');
                    const doctorId = this.getAttribute('data-doctor-id');
                    console.log(doctorId);
                    setTimeout(() => {
                        Notiflix.Loading.remove();
                        fetchDates(doctorId);
                    }, 1000);
                    // Also update a hidden input if needed (see form below)
                    document.getElementById('doctor_id').value = doctorId;
                });
            });

            // Get doctorId from PHP output.
            var hospitalId = document.getElementById("hospital_id").value;
            console.log(hospitalId);

            function getNextDateFromDay(dayStr) {
                const days = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
                const today = new Date();
                let targetIndex = days.indexOf(dayStr.toUpperCase());
                if (targetIndex === -1) return null;
                let diff = targetIndex - today.getDay();
                if (diff <= 0) diff += 7;
                const result = new Date(today);
                result.setDate(today.getDate() + diff);
                return result;
            }

            async function rowCount(hospital_id, doctor_id) {
                const response = await fetch(`/DAS/PHP/count_booking_row.php?hospital_id=${hospital_id}&doctor_id=${doctor_id}`);
                const data = await response.text();
                const jsonData = JSON.parse(data);
                return parseInt(jsonData.total);
            }

            function fetchDates(doctorId) {
                // Clear previous dates and times
                dateBox.innerHTML = '';
                timeBox.innerHTML = '';
                let check = "";
                let checkTemp = new Date();
                (async () => {
                    check = await fetchBookedDates(doctorId);
                    console.log(check);

                    const row = await rowCount(hospitalId, doctorId);
                    console.log(row);

                    let book_date = check.map(date => new Date(date));
                    console.log(book_date);
                    let bookDayStr = book_date.map(date => date.toISOString().slice(0, 10));
                    console.log(bookDayStr);

                    if (isValidDate(book_date)) {
                        const response = await fetch(`/DAS/PHP/fetch_dates.php?hospital_id=${hospitalId}&doctor_id=${doctorId}`)
                            .catch(error => {
                                console.log('Fetch error: ', error);
                                return null;
                            });
                        const data = await response.text();
                        console.log(data);
                        const jsonData = JSON.parse(data);
                        console.log(jsonData);
                        if (jsonData) {
                            if (!Array.isArray(jsonData) || jsonData.length === 0) {
                                console.error('No available dates found');
                                return;
                            }

                            let today = new Date();
                            let uniqueDays = new Set();
                            let nextSevenDays = [];

                            for (let i = 0; i < 60; i++) {
                                let tempDate = new Date();
                                tempDate.setDate(today.getDate() + i);
                                let dateStr = tempDate.toISOString().slice(0, 10);
                                let weekday = tempDate.toLocaleString('default', {
                                    weekday: 'short'
                                });

                                if (jsonData.includes(dateStr) || jsonData.some(d => d.toLowerCase() === weekday.toLowerCase())) {
                                    if (!uniqueDays.has(dateStr) && !bookDayStr.includes(dateStr) || row < 5) {
                                        uniqueDays.add(dateStr);
                                        nextSevenDays.push({
                                            date: dateStr,
                                            d: tempDate
                                        });
                                    }
                                }
                                if (nextSevenDays.length === 7) break;
                            }

                            if (nextSevenDays.length === 0) {
                                console.error('No valid available days found');
                                return;
                            }

                            nextSevenDays.forEach(({
                                date,
                                d
                            }) => {
                                const dateElement = document.createElement('div');
                                dateElement.classList.add('option-box');
                                dateElement.setAttribute('data-date', date);

                                dateElement.innerHTML = `
                    <span class="month">${d.toLocaleString('default', { month: 'short' })}</span>
                    <span class="day">${d.getDate()}</span>
                    <span class="weekday">${d.toLocaleString('default', { weekday: 'short' })}</span>
                `;

                                dateElement.addEventListener('click', function() {
                                    Notiflix.Loading.standard("Getting available times...");
                                    dateBox.querySelectorAll('.option-box').forEach(b => b.classList.remove('selected'));
                                    this.classList.add('selected');
                                    setTimeout(() => {
                                        Notiflix.Loading.remove();
                                        fetchTimes(doctorId, date, d);
                                    }, 1000);
                                });

                                dateBox.appendChild(dateElement);
                            });
                        }
                    } else {
                        const response = await fetch(`/DAS/PHP/fetch_dates.php?hospital_id=${hospitalId}&doctor_id=${doctorId}`)
                            .catch(error => {
                                console.log('Fetch error: ', error);
                                return null;
                            });
                        const data = await response.text();
                        console.log(data);
                        const jsonData = JSON.parse(data);
                        if (jsonData) {
                            if (!Array.isArray(jsonData) || jsonData.length === 0) {
                                console.error('No available dates found');
                                return;
                            }

                            let today = new Date();
                            let uniqueDays = new Set();
                            let nextSevenDays = [];

                            for (let i = 0; i < 60; i++) {
                                let tempDate = new Date();
                                tempDate.setDate(today.getDate() + i);
                                let dateStr = tempDate.toISOString().slice(0, 10);
                                let weekday = tempDate.toLocaleString('default', {
                                    weekday: 'short'
                                });

                                if (jsonData.includes(dateStr) || jsonData.some(d => d.toLowerCase() === weekday.toLowerCase())) {
                                    if (!uniqueDays.has(dateStr)) {
                                        uniqueDays.add(dateStr);
                                        nextSevenDays.push({
                                            date: dateStr,
                                            d: tempDate
                                        });
                                    }
                                }
                                if (nextSevenDays.length === 7) break;
                            }

                            if (nextSevenDays.length === 0) {
                                console.error('No valid available days found');
                                return;
                            }

                            nextSevenDays.forEach(({
                                date,
                                d
                            }) => {
                                const dateElement = document.createElement('div');
                                dateElement.classList.add('option-box');
                                dateElement.setAttribute('data-date', date);

                                dateElement.innerHTML = `
                    <span class="month">${d.toLocaleString('default', { month: 'short' })}</span>
                    <span class="day">${d.getDate()}</span>
                    <span class="weekday">${d.toLocaleString('default', { weekday: 'short' })}</span>
                `;

                                dateElement.addEventListener('click', function() {
                                    Notiflix.Loading.standard("Getting available times...");
                                    dateBox.querySelectorAll('.option-box').forEach(b => b.classList.remove('selected'));
                                    this.classList.add('selected');
                                    setTimeout(() => {
                                        Notiflix.Loading.remove();
                                        fetchTimes(doctorId, date, d);
                                    }, 1000);

                                });

                                dateBox.appendChild(dateElement);
                            });
                        }
                    }

                })();
            }

            function fetchTimes(doctorId, date, d) {
                // Clear previous times
                timeBox.innerHTML = '';
                fetch(`/DAS/PHP/fetch_times.php?hospital_id=${hospitalId}&date=${date}&doctor_id=${doctorId}&day=${d.toLocaleString('default', { weekday: 'short' })}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(time => {
                            const timeElement = document.createElement('div');
                            timeElement.classList.add('option-box');
                            timeElement.innerHTML = `<span class="time">${time}</span>`;
                            timeElement.addEventListener('click', function() {
                                timeBox.querySelectorAll('.option-box').forEach(b => b.classList.remove('selected'));
                                this.classList.add('selected');
                            });
                            timeBox.appendChild(timeElement);
                        });
                    });
            }

            document.getElementById("booking-form").addEventListener("submit", async function(event) {
                event.preventDefault(); // Prevent page reload

                Notiflix.Loading.standard("Making appointment...");

                const hospitalId = document.querySelector("#hospital_id").value;
                const useremail = document.querySelector("#useremail").value;
                const doctorId = document.querySelector("#doctor_id").value;
                const selectedDate = document.querySelector(".date-box .option-box.selected")?.dataset.date;

                const selectedTimeElement = document.querySelector(".time-box .option-box.selected");
                const selectedTime = selectedTimeElement ? (selectedTimeElement.dataset.time || selectedTimeElement.innerText.trim()) : null;

                const patientName = document.querySelector("#patient_name").value;
                const symptoms = document.querySelector("#symptoms").value;

                let tokens = await getToken(hospitalId, doctorId);
                console.log(tokens);

                let booked_token = 1;

                for (let i = 1; i <= 5; i++) {
                    if (!tokens.includes(i)) {
                        booked_token = i;
                        break;
                    }
                }

                console.log("Booked Tokens: ", booked_token);

                console.log("Selected Date:", selectedDate);
                console.log("Selected Time:", selectedTime);
                console.log("Email: ", useremail);

                if (!selectedDate || !selectedTime) {
                    messageBox.innerHTML = "<p style='color:red;'>Please select a valid date and time.</p>";
                    Notiflix.Loading.remove();
                    return;
                }

                // Prepare form data
                const formData = new FormData();
                formData.append("hospital_id", hospitalId);
                formData.append("doctor_id", doctorId);
                formData.append("useremail", useremail);
                formData.append("date", selectedDate);
                formData.append("time", selectedTime);
                formData.append("patient_name", patientName);
                formData.append("symptoms", symptoms);
                formData.append("token_number", booked_token);

                // Send AJAX request
                fetch("/DAS/PHP/booking_appointment.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        console.log(data);
                        const jsonData = JSON.parse(data);
                        Notiflix.Loading.remove();
                        if (jsonData.error) {
                            messageBox.innerHTML = `<p style='color:red;'>${jsonData.error}</p>`;
                        } else {
                            const timeElement = document.createElement('div');
                            document.getElementById("booking-form").reset();
                            dateBox.innerHTML = '';
                            timeBox.innerHTML = '';
                            messageBox.innerHTML = `<p style='color:green;'>${jsonData.message}</p>`;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        messageBox.innerHTML = "<p style='color:red;'>Something went wrong. Please try again.</p>";
                    });
            });

            async function getToken(hospitalId, doctorId) {
                const response = await fetch(`/DAS/PHP/get_token_number.php?hospital_id=${hospitalId}&doctor_id=${doctorId}`);
                const data = await response.text();
                const jsonData = JSON.parse(data);
                return jsonData;
            }

            async function fetchBookedDates(doctorId) {
                try {
                    const response = await fetch(`/DAS/PHP/fetch_booked_dates.php?hospital_id=${hospitalId}&doctor_id=${doctorId}`);
                    const data = await response.text();
                    console.log('Raw data:', data); // Log the raw response data
                    const jsonData = JSON.parse(data); // Parse the JSON string
                    if (jsonData && jsonData.available_dates) {
                        console.log('Available Dates:', jsonData.available_dates); // Log the available dates
                        return jsonData.available_dates;
                    } else {
                        console.log('available_dates not found or is empty');
                    }
                } catch (error) {
                    console.error('Error: ', error.message);
                    messageBox.innerHTML = "<p style='color:red;'>Something went wrong. Please try again.</p>";
                }
            }

            function isValidDate(dateStr) {
                let dates = dateStr.map(date => new Date(date));
                console.log(dates);
                return dates.every(date => !isNaN(date.getTime()));
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/notiflix@3.2.7/dist/notiflix-3.2.7.min.js"></script>
</body>

</html>