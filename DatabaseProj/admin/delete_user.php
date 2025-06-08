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
        // Start database transaction for safe deletion
        $pdo->beginTransaction();

        // First, get user email and count orders
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

        // Count user's orders before deletion (orders.email matches users.email)
        $order_count_stmt = $pdo->prepare("SELECT COUNT(*) as order_count FROM orders WHERE email = ?");
        $order_count_stmt->execute([$user_email]);
        $order_count = $order_count_stmt->fetch(PDO::FETCH_ASSOC)['order_count'];

        // Count user's inquiries before deletion (inquiries.email matches users.email) 
        $inquiry_count_stmt = $pdo->prepare("SELECT COUNT(*) as inquiry_count FROM inquiries WHERE email = ?");
        $inquiry_count_stmt->execute([$user_email]);
        $inquiry_count = $inquiry_count_stmt->fetch(PDO::FETCH_ASSOC)['inquiry_count'];

        // Delete user's orders first (orders.email = users.email)
        $delete_orders_stmt = $pdo->prepare("DELETE FROM orders WHERE email = ?");
        $delete_orders_stmt->execute([$user_email]);
        $deleted_orders = $delete_orders_stmt->rowCount();

        // Delete user's inquiries (inquiries.email = users.email)
        $delete_inquiries_stmt = $pdo->prepare("DELETE FROM inquiries WHERE email = ?");
        $delete_inquiries_stmt->execute([$user_email]);
        $deleted_inquiries = $delete_inquiries_stmt->rowCount();

        // Finally delete the user account
        $delete_user_stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $delete_user_stmt->execute([$user_id]);
        $deleted_user = $delete_user_stmt->rowCount();

        if ($deleted_user > 0) {
            // Commit the transaction
            $pdo->commit();
            
            // Success message with details
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