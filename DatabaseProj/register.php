<?php

require 'config.php';


$message = '';     
$messageClass = ''; 


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    //(pang remove extra whitespace)
    $mobile = trim($_POST['mobile'] ?? '');          
    $email = trim($_POST['email'] ?? '');             
    $password = $_POST['password'] ?? '';             
    $confirmPassword = $_POST['confirm_password'] ?? ''; 

    //pang validate if all required fields are filled
    if (empty($mobile) || empty($email) || empty($password) || empty($confirmPassword)) {
        $message = 'Please fill in all fields.';
        $messageClass = 'error';
        
    //pang validate if email format is correct
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Invalid email format.';
        $messageClass = 'error';
        
    //pang check if passwords match
    } elseif ($password !== $confirmPassword) {
        $message = 'Passwords do not match.';
        $messageClass = 'error';
        
    } else {
      

        try {
            //insert new user
          
            $stmt = $pdo->prepare("INSERT INTO users (mobile, email, password) VALUES (?, ?, ?)");
            
         
            $stmt->execute([$mobile, $email, $password]);

            //user has been registered
            $message = 'Account created successfully! You can now login.';
            $messageClass = 'success';

        } catch (PDOException $e) {
           
            
            //if yung email or mobile already exists
            if (strpos($e->getMessage(), 'duplicate') !== false || 
                strpos($e->getMessage(), 'UNIQUE') !== false ||
                strpos($e->getMessage(), 'UQ_') !== false ||
                $e->getCode() == 23000) {
                //- if yung user already exists
                $message = 'Email or mobile number already registered. Please use different details.';
                $messageClass = 'error';
            } else {
           
                $message = 'Registration failed. Please try again.';
                $messageClass = 'error';
                
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Register - Water Delivery</title>
  <link rel="stylesheet" href="css/entry.css" />
</head>
<body>
  <div class="logo">
    <img src="css/Logo.svg" alt="Logo" style="width: 40px; height: auto;" />
    <span>WATER DELIVERY</span>
  </div>

  <div class="container">
    <h2>Register</h2>

    <?php if ($message): ?>
     
      <div class="message <?= htmlspecialchars($messageClass) ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>


    <form method="post" action="">
      
      <!--philippines country code (mobile num to sha)-->
      <label for="mobile">Enter your mobile number</label>
      <div style="display: flex; gap: 10px; align-items: center;">
        <div class="country-code">+63</div>
        <!-- para di mawala num if may error (iwas retype) -->
        <input type="text" id="mobile" name="mobile" placeholder="912 345 6789" 
               value="<?= htmlspecialchars($_POST['mobile'] ?? '') ?>" />
      </div>

      <!--email-->
      <label for="email">Enter your Email</label>
      <!-- para di mawala email if may error -->
      <input type="email" id="email" name="email" placeholder="sample@gmail.com" 
             value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />

      <!--show/hide toggle -->
      <label for="password">Enter your password</label>
      <div class="password-container">
        <input type="password" id="password" name="password" placeholder="************" />
       <!-- password visibility (mata) -->
        <span class="toggle-password" onclick="togglePassword('password')">👁</span>
      </div>

     
      <label for="confirm-password">Re-enter your password</label>
      <div class="password-container">
        <input type="password" id="confirm-password" name="confirm_password" placeholder="************" />
        <!-- password visibility (mata) -->
        <span class="toggle-password" onclick="togglePassword('confirm-password')">👁</span>
      </div>

    
      <button type="submit" class="btn">Create Account</button>
    </form>

    <!-- for existing users (Link to login pag) -->
    <div class="footer">
      Already have an account? <a href="login.php">Login here</a>
    </div>
  </div>

  <script>
    //js function para sa password visibility
    function togglePassword(id) {
      const input = document.getElementById(id);
      
      input.type = input.type === "password" ? "text" : "password";
    }
  </script>
  <?php include 'footer.php'; ?>
</body>
</html>