<?php

?>
<link rel="stylesheet" href="admin_header.css">

<div class="header">
  <h2><?php echo htmlspecialchars($pageTitle); ?></h2>
  <div class="admin-container" onclick="toggleDropdown(event)">
    <span class="admin-label">Admin ▾</span>
    <div class="dropdown" id="adminDropdown">
      <button onclick="logout()">
        <img src="Logout.svg" alt="Logout" />
        Logout
      </button>
    </div>
  </div>
</div>

<script>
  function toggleDropdown(event) {
    event.stopPropagation();
    const dropdown = document.getElementById('adminDropdown');
    dropdown.classList.toggle('show');
  }

  function logout() {
    if (confirm('Are you sure you want to logout?')) {
      window.location.href = 'admin_logout.php';
    }
  }

  window.addEventListener('click', function(event) {
    const dropdown = document.getElementById('adminDropdown');
    const container = document.querySelector('.admin-container');
    if (dropdown && container && !container.contains(event.target)) {
      dropdown.classList.remove('show');
    }
  });
</script>
