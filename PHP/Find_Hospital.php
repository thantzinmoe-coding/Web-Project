<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Hospital - DAS</title>
    <link rel="stylesheet" href="../CSS/Find_Hospital.css">
</head>

<body>
    <header>
        <nav>
            <div class="logo">DAS</div>
            <ul>
                <li><a href="Home_page.php">Home</a></li>
                <li><a href="Find_Doctor.php">Find Doctor</a></li>
                <li><a href="Find_Hospital.php">Find Hospital</a></li>
                <li><a href="Sign_In.php">Sign In</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <aside class="filters">
            <h2>Specialties</h2>
            <div>
                <h3>Specialty</h3>
                <label><input type="radio" name="specialty"> Cardiology</label>
                <label><input type="radio" name="specialty"> Neurology</label>
                <label><input type="radio" name="specialty"> Pediatrics</label>
                <label><input type="radio" name="specialty"> Orthopedics</label>
            </div>
            <div>
                <h3>Gender</h3>
                <label><input type="radio" name="gender"> Male</label>
                <label><input type="radio" name="gender"> Female</label>
            </div>
        </aside>

        <main class="results">
            <div class="search-bar">
                <input type="text" id="search" placeholder="Search hospitals...">
                <button onclick="searchHospitals()">Search</button>
            </div>
            <h2>Hospital - Search Results</h2>

            <div class="hospital-list">
                <?php
                include 'connection.php';
                include 'Hospital.php';

                $conn = connect();
                if ($conn == null) {
                    die("Database Connection Failed");
                } else {
                    $hospital = new Hospital($conn);
                    $result = $hospital->fetchAll();
                    foreach ($result as $row) {
                        echo '<div class="hospital-card">';
                        echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                        echo '<p><strong>Address:</strong> ' . htmlspecialchars($row['address']) . '</p>';
                        echo '<p><strong>City:</strong> ' . htmlspecialchars($row['city']) . '</p>';
                        echo '<p><strong>Phone Number:</strong> ' . htmlspecialchars($row['phone_number']) . '</p>';
                        echo '<p><strong>Email:</strong> <a href="mailto:' . htmlspecialchars($row['email']) . '">' . htmlspecialchars($row['email']) . '</a></p>';
                        echo '<p><strong>Website:</strong> <a href="' . htmlspecialchars($row['website']) . '" target="_blank">' . htmlspecialchars($row['website']) . '</a></p>';
                        echo '<button>Book Now</button>';
                        echo '</div>';
                    }
                }
                ?>
            </div>
        </main>
    </div>

    <script>
        function searchHospitals() {
            alert("Search functionality coming soon!");
        }
    </script>
</body>

</html>