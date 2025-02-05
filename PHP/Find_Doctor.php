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

// Fetch unique job types
$jobTypeSql = "SELECT DISTINCT job_type FROM doctors";
$jobTypeResult = $conn->query($jobTypeSql);

// Fetch the first 20 doctors
$sql = "SELECT * FROM doctors LIMIT 20";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAS - Find Doctor</title>
    <link rel="stylesheet" href="../CSS/Find_Doctor.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
        <!-- Filters Section -->
        <aside class="filters">
            <h2>Specialties</h2>
            <div>
                <!-- <h3>Specialties</h3> -->
                <label><input type="radio" name="specialty" value="All" checked> All</label><br>
                <?php
                if ($jobTypeResult->num_rows > 0) {
                    while ($jobTypeRow = $jobTypeResult->fetch_assoc()) {
                        $jobType = htmlspecialchars($jobTypeRow['job_type']);
                        echo "<label><input type='radio' name='specialty' value='{$jobType}'> {$jobType}</label><br>";
                    }
                } else {
                    echo "<p>No specialties available.</p>";
                }
                ?>
            </div>
            <div>
                <h3>Gender</h3>
                <label><input type="radio" name="gender" value="All" checked> All</label><br>
                <label><input type="radio" name="gender" value="Male"> Male</label><br>
                <label><input type="radio" name="gender" value="Female"> Female</label><br>
            </div>
        </aside>


        <!-- Doctor Results Section -->
        <main class="results">
            <input type="text" id="search-box" placeholder="Search for doctors..." style="width: 50%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 4px;">
            <h2>Doctor - Search Results</h2>
            <div id="doctor-list">
                <?php
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
                    echo "<p>No doctors found.</p>";
                }
                ?>
            </div>
            <button id="load-more" style="display: block; margin: 20px auto;">See More</button>
        </main>
    </div>
    <script>
        $(document).ready(function() {
            let offset = 20;

            // "See More" button for pagination
            $("#load-more").click(function() {
                $.ajax({
                    url: "get_doctors.php",
                    type: "POST",
                    data: {
                        offset: offset
                    },
                    success: function(data) {
                        if ($.trim(data) !== "") {
                            $("#doctor-list").append(data);
                            offset += 20;
                        } else {
                            $("#load-more").hide();
                        }
                    }
                });
            });

            // Real-time Search
            $("#search-box").on("input", function() {
                const query = $(this).val().trim();
                if (query.length > 0) {
                    $.ajax({
                        url: "search_doctors.php",
                        type: "POST",
                        data: {
                            search: query
                        },
                        success: function(data) {
                            $("#doctor-list").html(data);
                            $("#load-more").hide();
                        }
                    });
                } else {
                    $.ajax({
                        url: "get_doctors.php",
                        type: "POST",
                        data: {
                            offset: 0
                        },
                        success: function(data) {
                            $("#doctor-list").html(data);
                            $("#load-more").show();
                            offset = 20;
                        }
                    });
                }
            });

            // Filter by Job Type and Gender
            $("input[name='specialty'], input[name='gender']").on("change", function() {
                const selectedJobType = $("input[name='specialty']:checked").val();
                const selectedGender = $("input[name='gender']:checked").val();

                $.ajax({
                    url: "filter_doctors.php",
                    type: "POST",
                    data: {
                        job_type: selectedJobType,
                        gender: selectedGender
                    },
                    success: function(data) {
                        $("#doctor-list").html(data);
                        $("#load-more").hide(); // Hide "See More" when filtered
                    }
                });
            });
        });
    </script>
</body>

</html>