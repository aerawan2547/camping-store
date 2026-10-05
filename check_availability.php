<?php
require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. ตรวจสอบ Username
    if (isset($_POST['username'])) {
        $username = trim($_POST['username']);
        
        // กฎ: ต้องมีเลขอย่างน้อย 1 ตัว (?=.*\d) และยาว 6 ตัวขึ้นไป {6,}
        // อนุญาตให้มี a-z, A-Z, 0-9 และ _
        if (!preg_match('/^(?=.*\d)[a-zA-Z0-9_]{6,}$/', $username)) {
            echo "<span style='color: red; font-size: 0.9rem;'><i class='fas fa-times-circle'></i> ต้องยาว 6 ตัวขึ้นไป และมีตัวเลขผสมอย่างน้อย 1 ตัว</span>";
            echo "<script>$('#submitBtn').prop('disabled', true);</script>";
        } else {
            // เช็คในฐานข้อมูลว่าซ้ำไหม
            $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                echo "<span style='color: red; font-size: 0.9rem;'><i class='fas fa-times-circle'></i> ชื่อนี้มีผู้ใช้งานแล้ว</span>";
                echo "<script>$('#submitBtn').prop('disabled', true);</script>";
            } else {
                echo "<span style='color: green; font-size: 0.9rem;'><i class='fas fa-check-circle'></i> ชื่อนี้ใช้ได้</span>";
            }
        }
    }

    // 2. ตรวจสอบ Email
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            echo "<span style='color: red; font-size: 0.9rem;'><i class='fas fa-times-circle'></i> อีเมลนี้มีในระบบแล้ว</span>";
             echo "<script>$('#submitBtn').prop('disabled', true);</script>";
        } else {
            // ผ่าน
        }
    }
}
?>