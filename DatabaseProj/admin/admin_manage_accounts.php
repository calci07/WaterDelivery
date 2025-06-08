<?php

session_start();

if (!isset($_SESSION['admin_username'])) {
    header('Location: ../index.php?login_required=true'); 
    exit(); 
}


$activePage = 'accounts';


require_once '../config.php';

try {
   
    $stmt = $pdo->prepare("SELECT id, email, mobile, created_at FROM users ORDER BY id ASC");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
   
    $users = [];
    error_log("Error fetching users: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin - Manage Accounts</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }
    body {
      margin: 0;
      background-color: #dffeff;
      color: #333;
      display: flex;
    }
    .main {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }
    
   
    .message {
      margin: 20px;
      padding: 10px;
      border-radius: 5px;
      font-weight: 500;
    }
    .message.success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    .message.error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
    
    table {
      width: calc(100% - 40px);
      border-collapse: collapse;
      margin: 0 20px;
    }
    th, td {
      padding: 12px 8px;
      border-bottom: 1px solid #ccc;
      text-align: left;
    }
    th {
      font-weight: bold;
      background-color: #f8f9fa;
      color: #333;
    }
    
    td {
      color: #ffffff;
    }
    
   
    tbody tr:nth-child(even) {
      background-color: #f8f9fa;
    }
    tbody tr:hover {
      transition: .3s ease-in-out;
      background-color: #333;
   
      
    }
    
    .delete-btn {
      background-color: #dc3545;
      color: white;
      border: none;
      padding: 8px 12px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 14px;
      transition: background-color 0.3s;
    }
    .delete-btn:hover {
      background-color: #c82333;
    }
    
    
    .user-summary {
      margin: 20px;
      padding: 15px;
      background-color: #fff;
      border-radius: 8px;
      border-left: 4px solid #007bff;
      color: #333;
    }
    .user-summary h3 {
      color: #333;
      margin-top: 0;
    }
    .user-summary p {
      color: #666;
    }
  </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="main">
  <?php
  $pageTitle = 'Manage Accounts';
  include 'admin_header.php';
  ?>
  
  
  <?php if (isset($_SESSION['message'])): ?>
    <div class="message success"><?= htmlspecialchars($_SESSION['message']) ?></div>
    <?php unset($_SESSION['message']); ?>
  <?php endif; ?>
  
  <?php if (isset($_SESSION['error'])): ?>
    <div class="message error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
  <?php endif; ?>
  

  <div class="user-summary">
    <h3>User Statistics</h3>
    <p><strong>Total Registered Users:</strong> <?= count($users) ?></p>
    <?php if (count($users) > 0): ?>
      <p><strong>Newest User:</strong> <?= htmlspecialchars($users[count($users)-1]['email']) ?> 
         (registered <?= date('M j, Y', strtotime($users[count($users)-1]['created_at'])) ?>)</p>
    <?php endif; ?>
  </div>
  
  <!--users table -->
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Email</th>
        <th>Mobile</th>
        <th>Registration Date</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php if (count($users) > 0): ?>
        <?php foreach ($users as $user): ?>
          <tr>
            <td>#<?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td>+63<?= htmlspecialchars($user['mobile']) ?></td>
            <td><?= date('M j, Y g:i A', strtotime($user['created_at'])) ?></td>
            <td>
           
              <form method="POST" action="delete_user.php" style="display: inline;" 
                    onsubmit="return confirm('Are you sure you want to delete this user account?\n\nUser: <?= htmlspecialchars($user['email']) ?>\n\nThis action cannot be undone.');">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                <button type="submit" class="delete-btn">✖ Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" style="text-align: center; padding: 40px; color: #666;">
            No user accounts found in the system.
          </td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

</body>
</html>