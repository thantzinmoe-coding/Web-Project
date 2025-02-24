<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $user_id = $_SESSION['user_id'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'project');

    if ($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    // Fetch user data, including profile_image
    $stmt = $conn->prepare('SELECT username, email, otp, status, date, profile_image FROM users WHERE userID = ?');
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo 'No user found.';
        exit();
    }

    $stmt->close();
    $conn->close();

    // Set profile image path
    $profileImage = !empty($user['profile_image']) ? 'uploads/' . $user['profile_image'] : 'uploads/default.png';
} else {
    header('Location: ../Html/Sign_In.html');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Hospital</title>
    <link rel="stylesheet" href="../CSS/Find_Hospital.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Amita:wght@400;700&family=Poppins:wght@300;400;700&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notiflix@3.2.7/src/notiflix.min.css">
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

    /* Profile Card */
    .profile-container {
        max-width: 600px;
        margin: auto;
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .profile-img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #f8f9fa;
        margin: auto;
        margin-bottom: 15px;
    }

    .profile-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .upload-btn {
        background: #28a745;
        color: white;
        font-weight: bold;
        padding: 10px 15px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .upload-btn:hover {
        background: #218838;
    }

    /* Buttons */
    .profile-buttons .btn {
        width: 100%;
        font-weight: bold;
        padding: 12px;
        border-radius: 6px;
        transition: all 0.3s;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    /* Modal */
    .modal-content {
        border-radius: 12px;
        padding: 20px;
    }

    .modal-header {
        border-bottom: none;
        font-size: 1.2rem;
        font-weight: bold;
    }

    .btn-close {
        font-size: 1.2rem;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        .profile-container {
            padding: 20px;
        }
    }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand nav-brand" href="admin_login.php">DAS</a>
            <span class="text-muted ms-3">WE VALUE YOUR HEALTH</span>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                        <a class="nav-link" href="view_appointment.php">Bookings</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Profile Section -->
    <div class="container py-5">
        <div class="profile-container">
            <h2 class="mb-4">Profile</h2>

            <div class="profile-img">
                <img src="<?php echo htmlspecialchars($profileImage); ?>?t=<?php echo time(); ?>" alt="Profile"
                    id="profileImage">
            </div>

            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <!-- Profile Buttons -->
            <div class="profile-buttons mt-4">
                <form id="uploadForm" enctype="multipart/form-data">
                    <input type="file" name="profile_image" id="profileImageInput" accept="image/*" required>
                    <button type="submit" class="btn upload-btn mt-2">Upload Profile Image</button>
                </form>
                <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                    EDIT PROFILE
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProfileForm">
                        <div class="mb-3">
                            <label for="username" class="form-label">Name</label>
                            <input type="text" class="form-control" id="username" name="username"
                                value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Other content of your page -->

    <!-- jQuery (Ensure jQuery is included) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS (For Modal Functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    $(document).ready(function() {
        // Edit Profile AJAX
        $("#editProfileForm").on("submit", function(e) {
            e.preventDefault(); // Prevent default form submission

            var formData = $(this).serialize(); // Serialize form data

            $.ajax({
                url: "update_profile.php", // PHP file to process update
                type: "POST",
                data: formData,
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        alert("Profile updated successfully!");
                        location.reload(); // Refresh the page to show updated data
                    } else {
                        alert("Error: " + response.message);
                    }
                },
                error: function() {
                    alert("An error occurred while updating the profile.");
                }
            });
        });

        // Upload Profile Image AJAX
        $('#uploadForm').on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: 'upload_profile_image.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Immediately update the profile image on the page
                        $('#profileImage').attr('src', response.imagePath + "?t=" +
                            new Date().getTime());
                        alert('Profile image updated successfully!');
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('An error occurred while uploading the image.');
                }
            });
        });

    });
    </script>

</body>

</html>

</body>

</html>