<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_username'])) {
    header('Location: ../index.php?login_required=true');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $user_id = intval($_POST['id']);

    if ($user_id <= 0) {
        $_SESSION['error'] = "Invalid user ID.";
        header("Location: admin_manage_accounts.php");
        exit();
    }

    try {
      
        $pdo->beginTransaction();

       
        $user_stmt = $pdo->prepare("SELECT email FROM users WHERE id = ?");
        $user_stmt->execute([$user_id]);
        $user = $user_stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $_SESSION['error'] = "User not found.";
            $pdo->rollBack();
            header("Location: admin_manage_accounts.php");
            exit();
        }

        $user_email = $user['email'];

   
        $order_count_stmt = $pdo->prepare("SELECT COUNT(*) as order_count FROM orders WHERE contact_email = ? OR contact_name IN (SELECT email FROM users WHERE id = ?)");
        $order_count_stmt->execute([$user_email, $user_id]);
        $order_count = $order_count_stmt->fetch(PDO::FETCH_ASSOC)['order_count'];

      
        $delete_orders_stmt = $pdo->prepare("DELETE FROM orders WHERE contact_email = ?");
        $delete_orders_stmt->execute([$user_email]);
        $deleted_orders = $delete_orders_stmt->rowCount();

     
        $delete_inquiries_stmt = $pdo->prepare("DELETE FROM inquiries WHERE email = ?");
        $delete_inquiries_stmt->execute([$user_email]);
        $deleted_inquiries = $delete_inquiries_stmt->rowCount();

      
        $delete_user_stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $delete_user_stmt->execute([$user_id]);
        $deleted_user = $delete_user_stmt->rowCount();

        if ($deleted_user > 0) {
            
            $pdo->commit();
            
        
            $_SESSION['message'] = "User account deleted successfully.";
            
            if ($deleted_orders > 0) {
                $_SESSION['message'] .= " Also deleted {$deleted_orders} order(s)";
            }
            
            if ($deleted_inquiries > 0) {
                $_SESSION['message'] .= " and {$deleted_inquiries} inquiry(s)";
            }
            
            $_SESSION['message'] .= " associated with this user.";
            
        } else {
            $pdo->rollBack();
            $_SESSION['error'] = "Failed to delete user account.";
        }

    } catch (PDOException $e) {
        //rollback transaction pag nag error
        $pdo->rollBack();
        $_SESSION['error'] = "Failed to delete user: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "Invalid request.";
}

header("Location: admin_manage_accounts.php");
exit();
?>