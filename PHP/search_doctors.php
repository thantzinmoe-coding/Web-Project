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

// Get the search query
$search = isset($_POST['search']) ? $conn->real_escape_string($_POST['search']) : '';

// Basic String Matching Algorithm (Case-Insensitive)
$sql = "SELECT * FROM doctors 
        WHERE LOWER(name) LIKE LOWER('%$search%') 
        OR LOWER(job_type) LIKE LOWER('%$search%') 
        OR LOWER(credential) LIKE LOWER('%$search%')";

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
    echo "<p>No doctors found matching your search.</p>";
}

$conn->close();
