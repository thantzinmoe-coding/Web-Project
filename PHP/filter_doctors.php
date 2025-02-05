<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$jobType = isset($_POST['job_type']) ? $conn->real_escape_string($_POST['job_type']) : 'All';
$gender = isset($_POST['gender']) ? $conn->real_escape_string($_POST['gender']) : 'All';

// Build the query dynamically based on filters
$sql = "SELECT * FROM doctors WHERE 1=1";

if ($jobType !== 'All') {
    $sql .= " AND job_type = '$jobType'";
}

if ($gender !== 'All') {
    $sql .= " AND gender = '$gender'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='job-card'>";
        echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
        echo "<p>" . htmlspecialchars($row['credential']) . "</p>";
        echo "<div class='details'>";
        echo "<span>Consultation Fee: " . number_format($row['consultation_fee']) . " MMK</span>";
        echo "<button>Book Now</button>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<p>No doctors found matching the selected criteria.</p>";
}
$conn->close();
