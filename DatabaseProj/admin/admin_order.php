<?php
session_start();

if (!isset($_SESSION['admin_username'])) {
    header('Location: ../index.php?login_required=true'); 
    exit(); 
}

$activePage = 'orders';
require_once '../config.php';

try {
    $pending_stmt = $pdo->prepare("SELECT COUNT(*) as count FROM orders WHERE order_status = 'Pending'");
    $pending_stmt->execute();
    $pendingCount = $pending_stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    $done_stmt = $pdo->prepare("SELECT COUNT(*) as count FROM orders WHERE order_status = 'Done'");
    $done_stmt->execute();
    $doneCount = $done_stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    $totalCount = $pendingCount + $doneCount;
    
} catch (PDOException $e) {
    $pendingCount = $doneCount = $totalCount = 0;
    error_log("Error fetching order statistics: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin - Orders</title>
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
    
    .orders-summary {
      display: flex;
      gap: 20px;
      padding: 0 20px;
    }
    .orders-summary div {
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 600;
    }
    .orders-table {
      width: 100%;
      border-collapse: collapse;
      margin: 0 20px;
    }
    .orders-table th, .orders-table td {
      padding: 12px 8px;
      text-align: left;
      border-bottom: 1px solid #ccc;
      transition: .3s ease-in-out;
    }
    .orders-table tr:hover {
      
      cursor: pointer;
      color: 	#F0EAD6;
    }

    .status-btn {
      padding: 6px 12px;
      margin-right: 6px;
      border: 1.5px solid #3b82f6;
      background: transparent;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 600;
      color: #3b82f6;
      transition: all 0.3s ease;
      user-select: none;
    }
    .status-btn.active {
      background-color: #3b82f6;
      color: white;
      pointer-events: none;
    }
    .status-btn:hover:not(.active) {
      background-color: #3b82f6;
      color: white;
    }

    .details-pane {
      width: 320px;
      background-color: #282424;
      color: white;
      padding: 20px;
      border-left: 1px solid #ccc;
      display: none;
      flex-direction: column;
      position: absolute;
      top: 0;
      right: 0;
      height: 100%;
      margin-top: 100px;
    }
    .details-pane h3 {
      margin-top: 0;
    }
    .status {
      font-weight: bold;
      color: green;
    }
    .total {
      font-size: 18px;
      font-weight: bold;
    }
    .pay-status {
      background: #e0dcdc;
      color: #333;
      padding: 10px;
      border-radius: 10px;
      text-align: center;
      margin-top: 10px;
    }
    .close-btn {
      align-self: flex-end;
      font-size: 20px;
      cursor: pointer;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

  <div class="main">
      <?php
      $pageTitle = 'Orders List';
      include 'admin_header.php';
      ?>

    <div class="orders-summary">
      <div>🧊 <span><?php echo $totalCount; ?> Orders</span></div>
      <div>📄 <span><?php echo $pendingCount; ?> Pending</span></div>
      <div>✅ <span><?php echo $doneCount; ?> Delivered</span></div>
    </div>

    <table class="orders-table">
      <thead>
        <tr>
          <th>Order</th>
          <th>Customer</th>
          <th>Product</th>
          <th>Delivery Time</th>
          <th>Total Amount</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody id="orders-body">
        <?php
        try {
            $orders_stmt = $pdo->prepare("SELECT * FROM orders ORDER BY created_at DESC");
            $orders_stmt->execute();
            $orders = $orders_stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($orders) > 0) {
                foreach ($orders as $order) {
                    $productList = [];
                    if ($order['gallon_5_qty'] > 0) $productList[] = "5 Gal x{$order['gallon_5_qty']}";
                    if ($order['bottle_500ml_qty'] > 0) $productList[] = "500ml x{$order['bottle_500ml_qty']}";
                    if ($order['bottle_1l_qty'] > 0) $productList[] = "1 Liter x{$order['bottle_1l_qty']}";
                    $products = implode(', ', $productList);

                    $isPending = ($order['order_status'] == 'Pending');
                    
                    $id = htmlspecialchars($order['id']);
                    $name = htmlspecialchars($order['contact_name']);
                    $delivery = htmlspecialchars($order['delivery_datetime']);
                    $total = number_format($order['total_amount'], 2);

                    echo "<tr onclick=\"showDetails('{$id}')\">
                            <td><button style='all: unset; cursor: pointer; color: #3b82f6;'>#{$id}</button></td>
                            <td>{$name}</td>
                            <td>{$products}</td>
                            <td>{$delivery}</td>
                            <td>₱{$total}</td>
                            <td>
                              <button class='status-btn " . ($isPending ? 'active' : '') . "' onclick='event.stopPropagation(); setStatus({$id}, \"pending\", this)'>Pending</button>
                              <button class='status-btn " . (!$isPending ? 'active' : '') . "' onclick='event.stopPropagation(); setStatus({$id}, \"delivered\", this)'>Delivered</button>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No orders found.</td></tr>";
            }
            
        } catch (PDOException $e) {
            echo "<tr><td colspan='6'>Error loading orders: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <?php
    try {
        $details_stmt = $pdo->prepare("SELECT * FROM orders ORDER BY created_at DESC");
        $details_stmt->execute();
        $detail_orders = $details_stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($detail_orders as $info) {
            $id = $info['id'];
            
            $products = [];
            if ($info['gallon_5_qty'] > 0) $products[] = "5 Gal x{$info['gallon_5_qty']}";
            if ($info['bottle_500ml_qty'] > 0) $products[] = "500ml x{$info['bottle_500ml_qty']}";
            if ($info['bottle_1l_qty'] > 0) $products[] = "1 Liter x{$info['bottle_1l_qty']}";
            $productStr = implode(', ', $products);
            
            $paid = ($info['order_status'] == 'Done');
            $statusText = $paid ? "Paid" : "Unpaid";
            $orderStatus = $paid ? "Completed" : "Pending";
            
            $formattedDate = date("F j, Y", strtotime($info['created_at']));
            
            $name = htmlspecialchars($info['contact_name']);
            $address = htmlspecialchars($info['delivery_address']);
            $contact = htmlspecialchars($info['contact_number']);
            $payment_method = htmlspecialchars($info['payment_method']);
            $total = number_format($info['total_amount'], 2);

            echo "<div class='details-pane' id='details-{$id}'>
                    <div class='close-btn' onclick='closeDetails()'>✕</div>
                    <div><strong>#{$id}</strong></div>
                    <p><strong>Status</strong><br>{$orderStatus}</p>
                    <p><strong>Address</strong><br>{$address}</p>
                    <hr>
                    <p><strong>Customer</strong><br>{$name}<br>{$contact}</p>
                    <p><strong>Orders</strong><br>{$productStr}</p>
                    <p><strong>Status</strong><br>{$statusText}</p>
                    <p><strong>Date issued</strong><br>{$formattedDate}</p>
                    <hr>
                    <p class='total'>Total<br>₱{$total}</p>
                    <div class='pay-status'>" . ($paid ? "✓ Paid with {$payment_method}" : "{$payment_method}") . "</div>
                  </div>";
        }
        
    } catch (PDOException $e) {
        error_log("Error generating order details: " . $e->getMessage());
    }
    ?>

  </div>

<script>
  function showDetails(orderId) {
    const panes = document.querySelectorAll('.details-pane');
    panes.forEach(pane => pane.style.display = 'none');
    
    const selectedPane = document.getElementById(`details-${orderId}`);
    if (selectedPane) selectedPane.style.display = 'flex';
  }

  function closeDetails() {
    document.querySelectorAll('.details-pane').forEach(pane => {
      pane.style.display = 'none';
    });
  }

  function setStatus(orderId, status, btn) {
    btn.disabled = true;
    btn.style.opacity = '0.5';
    
    fetch('update_order_status.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({order_id: orderId, status: status})
    })
    .then(response => response.json())
    .then(data => {
      if(data.success) {
        const td = btn.parentElement;
        td.querySelectorAll('.status-btn').forEach(b => {
          b.classList.remove('active');
          b.disabled = false;
          b.style.opacity = '1';
        });
        btn.classList.add('active');
        
        setTimeout(() => {
          window.location.reload();
        }, 500);
      } else {
        alert('Failed to update status: ' + data.message);
        btn.disabled = false;
        btn.style.opacity = '1';
      }
    })
    .catch(err => {
      alert('Error updating status: ' + err.message);
      btn.disabled = false;
      btn.style.opacity = '1';
    });
  }
</script>
</body>
</html>