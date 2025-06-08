<?php

session_start();


require_once 'config.php';


if (!isset($_SESSION['order'])) {
    die("No order data found. Please start the order process again.");
}

//pang fetch ng order data from session
$order = $_SESSION['order'];


$contact_name = $order['contact_name'];      
$delivery_address = $order['delivery_address']; 
$contact_number = $order['contact_number'];     
$email = $order['email'];                       
$payment_method = $order['payment_method'];     


$delivery_datetime = $order['delivery_datetime'];

//(YYYY-MM-DD HH:MM:SS)
try {
    $dateTime = new DateTime($delivery_datetime);
    $delivery_datetime_formatted = $dateTime->format('Y-m-d H:i:s');
} catch (Exception $e) {
    $delivery_datetime_formatted = date('Y-m-d H:i:s');
}



try {
    //pang fetch ng products from database with their current prices
    $products_stmt = $pdo->prepare("SELECT id, product_name, price, quantity_in_stock FROM stocks");
    $products_stmt->execute();
    $all_products = $products_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    
   
    $product_mapping = [
        'gallon_5_qty' => ['5 Gallon Jug'],                                    
        'bottle_500ml_qty' => ['500ml Bottled Water', '500ml Bottle'],        
        'bottle_1l_qty' => ['1 Liter Bottled Water', '1 Liter Bottle', '1L']  
    ];
    
  
    $product_lookup = [];
    foreach ($all_products as $product) {
        $product_lookup[$product['product_name']] = [
            'id' => $product['id'],
            'price' => floatval($product['price']),
            'stock' => intval($product['quantity_in_stock'])
        ];
    }
    
 
    $order_items = [];      
    $total_amount = 0;     
    $order_columns = [];   
    $order_values = [];     
    

    foreach ($product_mapping as $qty_field => $possible_product_names) {
        $quantity = intval($order[$qty_field] ?? 0);  //get quantity from order
        
        if ($quantity > 0) {  // mag p process lang if quantity is greater than 0
            $product_found = false;
            
            
            foreach ($possible_product_names as $product_name) {
                if (isset($product_lookup[$product_name])) {
                    $product = $product_lookup[$product_name];
                    
                    //pang check if enough stock is available
                    if ($product['stock'] >= $quantity) {
                       
                        $subtotal = $quantity * $product['price'];
                        $total_amount += $subtotal;
                        
                        //pang store ng order (item details)
                        $order_items[] = [
                            'product_name' => $product_name,
                            'quantity' => $quantity,
                            'unit_price' => $product['price'],
                            'subtotal' => $subtotal,
                            'field_name' => $qty_field
                        ];
                        
                   
                        $order_columns[] = $qty_field;
                        $order_values[] = $quantity;
                        
                        $product_found = true;
                        break; //if mahanap na yung product, stop looking
                    } else {
                        //insufficient stock
                        die("Error: Insufficient stock for {$product_name}. Available: {$product['stock']}, Requested: {$quantity}");
                    }
                }
            }
            
            if (!$product_found) {
                //product not found in database
                $product_names_str = implode(', ', $possible_product_names);
                die("Error: Product not found in database. Looking for: {$product_names_str}");
            }
        }
    }
    
   
    
    
    
    $base_columns = ['contact_name', 'delivery_address', 'contact_number', 'email', 'payment_method', 'delivery_datetime', 'total_amount'];
    $base_values = [$contact_name, $delivery_address, $contact_number, $email, $payment_method, $delivery_datetime_formatted, $total_amount];
    
    
    $all_columns = array_merge($base_columns, $order_columns);
    $all_values = array_merge($base_values, $order_values);
    
   
    $columns_str = implode(', ', $all_columns);
    $placeholders = str_repeat('?,', count($all_columns) - 1) . '?';  
    
    $sql = "INSERT INTO orders ({$columns_str}) VALUES ({$placeholders})";
    
   
    $stmt = $pdo->prepare($sql);
    $stmt->execute($all_values);
    
    
    foreach ($order_items as $item) {
        $update_stock = $pdo->prepare("UPDATE stocks SET quantity_in_stock = quantity_in_stock - ? WHERE product_name = ?");
        $update_stock->execute([$item['quantity'], $item['product_name']]);
    }
    
    
    $_SESSION['order_summary'] = [
        'name' => $contact_name,
        'number' => $contact_number,
        'address' => $delivery_address,
        'email' => $email,
        'payment' => $payment_method,
        'delivery' => $delivery_datetime_formatted,
        'total' => $total_amount,
        'items' => $order_items,  //detailed breakdown ng ordered items
        'order_date' => date('Y-m-d H:i:s')
    ];
    
    //pang clear ng order data from session
    unset($_SESSION['order']);
    
    //redirect sa success page
    header("Location: done.php");
    exit;

} catch (PDOException $e) {
    //error handler
    die("Error processing order: " . $e->getMessage() . "<br><br>" .
        "Debug Info:<br>" .
        "Total calculated: $" . number_format($total_amount ?? 0, 2) . "<br>" .
        "Items processed: " . count($order_items ?? []) . "<br>" .
        "<a href='order1.php'>← Go back and try again</a>");
}
?>