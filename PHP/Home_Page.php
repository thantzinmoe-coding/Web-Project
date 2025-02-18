<?php

session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Amita:wght@400;700&family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notiflix@3.2.7/src/notiflix.min.css">
    <link rel="stylesheet" href="../CSS/homepage.css">
    <style>
        .profile-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .logout {
            display: flex;
            justify-content: flex-end;
            padding: 10px;
            margin-top: -50px;
            margin-left: 10px;
        }

        .logout button {
            padding: 5px;
            background-color: #f44336;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand nav-brand" href="#">DAS</a>
            <span class="text-muted ms-3">WE VALUE YOUR HEALTH</span>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="Home_page.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Find_Doctor.php">Find Doctor</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="Find_Hospital.php">Find Hospital</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) { ?>
                        <li class="nav-item">
                            <a href="#"><img src="../Image/profile2.png" class="profile-icon"></a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item"> <a href="../Html/Sign_In.html" class="btn btn-success ms-3">Sign In</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>

    <header class="bg-success text-white text-center py-5">
        <h1>Your Health is Just a Click Away</h1>
        <p>Explore hospitals, find doctors, book appointments, and access health resources</p>
    </header>

    <section class="container my-5">
        <h2 class="text-center text-success mb-4">Top Hospitals</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card hospital-card">
                    <img src="../image/h1.jpg" alt="Bridgeport Hospital" class="card-img-top">
                    <div class="card-body text-center">
                        <h5 class="card-title">Bridgeport Hospital</h5>
                        <p class="text-muted">Specialized in Cardiology and General Surgery</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card hospital-card">
                    <img src="../image/h2.jpg" alt="St. Vincent's Medical Center" class="card-img-top">
                    <div class="card-body text-center">
                        <h5 class="card-title">St. Vincent's Medical Center</h5>
                        <p class="text-muted">Renowned for Neurology and Orthopedics</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card hospital-card">
                    <img src="../image/h3.jpg" alt="Yale New Haven Hospital" class="card-img-top">
                    <div class="card-body text-center">
                        <h5 class="card-title">Yale New Haven Hospital</h5>
                        <p class="text-muted">Expertise in Pediatrics and Oncology</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <h2 class="text-center mb-4">Why Choose DAS?</h2>
            <div class="row text-center">
                <div class="col-md-4">
                    <i class="fa fa-user-md"></i>
                    <h5 class="mt-3">Expert Doctors</h5>
                    <p>Connect with top-rated doctors and specialists in your area.</p>
                </div>
                <div class="col-md-4">
                    <i class="fa fa-hospital"></i>
                    <h5 class="mt-3">Trusted Hospitals</h5>
                    <p>Find hospitals with the best facilities and care.</p>
                </div>
                <div class="col-md-4">
                    <i class="fa fa-calendar-check"></i>
                    <h5 class="mt-3">Easy Appointments</h5>
                    <p>Book appointments with just a few clicks.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="container my-5">
        <h2 class="text-center text-success mb-4">Testimonials</h2>
        <div class="row">
            <div class="col-md-6">
                <p class="testimonial">"DAS helped me find the best doctor for my condition. The process was seamless and efficient!" - Sarah J.</p>
            </div>
            <div class="col-md-6">
                <p class="testimonial">"Booking an appointment has never been easier. Highly recommend DAS to everyone!" - Mark T.</p>
            </div>
        </div>
    </section>

    <footer class="text-center">
        <div class="container">
            <p>&copy; 2025 DAS Healthcare. All Rights Reserved.</p>
            <p>
                Address: 123 Main St, Bridgeport, CT 06604<br>
                Phone: 203-555-5555<br>
                Email: <a href="mailto:info@healthcare.com">info@healthcare.com</a>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggler = document.querySelector('.navbar-toggler');
        const togglerIcon = toggler.querySelector('i');

        toggler.addEventListener('click', () => {
            togglerIcon.classList.toggle('rotate');
            togglerIcon.classList.toggle('fa-bars');
            togglerIcon.classList.toggle('fa-times');
        });
    </script>
    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
        echo    '<div class="logout">
                    <form action="" id="logout">
                        <button type="submit">Log out</button>
                    </form>
                </div>';
    } else {
        echo '';
    }
    ?>
    <script src="../JS/jquery.js"></script>
    <script>
        $(document).ready(function() {
            $('#logout').submit(function(e) {
                e.preventDefault();
                Notiflix.Loading.standard('Logging out...');
                $.ajax({
                    type: 'POST',
                    url: '../PHP/logout.php',
                    success: function(response) {
                        if (response.status == 'success') {
                            Notiflix.Report.success('Success', response.message, 'OK', function() {
                                window.location.href = 'Home_Page.php';
                            });
                        } else {
                            Notiflix.Report.failure('Error', response.message, 'OK');
                        }
                    },
                    error: function(err) {
                        Notiflix.Report.failure('Error', 'An error occurred, please try again.', 'OK');
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/notiflix"></script>
</body>

</html>