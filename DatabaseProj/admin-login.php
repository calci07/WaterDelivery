<?php

require_once 'config.php';


session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $username = trim($_POST['username']); 
    $password = $_POST['password'];       

    try {
        //query to find yung admin by username
     
        $stmt = $pdo->prepare("SELECT username, email, password FROM admins WHERE username = ?");
        
        //query with the submitted username
        $stmt->execute([$username]);
        
        //pang fetch ng admin record 
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        //pang check if admin exists and password matches
        if ($admin && $password === $admin['password']) {
            //create admin session variables (pag successful login)
            $_SESSION['admin_username'] = $admin['username']; //stores admin username
            $_SESSION['admin_email'] = $admin['email'];       //store sadmin email
            
            //redirect sa admin dashboard
            header("Location: admin/admin_stocks.php");
            exit();
            
        } else {
            //invalid user or pass
            echo "<script>
                    alert('Invalid username or password. Please try again.');
                    window.history.back();
                  </script>";
        }
        
    } catch (PDOException $e) {
   
        echo "<script>
                alert('Database error occurred. Please try again later.');
                window.history.back();
              </script>";
      
    }
}
?>