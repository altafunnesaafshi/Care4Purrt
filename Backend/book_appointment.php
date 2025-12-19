<?php
session_start();
include('db.php');
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'owner') {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
$sql_doctors = "SELECT * FROM doctor_profiles";
$result_doctors = mysqli_query($conn, $sql_doctors);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_username = mysqli_real_escape_string($conn, $_POST['doctor']);
    $pet_type = mysqli_real_escape_string($conn, $_POST['pet_type']);
    $pet_age = mysqli_real_escape_string($conn, $_POST['pet_age']);
    $pet_problem = mysqli_real_escape_string($conn, $_POST['pet_problem']);
    $appointment_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
    $appointment_time = mysqli_real_escape_string($conn, $_POST['appointment_time']);

    $sql_appointment = "INSERT INTO appointments (pet_owner_username, doctor_username, pet_type, pet_age, pet_problem, appointment_date, appointment_time) 
                        VALUES ('$username', '$doctor_username', '$pet_type', '$pet_age', '$pet_problem', '$appointment_date', '$appointment_time')";

    if (mysqli_query($conn, $sql_appointment)) {
        $appointment_message = "Your appointment has been successfully booked."; 
    } else {
        $appointment_message = "Error booking appointment: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - Care4Purrt</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7f6;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            width: 100%;
            padding: 10px;
            font-size: 1.2rem;
            border-radius: 25px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Book Appointment</h2>

    <!-- Display appointment message -->
    <?php if (isset($appointment_message)) { echo "<div class='alert alert-info'>$appointment_message</div>"; } ?>

    <form method="POST" action="book_appointment.php">
        <!-- Select Doctor -->
        <div class="form-group">
            <label for="doctor">Select Doctor:</label>
            <select name="doctor" id="doctor" class="form-control" required>
                <?php while ($doctor = mysqli_fetch_assoc($result_doctors)) { ?>
                    <option value="<?php echo $doctor['username']; ?>"><?php echo $doctor['name']; ?> - <?php echo $doctor['specialization']; ?></option>
                <?php } ?>
            </select>
        </div>

        <!-- Pet Type -->
        <div class="form-group">
            <label for="pet_type">Pet Type:</label>
            <input type="text" name="pet_type" id="pet_type" class="form-control" required>
        </div>

        <!-- Pet Age -->
        <div class="form-group">
            <label for="pet_age">Pet Age (in years):</label>
            <input type="number" name="pet_age" id="pet_age" class="form-control" required>
        </div>

        <!-- Pet Problem -->
        <div class="form-group">
            <label for="pet_problem">Pet Problem:</label>
            <textarea name="pet_problem" id="pet_problem" class="form-control" rows="4" required></textarea>
        </div>

        <!-- Appointment Date -->
        <div class="form-group">
            <label for="appointment_date">Appointment Date:</label>
            <input type="date" name="appointment_date" id="appointment_date" class="form-control" required>
        </div>

        <!-- Appointment Time -->
        <div class="form-group">
            <label for="appointment_time">Appointment Time:</label>
            <input type="time" name="appointment_time" id="appointment_time" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary btn-custom">Book Appointment</button>
    </form>
</div>
</body>
</html>
