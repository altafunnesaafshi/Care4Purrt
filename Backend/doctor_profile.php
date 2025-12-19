<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'doctor') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
include('db.php');

$sql = "SELECT * FROM doctor_profiles WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$profile = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_profile'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $contact_number = mysqli_real_escape_string($conn, $_POST['contact_number']);
    $medical_designation = mysqli_real_escape_string($conn, $_POST['medical_designation']);
    $specialization = mysqli_real_escape_string($conn, $_POST['specialization']);
    $medical_license_number = mysqli_real_escape_string($conn, $_POST['medical_license_number']);
    $years_of_experience = (int)$_POST['years_of_experience'];
    $medical_degree = mysqli_real_escape_string($conn, $_POST['medical_degree']);
    $institution_name = mysqli_real_escape_string($conn, $_POST['institution_name']);

    $profile_picture = $profile['profile_picture'] ?? '';
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . uniqid() . "-" . basename($_FILES["profile_picture"]["name"]);
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            $profile_picture = $target_file;
        }
    }

    $upload_field = $profile['upload_field'] ?? '';
    if (isset($_FILES['upload_field']) && $_FILES['upload_field']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . uniqid() . "-" . basename($_FILES["upload_field"]["name"]);
        if (move_uploaded_file($_FILES["upload_field"]["tmp_name"], $target_file)) {
            $upload_field = $target_file;
        }
    }

    if ($profile) {
        $sql_update = "UPDATE doctor_profiles SET 
                        name=?, contact_number=?, medical_designation=?, specialization=?, 
                        medical_license_number=?, years_of_experience=?, medical_degree=?, 
                        institution_name=?, profile_picture=?, upload_field=? 
                        WHERE username=?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param(
            "ssssssissss",
            $name,
            $contact_number,
            $medical_designation,
            $specialization,
            $medical_license_number,
            $years_of_experience,
            $medical_degree,
            $institution_name,
            $profile_picture,
            $upload_field,
            $username
        );
        $stmt_update->execute();
        $msg = "Profile updated successfully!";
    } else {
        $sql_insert = "INSERT INTO doctor_profiles 
                       (username, name, contact_number, medical_designation, specialization, 
                        medical_license_number, years_of_experience, medical_degree, institution_name, profile_picture, upload_field) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param(
            "ssssssissss",
            $username,
            $name,
            $contact_number,
            $medical_designation,
            $specialization,
            $medical_license_number,
            $years_of_experience,
            $medical_degree,
            $institution_name,
            $profile_picture,
            $upload_field
        );
        $stmt_insert->execute();
        $msg = "Profile created successfully!";
    }

    $stmt = $conn->prepare("SELECT * FROM doctor_profiles WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $profile = $result->fetch_assoc();

    $showForm = false;
}

if (isset($_POST['edit_profile'])) {
    $showForm = true;
} elseif (!isset($showForm)) {
    $showForm = $profile ? false : true; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Doctor Profile</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    background: #dbeafe;
    font-family: 'Poppins', sans-serif;
}

/* Profile Card */
.profile-card {
    max-width: 750px;
    margin: 70px auto;
    background: #fff;
    padding: 70px 60px;
    border-radius: 30px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.18);
    text-align: center;
}
.profile-card img {
    width: 280px;
    height: 280px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 30px;
    border: 6px solid #7F00FF;
}
.profile-card h4 {
    font-size: 3rem;
    margin-bottom: 25px;
}
.profile-card p {
    font-size: 1.6rem;
    margin-bottom: 16px;
}
.profile-card p strong {
    width: 200px;
    display: inline-block;
}

