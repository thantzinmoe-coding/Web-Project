<?php
// Database connection
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['email'])) {
    $useremail = $_SESSION['email'];
}

// Fetch unique job types
$jobTypeSql = "SELECT DISTINCT job_type FROM doctors";
$jobTypeResult = $conn->query($jobTypeSql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DAS - Find Doctor</title>
    <link rel="stylesheet" href="/DAS/CSS/Find_Doctor.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Amita:wght@400;700&family=Poppins:wght@300;400;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notiflix@3.2.7/src/notiflix.min.css">
    <!-- Add AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    body {
        font-family: 'Poppins', sans-serif;
    }

    .nav-brand {
        font-family: 'Amita', serif;
        font-size: 2rem;
        font-weight: 700;
        color: #28a745;
    }

    .navbar-toggler i {
        transition: transform 0.3s ease;
    }

    .rotate {
        transform: rotate(90deg);
    }

    .profile-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
    }

    /* Responsive Styles */
    .container {
        display: flex;
        flex-wrap: wrap;
        margin-top: 10px;
        /* Allow wrapping of filters and results */
        gap: 20px;
        /* Space between filters and results */
    }

    .filters {
        flex: 1 1 250px;
        /* Filters take up a fixed width or 250px, whichever is larger */
    }

    .results {
        flex: 3 1 500px;
        /* Results take up remaining space */
    }

    .job-card {
        border: 1px solid #ccc;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 8px;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .job-card:hover {
        transform: scale(1.02);
    }

    .job-card h3 {
        margin-bottom: 10px;
    }

    .job-card p {
        margin-bottom: 5px;
    }

    .job-card .details {
        display: flex;
        justify-content: space-between;
        /* Align items on opposite ends */
        align-items: center;
        /* Vertically center items */
    }

    .job-card .details span {
        font-weight: bold;
    }

    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>.job-card .details button {
        background-color: #28a745;
    }

    <?php else: ?>.job-card .details button {
        background-color: #ffc107;
    }

    <?php endif ?>@media (max-width: 768px) {
        .filters {
            flex: 1 1 100%;
            /* Filters take full width on smaller screens */
            margin-bottom: 20px;
            /* Add margin below filters */
        }

        .results {
            flex: 1 1 100%;
            /* Results also take full width */
        }
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm" data-aos="fade-down">
        <div class="container">
            <a class="navbar-brand nav-brand" href="#">DAS</a>
            <span class="text-muted ms-3">WE VALUE YOUR HEALTH</span>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item" data-aos="fade-down" data-aos-delay="100">
                        <a class="nav-link active" aria-current="page" href="/DAS/Home">Home</a>
                    </li>
                    <li class="nav-item" data-aos="fade-down" data-aos-delay="200">
                        <a class="nav-link" href="/DAS/doctor">Find Doctor</a>
                    </li>
                    <li class="nav-item" data-aos="fade-down" data-aos-delay="300">
                        <a class="nav-link" href="/DAS/hospital">Find Hospital</a>
                    </li>
                    <?php
                    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {

                        $user_id = $_SESSION['user_id'];

                        // Fetch user profile image from database
                        $stmt = $conn->prepare('SELECT profile_image FROM users WHERE userID = ?');
                        $stmt->bind_param('i', $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $user = $result->fetch_assoc();
                        $stmt->close();

                        // Set profile image path (Use default image if none exists)
                        $profileImage = (!empty($user['profile_image'])) ? '/DAS/PHP/uploads/' . $user['profile_image'] : '/DAS/PHP/uploads/bx-user-circle.svg';
                    ?>
                    <li class="nav-item" data-aos="fade-down" data-aos-delay="400">
                        <a href="/DAS/profile">
                            <img src="<?php echo htmlspecialchars($profileImage); ?>?t=<?php echo time(); ?>"
                                class="profile-icon rounded-circle" width="40" height="40" style="object-fit: cover;">
                        </a>
                    </li>
                    <?php } else { ?>
                    <li class="nav-item" data-aos="fade-down" data-aos-delay="400">
                        <a href="/DAS/login" class="btn btn-success ms-3">Sign In</a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Filters Section -->
        <aside class="filters" data-aos="fade-right">
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
        <main class="results" data-aos="fade-left">
            <input type="text" id="search-box" placeholder="Search for doctors..."
                style="width: 50%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 4px;">
            <h2>Doctor - Search Results</h2>
            <div id="doctor-list">
                <?php
                $sql = "SELECT * FROM doctors LIMIT 20";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $doctor_id = $row['doctor_id'];
                        echo "<div class='job-card' data-aos='fade-up'>";
                        echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                        echo "<p>" . htmlspecialchars($row['credential']) . "</p>";
                        echo "<div class='details'>";
                        echo "<span>Consultation Fee: " . number_format($row['consultation_fee']) . " MMK</span>";
                        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
                            // If user is logged in, allow booking
                            echo '<form method="POST" action="/DAS/booking-doctor">';
                            echo '<input type="hidden" name="doctor_id" value="' . filter_var($row['doctor_id'], FILTER_VALIDATE_INT) . '">';
                            echo '<input type="hidden" name="email" value="' . $useremail . '">';
                            echo '<button type="submit">Book Now</button>';
                            echo '</form>';
                        } else {
                            // If user is not logged in, show alert and redirect using JavaScript
                            echo '<button onclick="redirectToLogin()">Book Now</button>';
                        }
                        echo '</form>';
                        echo "</div>";
                        echo "</div>";
                    }
                    if (isset($_POST['doctor_id'])) {
                        exit;
                    }
                } else {
                    echo "<p>No doctors found.</p>";
                }
                ?>
            </div>
            <script>
            function redirectToLogin() {
                Notiflix.Report.warning('Warning', 'You need to login first to make appointment!', 'Okay', () => {
                    window.location.href = "/DAS/login"; // Redirect after alert
                });
            }
            </script>

            <button id="load-more" style="display: block; margin: 20px auto;" class="btn btn-primary"
                data-aos="fade-up">See More</button>
        </main>
    </div>
    <script>
    $(document).ready(function() {
        let offset = 20;

        // "See More" button for pagination
        $("#load-more").click(function() {
            $.ajax({
                url: "/DAS/PHP/get_doctors.php",
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
                    url: "/DAS/PHP/search_doctors.php",
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
                    url: "/DAS/PHP/get_doctors.php",
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
                url: "/DAS/PHP/filter_doctors.php",
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
    <script src="https://cdn.jsdelivr.net/npm/notiflix"></script>
    <!-- Add AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
    // Initialize AOS
    AOS.init({
        duration: 1000, // Animation duration
        once: true, // Whether animation should happen only once
    });

    const toggler = document.querySelector('.navbar-toggler');
    const togglerIcon = toggler.querySelector('i');

    toggler.addEventListener('click', () => {
        togglerIcon.classList.toggle('rotate');
        togglerIcon.classList.toggle('fa-bars');
        togglerIcon.classList.toggle('fa-times');
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>