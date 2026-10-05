<?php
session_start();
require_once 'config/db.php';

// เช็คว่าล็อกอินอยู่ไหม
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ป้องกัน Admin เผลอลบตัวเอง 
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    echo "<script>
            alert('Admin ไม่สามารถลบบัญชีตัวเองผ่านหน้านี้ได้');
            window.location = 'edit_profile.php';
          </script>";
    exit();
}

// คำสั่งลบข้อมูล
$sql = "DELETE FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    // ลบสำเร็จ -> ทำลาย Session (Logout)
    session_destroy();
    echo "<script>
            alert('บัญชีของคุณถูกลบเรียบร้อยแล้ว');
            window.location = 'index.php';
          </script>";
} else {
    // กรณีลบไม่สำเร็จ (เช่น มีออเดอร์ค้างอยู่ แล้ว Database ล็อกไว้)
    echo "<script>
            alert('ไม่สามารถลบบัญชีได้ เนื่องจากอาจมีประวัติการสั่งซื้อค้างอยู่ในระบบ');
            window.location = 'edit_profile.php';
          </script>";
}
?>