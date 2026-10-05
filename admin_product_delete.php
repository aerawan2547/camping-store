<?php
session_start();
require_once '../config/db.php';

if (isset($_GET['id']) && $_SESSION['role'] == 'admin') {
    $id = $_GET['id'];
    //ลบสินค้า
    $conn->query("DELETE FROM products WHERE product_id = $id");
    //กลับหน้ารายการ
    header("Location: admin_product_list.php");
}
?>