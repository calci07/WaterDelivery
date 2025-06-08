<?php

session_start();
$activePage = 'inquiries';

//mag re redirect to login if admin is not authenticated
if (!isset($_SESSION['admin_username'])) {
    header('Location: ../index.php?login_required=true');
    exit();
}


require_once '../config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin - Inquiries</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; font-family: 'Inter', sans-serif; }
    body { margin: 0; background-color: #dffeff; color: #333; display: flex; }

    .main { flex-grow: 1; display: flex; flex-direction: column; }

    table {
      width: calc(100% - 40px); margin: 20px; border-collapse: collapse;
    }
    th, td {
      padding: 12px 8px; text-align: left; border-bottom: 1px solid #ccc;
    }
    th { font-weight: bold; }

    .actions button {
      margin-right: 8px; padding: 5px 10px; border: none;
      color: white; border-radius: 4px; cursor: pointer;
    }
    .actions .edit { background-color: #0dbf92; }
    .actions .delete { background-color: #ff4d4d; }

    #overlay, .popup { display: none; }
    #overlay {
      position: fixed; top: 0; left: 0; width: 100%; height: 100%;
      background-color: rgba(0, 0, 0, 0.5); z-index: 999;
    }
    .popup {
      position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%);
      background: #181414; padding: 20px; border-radius: 10px;
      z-index: 1000; width: 300px;
    }
    .popup h2 { margin-top: 0; color: white; }
    .popup p { margin: 10px 0 4px; font-weight: 500; color: white; }
    .popup input, .popup textarea {
      width: 100%; padding: 8px; margin-bottom: 12px;
      border: 1px solid #ccc; border-radius: 4px;
    }
    .popup button {
      padding: 8px 12px; margin-right: 10px; border: none;
      border-radius: 4px; cursor: pointer;
    }
    .popup button[type="submit"] { background-color: #0dbf92; color: white; }
    .popup button[type="button"] { background-color: #ccc; }
  </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">
    <?php
    $pageTitle = 'Inquiries';
    include 'admin_header.php';
    ?>

  <table>
    <thead>
      <tr>
        <th>Full Name</th>
        <th>Email</th>
        <th>Contact</th>
        <th>Message</th>
        <th>Submitted At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      try {
          //pang fetch ng inquiries from database using 
   
          $stmt = $pdo->prepare("SELECT * FROM inquiries ORDER BY submitted_at DESC");
          $stmt->execute();
          
        
          $inquiries = $stmt->fetchAll(PDO::FETCH_ASSOC);
          
          if (count($inquiries) > 0) {
           
              foreach ($inquiries as $row) {
                
                  $id = htmlspecialchars($row['id']);
                  $fullname = htmlspecialchars($row['fullname']);
                  $email = htmlspecialchars($row['email']);
                  $contact = htmlspecialchars($row['contact']);
                  $message = htmlspecialchars($row['message']);
                  $submitted_at = htmlspecialchars($row['submitted_at']);
                  
                  //para ma display each inquiry as a table row
                  echo "<tr>
                      <td>{$fullname}</td>
                      <td>{$email}</td>
                      <td>{$contact}</td>
                      <td>" . substr($message, 0, 50) . "...</td>
                      <td>{$submitted_at}</td>
                      <td class='actions'>
                          <button class='edit' onclick=\"openEditModal({$id}, '{$fullname}', '{$email}', '{$contact}', '" . addslashes($message) . "')\">Edit</button>
                          <button class='delete' onclick=\"deleteInquiry({$id})\">Delete</button>
                      </td>
                  </tr>";
              }
          } else {
             
              echo "<tr><td colspan='6'>No inquiries found.</td></tr>";
          }
          
      } catch (PDOException $e) {
          //pang handle ng db errr
          echo "<tr><td colspan='6'>Error loading inquiries: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>


<div id="overlay"></div>


<div id="editModal" class="popup">
  <form action="inquiry_edit.php" method="POST">
    <h2>Edit Inquiry</h2>
    
    <!-- pang store ng inquiry ID -->
    <input type="hidden" name="id" id="edit_id">
    
    <p>Full Name</p>
    <input name="fullname" id="edit_fullname" required>
    
    <p>Email</p>
    <input name="email" id="edit_email" type="email" required>
    
    <p>Contact</p>
    <input name="contact" id="edit_contact" required>
    
    <p>Message</p>
    <textarea name="message" id="edit_message" rows="3" required></textarea>
    
    <button type="submit">Update</button>
    <button type="button" onclick="closePopup()">Cancel</button>
  </form>
</div>

<script>
 
  function openEditModal(id, fullname, email, contact, message) {
    //para ma fill yung form fields with existing data
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_fullname').value = fullname;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_contact').value = contact;
    document.getElementById('edit_message').value = message;
    
    //modal
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('editModal').style.display = 'block';
  }

  //function para ma close modal popup
  function closePopup() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('editModal').style.display = 'none';
  }

  //delete inquiry with confirmation
  function deleteInquiry(id) {
    if (confirm("Are you sure you want to delete this inquiry?")) {
 
      window.location.href = `inquiry_delete.php?id=${id}`;
    }
  }
</script>

</body>
</html>