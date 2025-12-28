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
<!-- message will appear here after API response -->
<div id="msgBox"></div>

        <form id="appointmentForm">

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
<script>
const form = document.getElementById('appointmentForm');
const msgBox = document.getElementById('msgBox');

form.addEventListener('submit', async (e) => {
  e.preventDefault();
  msgBox.innerHTML = ''; // clear previous message

  const fd = new FormData(form);

  try {
    const res = await fetch('api/appointments.php', {
      method: 'POST',
      body: fd
    });

    const data = await res.json();

    if (!data.ok) {
      msgBox.innerHTML = `<div class="alert alert-danger">${data.error || 'Failed to book appointment'}</div>`;
      return;
    }

    msgBox.innerHTML = `<div class="alert alert-success">${data.message || 'Appointment booked successfully'}</div>`;
    form.reset();

  } catch (err) {
    msgBox.innerHTML = `<div class="alert alert-danger">Network error. Please try again.</div>`;
  }
});
</script>
</body>
</html>
