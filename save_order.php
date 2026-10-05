<?php
session_start();
require_once 'config/db.php';

//ล็อกอิน + มีของในตะกร้า
if (!isset($_SESSION['user_id']) || empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

//โฟลเดอร์เก็บสลิป
$upload_dir = "uploads/slips/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true); 
}

$slip_filename = "";

//อัปโหลดไฟล์สลิป
if (isset($_FILES['payment_slip']) && $_FILES['payment_slip']['error'] == 0) {
    $ext = pathinfo($_FILES['payment_slip']['name'], PATHINFO_EXTENSION);
    $slip_filename = "slip_" . time() . "_" . $_SESSION['user_id'] . "." . $ext;
    move_uploaded_file($_FILES['payment_slip']['tmp_name'], $upload_dir . $slip_filename);
}

//เตรียมข้อมูลลง DB
$user_id = $_SESSION['user_id'];
$total_amount = $_POST['total_amount'];

$name = $_POST['fullname'];
$phone = $_POST['phone'];
$addr = $_POST['address'];

// รวมข้อมูลเป็นก้อนเดียวเพื่อบันทึกลง DB
$full_address = $name . " (" . $phone . ")\n" . $addr;
// --------------------

$conn->begin_transaction();

try {
    //Insert ลงตาราง orders
    $sql_order = "INSERT INTO orders (user_id, total_amount, shipping_address, payment_slip_url, status, order_date) VALUES (?, ?, ?, ?, 'pending', NOW())";
    $stmt = $conn->prepare($sql_order);
    $stmt->bind_param("idss", $user_id, $total_amount, $full_address, $slip_filename);
    
    if (!$stmt->execute()) {
        throw new Exception("บันทึก Order ไม่สำเร็จ: " . $conn->error);
    }
    
    //ดึง Order ID ล่าสุด
    $order_id = $stmt->insert_id; 

    //Insert รายการสินค้า
    $cart_ids = implode(',', array_keys($_SESSION['cart']));
    $sql_products = "SELECT product_id, price, stock_quantity FROM products WHERE product_id IN ($cart_ids)";
    $result = $conn->query($sql_products);

    $sql_item = "INSERT INTO orders_item (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)";
    $stmt_item = $conn->prepare($sql_item);

    while ($product = $result->fetch_assoc()) {
        $p_id = $product['product_id'];
        $qty = $_SESSION['cart'][$p_id];
        $price = $product['price'];
        
        $stmt_item->bind_param("iiid", $order_id, $p_id, $qty, $price);
        $stmt_item->execute();
        
        //ตัดสต็อก
        $new_stock = $product['stock_quantity'] - $qty;
        if ($new_stock < 0) $new_stock = 0;
        $conn->query("UPDATE products SET stock_quantity = $new_stock WHERE product_id = $p_id");
    }

    $conn->commit();
    unset($_SESSION['cart']);

    echo "<script> 
        alert('สั่งซื้อเรียบร้อย! ขอบคุณที่ใช้บริการครับ');
        window.location.href = 'order_history.php'; 
    </script>"; 

} catch (Exception $e) {
    $conn->rollback();
    echo "เกิดข้อผิดพลาด: " . $e->getMessage();
}
?>