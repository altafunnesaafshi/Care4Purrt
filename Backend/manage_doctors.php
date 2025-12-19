<?php
session_start();
include('db.php');
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); 
    exit();
}
$username = $_SESSION['username'];
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_doctor'])) {
    $doctor_name = mysqli_real_escape_string($conn, $_POST['doctor_name']);
    $doctor_email = mysqli_real_escape_string($conn, $_POST['doctor_email']);
    $doctor_phone = mysqli_real_escape_string($conn, $_POST['doctor_phone']);
    $doctor_specialty = mysqli_real_escape_string($conn, $_POST['doctor_specialty']);
    $sql = "INSERT INTO doctors (doctor_name, doctor_email, doctor_phone, doctor_specialty) 
            VALUES ('$doctor_name', '$doctor_email', '$doctor_phone', '$doctor_specialty')";
    
    if (mysqli_query($conn, $sql)) {
        echo "New doctor added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
if (isset($_GET['delete'])) {
    $doctor_id = $_GET['delete'];

    $sql_delete = "DELETE FROM doctors WHERE id = $doctor_id";
    
    if (mysqli_query($conn, $sql_delete)) {
        echo "Doctor deleted successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
$sql = "SELECT * FROM doctors";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctors - Care4Purrt</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Manage Doctors</h2>
        
        <!-- Form to Add New Doctor -->
        <form method="POST" class="mb-5">
            <h4>Add New Doctor</h4>
            <div class="form-group">
                <label for="doctor_name">Name:</label>
                <input type="text" name="doctor_name" id="doctor_name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="doctor_email">Email:</label>
                <input type="email" name="doctor_email" id="doctor_email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="doctor_phone">Phone:</label>
                <input type="text" name="doctor_phone" id="doctor_phone" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="doctor_specialty">Specialty:</label>
                <input type="text" name="doctor_specialty" id="doctor_specialty" class="form-control" required>
            </div>

            <button type="submit" name="add_doctor" class="btn btn-primary">Add Doctor</button>
        </form>

        <!-- Doctor List -->
        <h4>All Doctors</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Doctor Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Specialty</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['doctor_name']; ?></td>
                        <td><?php echo $row['doctor_email']; ?></td>
                        <td><?php echo $row['doctor_phone']; ?></td>
                        <td><?php echo $row['doctor_specialty']; ?></td>
                        <td>
                            <a href="edit_doctor.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="manage_doctors.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>

