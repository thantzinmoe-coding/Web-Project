<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Hospital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <div class="container my-4">
        <h1 class="text-center mb-4">Find Your Healthcare Here</h1>

        <!-- Search Bar -->
        <div class="search-bar">
            <input type="text" id="searchHospital" class="form-control" placeholder="Search hospital by name...">
            <input type="text" id="filterLocation" class="form-control" placeholder="Add city or region">
            <button class="btn btn-primary" onclick="fetchHospitals()">Search</button>
        </div>

        <!-- Filters -->
        <div class="filter-section mt-4">
            <h5>Filter By:</h5>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="24/7" id="emergencyFilter">
                <label class="form-check-label" for="emergencyFilter">24/7 Emergency Services</label>
            </div>
            <div class="mt-2">
                <label for="filterSpecialty">Specialty:</label>
                <select id="filterSpecialty" class="form-select">
                    <option value="">All Specialties</option>
                    <option value="Cardiology">Cardiology</option>
                    <option value="Pediatrics">Pediatrics</option>
                    <option value="Neurology">Neurology</option>
                </select>
            </div>
        </div>

        <!-- Hospital List -->
        <div id="hospitalList" class="mt-4">
            <!-- Hospital Cards Will Be Injected Here -->
        </div>
    </div>

    <script>
        function fetchHospitals() {
            const search = document.getElementById('searchHospital').value;
            const location = document.getElementById('filterLocation').value;
            const specialty = document.getElementById('filterSpecialty').value;
            const emergency = document.getElementById('emergencyFilter').checked;

            fetch(`fetch_hospitals.php?search=${search}&location=${location}&specialty=${specialty}&emergency=${emergency}`)
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

            hospitals.forEach(hospital => {
                const card = `
            <div class="hospital-card">
                <h4>${hospital.name}</h4>
                <p>Location: ${hospital.location}</p>
                <p>Specialty: ${hospital.specialty}</p>
                <p>Contact: ${hospital.contact}</p>
                <p>Rating: ${hospital.rating} ⭐</p>
                ${hospital.emergency_services == 1 ? '<p>24/7 Emergency Available 🚑</p>' : ''}
                <button class="btn btn-primary">Book Appointment</button>
            </div>`;
                hospitalList.innerHTML += card;
            });
        }

        // Initial Load
        fetchHospitals();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>