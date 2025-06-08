<?php

//edit inquiry


session_start();
require_once '../config.php';


if (!isset($_SESSION['admin_username'])) {
    header('Location: DatabaseProj/index.php?login_required=true');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $id = intval($_POST['id'] ?? 0);
    $fullname = trim($_POST['fullname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $message = trim($_POST['message'] ?? '');

   
    if ($id <= 0 || empty($fullname) || empty($email) || empty($contact) || empty($message)) {
        echo "<script>alert('Error: Please fill all fields.'); window.location.href='admin_inquiries.php';</script>";
        exit();
    }

    //pang validate ng email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Error: Invalid email format.'); window.location.href='admin_inquiries.php';</script>";
        exit();
    }

    try {
       
        $stmt = $pdo->prepare("UPDATE inquiries SET fullname = ?, email = ?, contact = ?, message = ? WHERE id = ?");
        $stmt->execute([$fullname, $email, $contact, $message, $id]);

  
        if ($stmt->rowCount() > 0) {
        
            echo "<script>alert('Inquiry updated successfully.'); window.location.href='admin_inquiries.php';</script>";
        } else {
          
            echo "<script>alert('No changes made or inquiry not found.'); window.location.href='admin_inquiries.php';</script>";
        }

    } catch (PDOException $e) {
      
        echo "<script>alert('Error updating inquiry: " . addslashes($e->getMessage()) . "'); window.location.href='admin_inquiries.php';</script>";
    }
} else {
 
    header("Location: admin_inquiries.php");
    exit();
}
?>

<?php

//delete inquiry


session_start();
require_once '../config.php';


if (!isset($_SESSION['admin_username'])) {
    header('Location: ../index.php?login_required=true');
    exit();
}


$id = intval($_GET['id'] ?? 0);


if ($id <= 0) {
    echo "<script>alert('Invalid inquiry ID.'); window.location.href='admin_inquiries.php';</script>";
    exit();
}

try {
   
    $stmt = $pdo->prepare("DELETE FROM inquiries WHERE id = ?");
    $stmt->execute([$id]);

   
    if ($stmt->rowCount() > 0) {
       
        echo "<script>alert('Inquiry deleted successfully.'); window.location.href='admin_inquiries.php';</script>";
    } else {
       
        echo "<script>alert('Inquiry not found.'); window.location.href='admin_inquiries.php';</script>";
    }

} catch (PDOException $e) {
   
    echo "<script>alert('Error deleting inquiry: " . addslashes($e->getMessage()) . "'); window.location.href='admin_inquiries.php';</script>";
}
?>