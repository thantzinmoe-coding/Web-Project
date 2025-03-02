<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Amita:wght@400;700&family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notiflix@3.2.7/src/notiflix.min.css">
    <link rel="stylesheet" href="/DAS/CSS/homepage.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        /* Custom Styles */
        body {
            font-family: 'Poppins', sans-serif;
            color: #333;
        }

        .navbar {
            background: linear-gradient(90deg, #1a73e8, #155ea7);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-family: 'Amita', cursive;
            font-size: 1.8rem;
            font-weight: bold;
            color: #fff !important;
        }

        .navbar-nav .nav-link {
            color: #fff !important;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            color: #ffcc00 !important;
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
            border: 2px solid #fff;
            transition: transform 0.3s ease;
        }

        .profile-icon:hover {
            transform: scale(1.1);
        }

        .container {
            max-width: 1200px;
            gap: 20px;
        }

        header {
            background: url('/DAS/image/header-bg.jpg') no-repeat center center/cover;
            padding: 100px 0;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        header h1 {
            font-size: 3rem;
            font-weight: bold;
        }

        header p {
            font-size: 1.2rem;
        }

        .hospital-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hospital-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .features {
            background-color: #f8f9fa;
            padding: 60px 0;
        }

        .features h2 {
            color: #1a73e8;
            margin-bottom: 40px;
        }

        .features h5 {
            color: #333;
            margin-top: 20px;
        }

        .features p {
            color: #555;
        }

        .features i {
            font-size: 3rem;
            color: #1a73e8;
            transition: transform 0.3s ease;
        }

        .features i:hover {
            transform: scale(1.2);
        }

        .testimonial-carousel .carousel-item {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .testimonial-carousel .carousel-item p {
            color: #333;
            font-size: 1.1rem;
            font-style: italic;
        }

        footer {
            background: linear-gradient(90deg, #1a73e8, #155ea7);
            color: #fff;
            padding: 40px 0;
        }

        footer a {
            color: #ffcc00;
            text-decoration: none;
        }

        footer a:hover {
            text-decoration: underline;
        }

        .social-icons a {
            color: #fff;
            font-size: 1.5rem;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        .social-icons a:hover {
            color: #ffcc00;
        }

        .dropdown-menu {
            background-color: rgb(208, 211, 208);
            width: 100%;
        }

        @media (max-width: 768px) {
            .search-bar {
                flex-direction: column;
            }

            .search-bar button {
                width: 100%;
            }

            .navbar-collapse {
                text-align: center;
            }

            .navbar-nav {
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }

            .navbar-nav .nav-item {
                width: 80%;
            }

            .navbar-nav .btn {
                width: 60%;
                margin-top: 10px;
                left: -20px;
            }

            .navbar-toggler {
                margin-left: auto;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">DAS</a>
            <span class="text-light ms-3">WE VALUE YOUR HEALTH</span>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fa fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="/DAS/Home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/DAS/doctor">Find Doctor</a></li>
                    <li class="nav-item"><a class="nav-link" href="/DAS/hospital">Find Hospital</a></li>
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
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <img src="<?php echo htmlspecialchars($profileImage); ?>?t=<?php echo time(); ?>" class="profile-icon" alt="Profile">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/DAS/profile">Profile</a></li>
                                <li><a class="dropdown-item" href="#" id="logout">Logout</a></li>
                            </ul>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a href="/DAS/login" class="btn btn-success">Sign In</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <header class="text-center py-5" data-aos="fade-down" data-aos-duration="1000">
        <h1>Your Health is Just a Click Away</h1>
        <p>Explore hospitals, find doctors, book appointments, and access health resources</p>
    </header>

    <!-- Top Hospitals -->
    <section class="container my-5">
        <h2 class="text-center text-success mb-4" data-aos="fade-up" data-aos-duration="1000">Top Hospitals</h2>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-right" data-aos-duration="1000">
                <div class="card hospital-card">
                    <img src="/DAS/image/h1.jpg" alt="Bridgeport Hospital" class="card-img-top">
                    <div class="card-body text-center">
                        <h5 class="card-title">Bridgeport Hospital</h5>
                        <p class="text-muted">Specialized in Cardiology and General Surgery</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-duration="1000">
                <div class="card hospital-card">
                    <img src="/DAS/image/h2.jpg" alt="St. Vincent's Medical Center" class="card-img-top">
                    <div class="card-body text-center">
                        <h5 class="card-title">St. Vincent's Medical Center</h5>
                        <p class="text-muted">Renowned for Neurology and Orthopedics</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-left" data-aos-duration="1000">
                <div class="card hospital-card">
                    <img src="/DAS/image/h3.jpg" alt="Yale New Haven Hospital" class="card-img-top">
                    <div class="card-body text-center">
                        <h5 class="card-title">Yale New Haven Hospital</h5>
                        <p class="text-muted">Expertise in Pediatrics and Oncology</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="features">
        <div class="container">
            <h2 class="text-center mb-4" data-aos="fade-up" data-aos-duration="1000">Why Choose DAS?</h2>
            <div class="row text-center">
                <div class="col-md-4" data-aos="fade-right" data-aos-duration="1000">
                    <i class="fa fa-user-md"></i>
                    <h5 class="mt-3">Expert Doctors</h5>
                    <p>Connect with top-rated doctors and specialists in your area.</p>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-duration="1000">
                    <i class="fa fa-hospital"></i>
                    <h5 class="mt-3">Trusted Hospitals</h5>
                    <p>Find hospitals with the best facilities and care.</p>
                </div>
                <div class="col-md-4" data-aos="fade-left" data-aos-duration="1000">
                    <i class="fa fa-calendar-check"></i>
                    <h5 class="mt-3">Easy Appointments</h5>
                    <p>Book appointments with just a few clicks.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="container my-5">
        <h2 class="text-center text-success mb-4" data-aos="fade-up" data-aos-duration="1000">Testimonials</h2>
        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-aos="fade-up" data-aos-duration="1000">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <p class="testimonial">"DAS helped me find the best doctor for my condition. The process was seamless and efficient!" - Sarah J.</p>
                </div>
                <div class="carousel-item">
                    <p class="testimonial">"Booking an appointment has never been easier. Highly recommend DAS to everyone!" - Mark T.</p>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center py-4" data-aos="fade-up" data-aos-duration="1000">
        <div class="container">
            <p>&copy; 2025 DAS Healthcare. All Rights Reserved.</p>
            <p>
                Address: 123 Main St, Bridgeport, CT 06604<br>
                Phone: 203-555-5555<br>
                Email: <a href="mailto:info@healthcare.com">info@healthcare.com</a>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/notiflix"></script>
    <script src="/DAS/JS/jquery.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000, // Duration of animation
            once: true, // Whether animation should happen only once - while scrolling down
        });

        const toggler = document.querySelector('.navbar-toggler');
        const togglerIcon = toggler.querySelector('i');

        toggler.addEventListener('click', () => {
            togglerIcon.classList.toggle('rotate');
            togglerIcon.classList.toggle('fa-bars');
            togglerIcon.classList.toggle('fa-times');
        });

        $(document).ready(function() {
            $('#logout').click(function(e) {
                e.preventDefault();
                Notiflix.Loading.standard('Logging out...');
                $.ajax({
                    type: 'POST',
                    url: '/DAS/PHP/logout.php',
                    success: function(response) {
                        if (response.status == 'success') {
                            Notiflix.Report.success('Success', response.message, 'OK',
                                function() {
                                    window.location.href = '/DAS/Home';
                                });
                        } else {
                            Notiflix.Report.failure('Error', response.message, 'OK');
                        }
                    },
                    error: function(err) {
                        Notiflix.Report.failure('Error', 'An error occurred, please try again.',
                            'OK');
                    }
                });
            });
        });
    </script>
</body>

</html>