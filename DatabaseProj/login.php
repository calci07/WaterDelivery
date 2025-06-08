<?php

session_start();

//db
require_once 'config.php'; 


$message = '';
$messageClass = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $mobile = trim($_POST['mobile'] ?? ''); 
    $password = $_POST['password'] ?? '';   

    //validate na both fields are filled
    if (empty($mobile) || empty($password)) {
        $message = 'Please fill in both fields.';
        $messageClass = 'error';
    } else {
        try {
            //query to find user by mobile number
           
            $stmt = $pdo->prepare("SELECT id, email, password FROM users WHERE mobile = ?");
            
           
            $stmt->execute([$mobile]);
            
            //pang fetch ng user record 
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            //check if nag e eist user 
            if ($user) {
              
                if ($password === $user['password']) {
                   
                    $_SESSION['user_id'] = $user['id'];     //store user ID
                    $_SESSION['mobile'] = $mobile;          //store mobile number
                    $_SESSION['email'] = $user['email'];    //store email
                    
                 
                    header("Location: index.php");
                    exit; 
                } else {
                    //wrong password
                    $message = 'Incorrect password.';
                    $messageClass = 'error';
                }
            } else {
                //pang check if nag e exist mobile num
                $message = 'User not found.';
                $messageClass = 'error';
            }
            
        } catch (PDOException $e) {
            
            $message = 'Database error occurred. Please try again.';
            $messageClass = 'error';
          
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - Water Delivery</title>
  <link rel="stylesheet" href="css/entry.css">
</head>
<body>
  <div class="logo">
    <img src="css/Logo.svg" alt="Logo" style="width: 40px;">
    <span>WATER DELIVERY</span>
  </div>

  <div class="login-container">
    <h2>Login</h2>

    <?php if ($message): ?>
   
      <div class="message <?= htmlspecialchars($messageClass) ?>">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <!-- Login form -->
    <form method="post">
      <label>Mobile Number (+63)</label>
      <!-- para di mawala mobile num if may error -->
      <input type="text" name="mobile" required value="<?= htmlspecialchars($_POST['mobile'] ?? '') ?>">

      <label>Password</label>
      <input type="password" name="password" required>

      <button type="submit" class="btn">Login</button>
    </form>

    <div class="footer">
      Don't have an account? <a href="register.php">Sign Up</a>
    </div>
  </div>
  
  <?php include 'footer.php'; ?>
</body>
</html>