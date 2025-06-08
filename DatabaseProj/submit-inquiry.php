<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    require 'config.php';

 
    $fullname = $_POST['fullname'] ?? ''; 
    $email    = $_POST['email'] ?? '';    
    $contact  = $_POST['contact'] ?? '';   
    $message  = $_POST['message'] ?? '';   

    try {
        //insert inquiry into database
      
        $stmt = $pdo->prepare("INSERT INTO inquiries (fullname, email, contact, message) VALUES (?, ?, ?, ?)");
        
       
        $stmt->execute([
            $fullname, 
            $email,   
            $contact,  
            $message    
        ]);

        //redirect back to form
        echo "<script>
                alert('Inquiry submitted successfully! We will get back to you soon.');
                window.location.href = 'inquiry-form.php';
              </script>";

    } catch (PDOException $e) {
        //error message lang
        echo "<script>
                alert('Error saving inquiry: " . addslashes($e->getMessage()) . "');
                window.location.href = 'inquiry-form.php';
              </script>";
    }

} else {
 
    echo "<script>
            alert('Invalid request method. Please use the contact form.');
            window.location.href = 'inquiry-form.php';
          </script>";
}
?>