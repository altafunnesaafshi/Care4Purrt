<?php
session_start();

// Check if the user is logged in and is a pet owner
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
<title>PurrPort: Your Pet Passport</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f0f2f5;
        padding: 40px 20px;
    }
    h1 {
        text-align: center;
        color: #4b4b8f;
        margin-bottom: 50px;
    }
    .passport-card {
        background: linear-gradient(to bottom right, #fff, #e0f7fa);
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 40px;
        box-shadow: 0 8px 18px rgba(0,0,0,0.2);
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
        position: relative;
    }
    .passport-header h2 {
        margin: 0;
        font-weight: bold;
        color: #2c3e50;
    }
    .passport-header p {
        font-style: italic;
        color: #555;
        margin: 5px 0 20px 0;
    }
    .passport-picture img {
        width: 250px;
        height: 250px;
        object-fit: cover;
        border-radius: 15px;
        border: 3px solid #2c3e50;
        display: block;
        margin: 0 auto 20px auto;
    }
    .passport-details p {
        font-size: 18px;
        line-height: 1.6;
        color: #2c3e50;
    }
    .passport-details strong {
        color: #34495e;
    }
    .btn-pdf {
        background: #6c63ff;
        color: #fff;
        padding: 12px 25px;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        cursor: pointer;
        transition: 0.3s;
        display: block;
        margin: 20px auto 0 auto;
    }
    .btn-pdf:hover {
        background: #574fcf;
        transform: scale(1.05);
    }
    .text-muted-center {
        text-align: center;
        color: #6c757d;
    }
</style>
</head>
<body>

<h1>🐾 PurrPort: Your Pet Passport 🐾</h1>

<div class="container">
<div id="passportBox"></div>

</div>

<script>
function escapeHtml(value) {
  return String(value ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

async function loadPassports() {
  const box = document.getElementById('passportBox');
  box.innerHTML = '<p class="text-muted-center">Loading passport data...</p>';

  try {
    const res = await fetch('api/passports.php');
    const data = await res.json();

    if (!data.ok) {
      box.innerHTML = `<p class="text-muted-center">${escapeHtml(data.error || 'Failed to load travel readiness')}</p>`;
      return;
    }

    const rows = data.data || [];
    if (rows.length === 0) {
      box.innerHTML = `
        <p class="text-muted-center">
          No passport found for your pets yet. Please check back later.
          For assistance,
          <a href="https://wa.me/8801554120378" target="_blank">contact the admin via WhatsApp</a>.
        </p>`;
      return;
    }

    box.innerHTML = rows.map(r => {
      const petPic = r.pet_picture ? escapeHtml(r.pet_picture) : 'placeholder_pet.png';
      const id = escapeHtml(r.id);
      const petName = escapeHtml(r.pet_name || r.name);
      const age = escapeHtml(r.pet_age ?? '');
      const passNo = escapeHtml(r.passport_number ?? '');
      const vStatus = escapeHtml(r.vaccination_status ?? '');
      const vDate = escapeHtml(r.vaccination_date ?? '');
      return `
      <div class="passport-card" id="passport-${id}">
        <div class="passport-header text-center">
          <h2>Pet Passport</h2>
          <p>Care4Purrt - Official Travel Document</p>
        </div>
        <div class="passport-picture">
          <img src="${petPic}" alt="Pet Picture">
        </div>
        <div class="passport-details">
          <p><strong>Pet Name:</strong> ${petName}</p>
          <p><strong>Age:</strong> ${age} years</p>
          <p><strong>Passport Number:</strong> ${passNo}</p>
          <p><strong>Vaccination Status:</strong> ${vStatus}</p>
          <p><strong>Vaccination Date:</strong> ${vDate}</p>
        </div>
        <button class="btn-download" onclick="downloadPassport(${id})">
          Download Passport PDF
        </button>
      </div>`;
    }).join('');
  } catch (err) {
    box.innerHTML = '<p class="text-muted-center">Network error. Please try again.</p>';
  }
}

loadPassports();
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
async function downloadPassport(id) {
    const { jsPDF } = window.jspdf;
    const element = document.getElementById("passport-" + id);
    const canvas = await html2canvas(element, { scale: 2 });
    const imgData = canvas.toDataURL("image/png");

    const pdf = new jsPDF("p", "pt", "a4");
    const pageWidth = pdf.internal.pageSize.getWidth();
    const imgWidth = pageWidth - 40;
    const imgHeight = canvas.height * imgWidth / canvas.width;

    pdf.addImage(imgData, "PNG", 20, 20, imgWidth, imgHeight);
    pdf.save("pet_passport_<?php echo $username; ?>_" + id + ".pdf");
}
</script>
</body>
</html>
