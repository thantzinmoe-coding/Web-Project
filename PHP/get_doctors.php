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

// Get offset from AJAX request
$offset = isset($_POST['offset']) ? (int)$_POST['offset'] : 0;
$limit = 20; // Number of doctors per page

// Fetch doctors with LIMIT and OFFSET
$sql = "SELECT * FROM doctors LIMIT $limit OFFSET $offset";
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
    echo ""; // No more doctors to load
}

$conn->close();
