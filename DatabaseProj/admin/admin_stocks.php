<?php

session_start();
$activePage = 'stocks';


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
  <title>Admin - Stocks</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; font-family: 'Inter', sans-serif; }
    body { margin: 0; background-color: #dffeff; color: #333; display: flex; }

    .main { flex-grow: 1; display: flex; flex-direction: column; }

    table {
      width: calc(100% - 40px); margin: 0 20px; border-collapse: collapse;
    }
    th, td {
      padding: 12px 8px; text-align: left; border-bottom: 1px solid #ccc;
    }
    th { font-weight: bold; }

    .status-instock { color: green; font-weight: 600; }
    .status-low { color: #e67e22; font-weight: 600; }

    .actions button {
      margin-right: 8px; padding: 5px 10px; border: none;
      color: white; border-radius: 4px; cursor: pointer;
    }
    .actions .edit { background-color: #0dbf92; }
    .actions .delete { background-color: #ff4d4d; }

    .add-button {
      position: fixed; bottom: 100px; right: 100px;
      padding: 10px 20px; background-color: #0dbf92; border: none;
      border-radius: 6px; color: white; font-size: 16px; cursor: pointer;
      max-width: 400px; z-index: 1001;
    }

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
    .popup input {
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
    $pageTitle = 'Stocks';
    include 'admin_header.php';
    ?>

  <!--add button -->
  <button class="add-button" onclick="openAddModal()">Add Item</button>

  <table>
    <thead>
      <tr>
        <th>Product</th>
        <th>Quantity in Stock</th>
        <th>Price</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      try {
          //pang fetch ng stock items from database using 
          //alphabetical listing
          $stmt = $pdo->prepare("SELECT * FROM stocks ORDER BY product_name ASC");
          $stmt->execute();
          
     
          $stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
          
          if (count($stocks) > 0) {
              
              foreach ($stocks as $row) {
                  // Determine stock status based on quantity
                  //if ess than 20 items = Low Stock, else nde
                  $status = ($row['quantity_in_stock'] < 20) ? 'Low Stock' : 'In Stock';
                  $statusClass = ($status === 'In Stock') ? 'status-instock' : 'status-low';
                  
                
                  $priceFormatted = number_format($row['price'], 2);
                  
               
                  $id = htmlspecialchars($row['id']);
                  $product_name = htmlspecialchars($row['product_name']);
                  $quantity = htmlspecialchars($row['quantity_in_stock']);
                  $unit = htmlspecialchars($row['unit']);
                  $price = htmlspecialchars($row['price']);
                  
                  //para ma display each stock item as a table row
                  echo "<tr>
                      <td>{$product_name}</td>
                      <td>{$quantity} {$unit}</td>
                      <td>₱{$priceFormatted}</td>
                      <td class='{$statusClass}'>{$status}</td>
                      <td class='actions'>
                          <button class='edit' onclick=\"openEditModal({$id}, '{$product_name}', {$quantity}, {$price}, '{$unit}')\">Edit</button>
                          <button class='delete' onclick=\"deleteItem({$id})\">Delete</button>
                      </td>
                  </tr>";
              }
          } else {
              //if no stock items found in database
              echo "<tr><td colspan='5'>No stock data found.</td></tr>";
          }
          
      } catch (PDOException $e) {
        
          echo "<tr><td colspan='5'>Error loading stock data: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>


<div id="overlay"></div>

<!--add new item -->
<div id="addModal" class="popup">
  <form action="stock_add.php" method="POST">
    <h2>Add New Item</h2>
    
    <p>Product Name</p>
    <input name="product_name" required>
    
    <p>Quantity in Stock</p>
    <input name="quantity_in_stock" type="number" required>
    
    <p>Price</p>
    <input name="price" type="number" step="0.01" required>
    
    <p>Unit</p>
    <input name="unit" required>
    
    <button type="submit">Add</button>
    <button type="button" onclick="closePopup()">Cancel</button>
  </form>
</div>

<!--edit item  -->
<div id="editModal" class="popup">
  <form action="stock_edit.php" method="POST">
    <h2>Edit Item</h2>
    
    <!--pang store ng item ID -->
    <input type="hidden" name="id" id="edit_id">
    
    <p>Product Name</p>
    <input name="product_name" id="edit_product" required>
    
    <p>Quantity</p>
    <input name="quantity_in_stock" id="edit_quantity" type="number" required>
    
    <p>Price</p>
    <input name="price" id="edit_price" type="number" step="0.01" required>
    
    <p>Unit</p>
    <input name="unit" id="edit_unit" required>
    
    <button type="submit">Update</button>
    <button type="button" onclick="closePopup()">Cancel</button>
  </form>
</div>

<script>
  
  function openAddModal() {
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('addModal').style.display = 'block';
  }

  // Function to open edit modal with existing item data
  function openEditModal(id, product, quantity, price, unit) {
    // Fill the form fields with existing data
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_product').value = product;
    document.getElementById('edit_quantity').value = quantity;
    document.getElementById('edit_price').value = price;
    document.getElementById('edit_unit').value = unit;
    
    //modal
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('editModal').style.display = 'block';
  }

  //function para ma close all modal popups
  function closePopup() {
    document.getElementById('overlay').style.display = 'none';
    document.getElementById('addModal').style.display = 'none';
    document.getElementById('editModal').style.display = 'none';
  }

  //function to delete item with confirmation
  function deleteItem(id) {
    if (confirm("Are you sure you want to delete this item?")) {
     
      window.location.href = `stock_delete.php?id=${id}`;
    }
  }
</script>

</body>
</html>