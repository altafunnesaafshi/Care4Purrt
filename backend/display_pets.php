<?php
session_start();

// Only pet owners should access this page (matches API access rules)
if (!isset($_SESSION['username']) || ($_SESSION['role'] ?? '') !== 'owner') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Pets - Care4Purrt</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">My Pets</h2>
    <a class="btn btn-secondary" href="pet_owner_dashboard.php">Back</a>
  </div>

  <div id="petsContainer"></div>
</div>

<script>
async function loadPets() {
  const box = document.getElementById('petsContainer');
  box.innerHTML = '<div class="alert alert-info">Loading pets...</div>';

  try {
    const res = await fetch('api/pets.php');
    const data = await res.json();

    if (!data.ok) {
      box.innerHTML = `<div class="alert alert-danger">${data.error || 'Failed to load pets'}</div>`;
      return;
    }

    const pets = data.pets || [];
    if (pets.length === 0) {
      box.innerHTML = '<div class="alert alert-warning">No pets found.</div>';
      return;
    }

    box.innerHTML = pets.map(p => `
      <div class="card mb-2">
        <div class="card-body">
          <h5 class="card-title mb-2">${p.name || ''}</h5>
          <div class="row">
            <div class="col-md-4"><b>Age:</b> ${p.age ?? p.pet_age ?? ''}</div>
            <div class="col-md-8"><b>Owner:</b> ${p.owner_name ?? p.owner_username ?? ''}</div>
          </div>
        </div>
      </div>
    `).join('');

  } catch (err) {
    box.innerHTML = '<div class="alert alert-danger">Network error. Please try again.</div>';
  }
}
loadPets();
</script>
</body>
</html>
