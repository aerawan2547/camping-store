<?php
session_start();
require_once '../config/db.php';

// เช็คสิทธิ์ Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // ป้องกันการลบตัวเอง (Admin ห้ามลบตัวเอง)
    if ($user_id == $_SESSION['user_id']) {
        echo "<script>alert('ไม่สามารถลบบัญชีตัวเองได้!'); window.location='admin_customer_list.php';</script>";
        exit();
    }

    // ลบข้อมูล
    $sql = "DELETE FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        header("Location: admin_customer_list.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการลบ: " . $conn->error;
    }
} else {
    header("Location: admin_customer_list.php");
    exit();
}
?>