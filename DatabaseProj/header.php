<?php
//call yung db
require_once 'config.php';

//(to check if user is logged in)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header>
  <div class="header-left">
  
    <div class="logo">
      <img src="css/Logo.svg" alt="Logo" />
      <span style="margin-left: 8px;">WATER DELIVERY</span>
    </div>
    
    <!--main navigation menu -->
    <nav>
      <a href="index.php">Home</a>
      <a href="order1.php">Services</a>
      <a href="inquiry-form.php">Contact Us</a>
    </nav>
  </div>

  <?php if (isset($_SESSION['email'])): ?>
    <!-- show profile dropdown -->
    <div class="Profile">
      <div class="dropdown">
        <button class="dropbtn">Welcome, <b><?= htmlspecialchars($_SESSION['email']) ?></b> ▼</button>
        <div class="dropdown-content">
          <!--user menu options -->
          <a href="#"><img class="dropdown-icon" src="css/HistoryIcon.svg" alt="History">History</a>
          <a href="#" onclick="document.getElementById('adminLoginModal').style.display='block'">
            <img class="dropdown-icon" src="css/AdminIcon.svg" alt="Admin">Login as Admin
          </a>
          <hr>
          <!-- Logout  confirmation -->
          <a href="logout.php" onclick="return confirm('Are you sure you want to log out?')">
            <img class="dropdown-icon" src="css/LogoutIcon.svg" alt="Logout">Logout</a>
        </div>
      </div>
    </div>
  <?php else: ?>
    <!-- User is not logged in - show login/register buttons -->
    <div class="buttons">
      <button class="btn login-btn" onclick="window.location.href='login.php'">Log in ↗</button>
      <button class="btn get-started-btn" onclick="window.location.href='register.php'">Get started ↗</button>
    </div>
  <?php endif; ?>
</header>

<!--for admin login -->
<div id="adminLoginModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="document.getElementById('adminLoginModal').style.display='none'">&times;</span>
    <h2>Admin Login</h2>
    
    <
    <form method="POST" action="admin-login.php">
      <label>Username</label>
      <input type="text" name="username" required>
      
      <label>Password</label>
      <input type="password" name="password" required>
      
      <button type="submit" class="btn login-submit">Login</button>
    </form>
  </div>
</div>


<style>
  .modal {
    display: none;                   
    position: fixed;                 
    z-index: 1000;                  
    left: 0; top: 0;               
    width: 100%; height: 100%;       
    overflow: auto;                  
    background-color: rgba(0,0,0,0.5); 
  }
  
  .modal-content {
    background-color: #fff;
    margin: 10% auto;             
    padding: 30px;
    border-radius: 10px;
    width: 350px;
    position: relative;
    animation: fadeIn 0.3s ease-in-out; /
  }
  
  .modal-content h2 {
    text-align: center;
  }
  
  .modal-content label {
    display: block;
    margin-top: 15px;
  }
  
  .modal-content input {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    box-sizing: border-box;
  }
  
  .login-submit {
    width: 100%;
    margin-top: 20px;
    background-color: #0077cc;
    color: white;
    border: none;
    padding: 10px;
    cursor: pointer;
  }
  
  .login-submit:hover {
    background-color: #005fa3;      
  }
  
  .close {
    position: absolute;
    right: 15px;
    top: 10px;
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
  }
  
  
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px);}
    to { opacity: 1; transform: translateY(0);}
  }
</style>

<script>
 
  window.onclick = function(event) {
    const modal = document.getElementById('adminLoginModal');
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }
</script>