<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Amita:wght@400;700&family=Poppins:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../CSS/homepage.css">
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
</body>

</html>