<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

//เพิ่มสินค้าลงตะกร้า
if ($action == 'add' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    //เช็คสต็อก
    $sql = "SELECT stock_quantity FROM products WHERE product_id = $product_id";
    $result = $conn->query($sql);
    $product = $result->fetch_assoc();

    if ($product) {
        //ถ้ามีสินค้านั้นๆในตะกร้าแล้ว ให้บวกจำนวนเพิ่ม
        if (isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] += $quantity;
        } else {
            //ถ้าไม่มีก็เพิ่มลงไป
            $_SESSION['cart'][$product_id] = $quantity;
        }
        
        //ไม่ให้สั่งเกินสต็อก
        if ($_SESSION['cart'][$product_id] > $product['stock_quantity']) {
             $_SESSION['cart'][$product_id] = $product['stock_quantity'];
        }
    }
    
    //กลับไปหน้าตะกร้า
    header("Location: cart.php");
    exit();
}
//อัปเดตจำนวนสินค้าจากหน้าตะกร้า
if ($action == 'update') {
    $amounts = $_POST['amount'];
    
    foreach ($amounts as $pid => $qty) {
        $qty = (int)$qty;
        if ($qty <= 0) {
            unset($_SESSION['cart'][$pid]); // ถ้าใส่ 0 หรือติดลบให้ลบออก
        } else {
            //เช็คสต็อกอีกรอบ
            $sql = "SELECT stock_quantity FROM products WHERE product_id = $pid";
            $res = $conn->query($sql);
            $row = $res->fetch_assoc();
            
            if ($qty > $row['stock_quantity']) {
                $qty = $row['stock_quantity'];
            }
            $_SESSION['cart'][$pid] = $qty;
        }
    }
    header("Location: cart.php");
    exit();
}
//ลบสินค้าทีละชิ้น
if ($action == 'delete' && isset($_GET['id'])) {
    $product_id = $_GET['id'];
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php");
    exit();
}

//ล้างตะกร้า
if ($action == 'clear') {
    unset($_SESSION['cart']);
    header("Location: cart.php");
    exit();
}
header("Location: index.php");
exit();
?>