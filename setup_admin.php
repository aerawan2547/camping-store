<?php
require_once '../config/db.php';

//กำหนด Username+Password ที่ต้องการ
$username = 'admin';
$password_raw = 'admin1234';
$fullname = 'Administrator';
$email = 'admin@campingstore.com';

//เข้ารหัสรหัสผ่าน
$password_hash = password_hash($password_raw, PASSWORD_DEFAULT);

//เช็คว่ามี user admin อยู่แล้วไหม
$check_sql = "SELECT user_id FROM users WHERE username = 'admin'";
$result = $conn->query($check_sql);

if ($result->num_rows > 0) {
    //กรณีมีอยู่แล้ว อัปเดตให้เป็น Admin และแก้รหัสผ่านใหม่
    $sql = "UPDATE users SET password = ?, role = 'admin' WHERE username = 'admin'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $password_hash);
    
    if ($stmt->execute()) {
        echo "<h1>อัปเดต Admin สำเร็จ!</h1>";
        echo "<p>User: <strong>admin</strong></p>";
        echo "<p>Pass: <strong>admin1234</strong></p>";
    } else {
        echo "Error Updating: " . $conn->error;
    }
} else {
    //กรณีไม่มี เพิ่มใหม่
    $sql = "INSERT INTO users (fullname, username, email, password, role) VALUES (?, ?, ?, ?, 'admin')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $fullname, $username, $email, $password_hash);
    
    if ($stmt->execute()) {
        echo "<h1>สร้าง Admin ใหม่สำเร็จ!</h1>";
        echo "<p>User: <strong>admin</strong></p>";
        echo "<p>Pass: <strong>admin1234</strong></p>";
    } else {
        echo "Error Inserting: " . $conn->error;
    }
}

echo "<br><a href='../login.php'>ไปที่หน้าเข้าสู่ระบบ</a>";
?>