<?php
session_start();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['role'] == 'admin') {
    $id = $_POST['product_id'];
    $name = $_POST['product_name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock_quantity'];
    $brand = $_POST['brand_id'];
    $cat = $_POST['category_id'];
    $featured = isset($_POST['is_featured']) ? 1 : 0;
    
    //จัดการรูปภาพ
    $image_url = $_POST['old_image']; //รูปเดิม
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = "uploads/";
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_name = uniqid() . "." . $ext; //ตั้งชื่อไฟล์ใหม่กันซ้ำ
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_name);
        $image_url = $new_name; //ชื่อรูปใหม่
    }

    if (!empty($id)) {
        $sql = "UPDATE products SET product_name=?, description=?, price=?, stock_quantity=?, brand_id=?, category_id=?, image_url=?, is_featured=? WHERE product_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdiiisii", $name, $desc, $price, $stock, $brand, $cat, $image_url, $featured, $id);
    } else {
        $sql = "INSERT INTO products (product_name, description, price, stock_quantity, brand_id, category_id, image_url, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdiiisi", $name, $desc, $price, $stock, $brand, $cat, $image_url, $featured);
    }

    if ($stmt->execute()) {
        echo "<script>alert('บันทึกข้อมูลสำเร็จ'); window.location='admin_product_list.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>