<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Hospital</title>
    <link rel="stylesheet" href="/DAS/CSS/Find_Hospital.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Amita:wght@400;700&family=Poppins:wght@300;400;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notiflix@3.2.7/src/notiflix.min.css">
    <!-- Add AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
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
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm" data-aos="fade-down">
        <div class="container">
            <a class="navbar-brand nav-brand" href="#">DAS</a>
            <span class="text-muted ms-3">WE VALUE YOUR HEALTH</span>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fa fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item" data-aos="fade-down" data-aos-delay="100">
                        <a class="nav-link active" href="/DAS/Home">Home</a>
                    </li>
                    <li class="nav-item" data-aos="fade-down" data-aos-delay="200">
                        <a class="nav-link" href="/DAS/doctor">Find Doctor</a>
                    </li>
                    <li class="nav-item" data-aos="fade-down" data-aos-delay="300">
                        <a class="nav-link" href="/DAS/hospital">Find Hospital</a>
                    </li>
                    <?php
                    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
                        // Database Connection
                        $conn = new mysqli('localhost', 'root', '', 'project');

                        if ($conn->connect_error) {
                            die('Connection failed: ' . $conn->connect_error);
                        }

                        $user_id = $_SESSION['user_id'];

                        // Fetch user profile image from database
                        $stmt = $conn->prepare('SELECT profile_image FROM users WHERE userID = ?');
                        $stmt->bind_param('i', $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $user = $result->fetch_assoc();
                        $stmt->close();
                        $conn->close();

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

    <form>
        <input type="hidden" name="" id="useremail" value="<?php
                                                            if (isset($_SESSION['email'])) {
                                                                $useremail = $_SESSION['email'];
                                                                echo $useremail;
                                                            }
                                                            ?>">
    </form>

    <div class="container">
        <h1 class="text-center mb-4" data-aos="fade-up">Find Your Healthcare Here</h1>
        <div class="search-bar" data-aos="fade-up" data-aos-delay="100">
            <input type="text" id="searchHospital" class="form-control" placeholder="Search hospital by name...">
            <input type="text" id="filterLocation" class="form-control" placeholder="Add city or region">
            <button onclick="fetchHospitals()">Search</button>
        </div>
        <div id="hospitalList" class="mt-4"></div>
    </div>

    <script>
    function fetchHospitals() {
        const search = document.getElementById('searchHospital').value;
        const location = document.getElementById('filterLocation').value;

        fetch(`/DAS/PHP/fetch_hospitals.php?search=${search}&location=${location}`)
            .then(response => response.json())
            .then(data => displayHospitals(data))
            .catch(error => console.error('Error fetching hospitals:', error));
    }

    function displayHospitals(hospitals) {
        const hospitalList = document.getElementById('hospitalList');
        hospitalList.innerHTML = '';

        if (hospitals.length === 0) {
            hospitalList.innerHTML = '<p>No hospitals found.</p>';
            return;
        }

        const useremail = document.getElementById("useremail").value;
        console.log(useremail);

        hospitals.forEach(hospital => {
            const card = `
                <div class="hospital-card" data-aos="fade-up">
                    <h4>${hospital.name}</h4>
                    <p><strong>Location:</strong> ${hospital.location}</p>
                    <p><strong>Specialty:</strong> ${hospital.specialty}</p>
                    <p><strong>Contact:</strong> ${hospital.contact}</p>
                    <p><strong>Rating:</strong> ${hospital.rating} ⭐</p>
                    ${hospital.emergency_services == 1 ? '<p><strong>24/7 Emergency Available 🚑</strong></p>' : ''}
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true): ?>
                    <form method="POST" action="/DAS/booking-hospital">
                        <input type="hidden" name="hospital_id" value="${hospital.hospital_id}">
                        <input type="hidden" name="email" value="${useremail}">
                        <button type="submit" class="btn btn-success">Book Appointment</button>
                    </form>
                    <?php else: ?>
                        <button type="submit" class="btn btn-warning" onclick="redirectToLogin()">Book Appointment</button>
                        </form>
                    <?php endif ?>
                </div>`;
            hospitalList.innerHTML += card;
        });
    }

    fetchHospitals();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

    function redirectToLogin() {
        Notiflix.Report.warning('Warning', 'You need to login first to make appointment!', 'Okay', () => {
            window.location.href = "/DAS/login";
        });
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/notiflix"></script>
</body>

</html>