/* Buttons */
.btn-modern {
    border-radius: 60px;
    padding: 20px 50px;
    font-weight: 800;
    font-size: 1.8rem;
    margin: 12px;
    transition: all 0.3s ease;
}
.btn-primary-modern {
    background: linear-gradient(90deg, #7F00FF, #E100FF);
    color: #fff;
    border: none;
}
.btn-primary-modern:hover {
    transform: scale(1.08);
    opacity: 0.9;
}
.btn-secondary-modern {
    background: linear-gradient(90deg, #6c757d, #343a40);
    color: #fff;
    border: none;
}
.btn-secondary-modern:hover {
    transform: scale(1.08);
    opacity: 0.9;
}

/* Form Styling */
form .form-label {
    font-weight: 700;
    font-size: 1.6rem;
}
form .form-control {
    font-size: 1.5rem;
    padding: 18px 20px;
    border-radius: 15px;
}
form .row.g-4 {
    gap: 2rem;
}
h2.display-5 {
    font-size: 4rem;
}
.alert {
    font-size: 1.8rem;
    padding: 20px;
}
</style>
</head>
<body>
<div class="container">

<?php if ($showForm): ?>
    <h2 class="text-center mt-5 mb-5 display-5"><?php echo $profile ? 'Edit Profile' : 'Create Profile'; ?></h2>

    <?php if (isset($msg)): ?>
        <div class="alert alert-success text-center"><?php echo $msg; ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="row g-4 justify-content-center fs-5">
        <input type="hidden" name="submit_profile" value="1">

        <div class="col-md-6">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo $profile['name'] ?? ''; ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Contact Number</label>
            <input type="text" name="contact_number" class="form-control" value="<?php echo $profile['contact_number'] ?? ''; ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Medical Designation</label>
            <input type="text" name="medical_designation" class="form-control" value="<?php echo $profile['medical_designation'] ?? ''; ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Specialization</label>
            <input type="text" name="specialization" class="form-control" value="<?php echo $profile['specialization'] ?? ''; ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Medical License Number</label>
            <input type="text" name="medical_license_number" class="form-control" value="<?php echo $profile['medical_license_number'] ?? ''; ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Years of Experience</label>
            <input type="number" name="years_of_experience" class="form-control" value="<?php echo $profile['years_of_experience'] ?? ''; ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Medical Degree</label>
            <input type="text" name="medical_degree" class="form-control" value="<?php echo $profile['medical_degree'] ?? ''; ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Institution Name</label>
            <input type="text" name="institution_name" class="form-control" value="<?php echo $profile['institution_name'] ?? ''; ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Profile Picture</label>
            <input type="file" name="profile_picture" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Upload Field (Optional)</label>
            <input type="file" name="upload_field" class="form-control">
        </div>

        <div class="col-12 text-center mt-4">
            <button type="submit" class="btn btn-primary-modern btn-modern"><?php echo $profile ? 'Update Profile' : 'Create Profile'; ?></button>
        </div>
    </form>
<?php endif; ?>

<?php if ($profile && !$showForm): ?>
    <div class="profile-card">
        <?php if ($profile['profile_picture']): ?>
            <img src="<?php echo htmlspecialchars($profile['profile_picture']); ?>" alt="Profile Picture">
        <?php else: ?>
            <img src="assets/default_doctor.png" alt="Default Profile Picture">
        <?php endif; ?>

        <h4><?php echo htmlspecialchars($profile['name']); ?></h4>
        <p><strong>Contact:</strong> <?php echo htmlspecialchars($profile['contact_number']); ?></p>
        <p><strong>Designation:</strong> <?php echo htmlspecialchars($profile['medical_designation']); ?></p>
        <p><strong>Specialization:</strong> <?php echo htmlspecialchars($profile['specialization']); ?></p>
        <p><strong>License No:</strong> <?php echo htmlspecialchars($profile['medical_license_number']); ?></p>
        <p><strong>Experience:</strong> <?php echo htmlspecialchars($profile['years_of_experience']); ?> years</p>
        <p><strong>Degree:</strong> <?php echo htmlspecialchars($profile['medical_degree']); ?></p>
        <p><strong>Institution:</strong> <?php echo htmlspecialchars($profile['institution_name']); ?></p>
        <?php if ($profile['upload_field']): ?>
            <p><strong>Uploaded File:</strong> <a href="<?php echo htmlspecialchars($profile['upload_field']); ?>" target="_blank">View</a></p>
        <?php endif; ?>

        <div class="mt-5">
            <form method="POST" style="display:inline;">
                <button type="submit" name="edit_profile" class="btn btn-primary-modern btn-modern">Edit Profile</button>
            </form>
            <a href="doctor_dashboard.php" class="btn btn-secondary-modern btn-modern">Back to Dashboard</a>
        </div>
    </div>
<?php endif; ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
