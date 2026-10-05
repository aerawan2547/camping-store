<?php
require_once 'config/db.php';

$message = '';
$token = $_GET['token'] ?? '';

if (!$token) {
    die("ลิงก์ไม่ถูกต้อง");
}

// ดึงข้อมูลจาก token
$stmt = $conn->prepare(
    "SELECT user_id, reset_expires 
     FROM users 
     WHERE reset_token = ?"
); 
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("ลิงก์ไม่ถูกต้อง หรือหมดอายุแล้ว");
}

$user = $result->fetch_assoc();

// เช็กหมดอายุ
if (strtotime($user['reset_expires']) < time()) {
    die("ลิงก์หมดอายุแล้ว");
}

// เมื่อกดตั้งรหัสผ่านใหม่
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $message = '<div class="alert alert-danger text-center">รหัสผ่านไม่ตรงกัน</div>';
    } elseif (strlen($password) < 6) {
        $message = '<div class="alert alert-danger text-center">รหัสผ่านต้องมีอย่างน้อย 6 ตัว</div>';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $update = $conn->prepare(
            "UPDATE users 
             SET password = ?, reset_token = NULL, reset_expires = NULL 
             WHERE user_id = ?"
        ); 
        $update->bind_param("si", $hash, $user['user_id']);
        $update->execute();

        $message = '
        <div class="alert alert-success text-center">
            ตั้งรหัสผ่านใหม่สำเร็จ<br>
            <a href="login.php" class="fw-bold">เข้าสู่ระบบ</a>
        </div>'; 
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ตั้งรหัสผ่านใหม่ - Camping Gear Store</title>
    <link rel="icon" type="image/png" href="uploads/camp_icon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #E5D9B6;
            font-family: 'Sarabun', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-custom {
            width: 100%;
            max-width: 420px;
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 20px rgba(64,81,59,0.15);
        }
        .btn-main {
            background-color: #40513B;
            color: white;
            border-radius: 50px;
            width: 100%;
            font-weight: bold;
            border: none;
        }
        .btn-main:hover {
            background-color: #2c3e2e;
            color: white;
        }
    </style>
</head>
<body>

<div class="card card-custom p-4">
    <h3 class="text-center mb-4" style="color:#40513B; font-weight:800;">
        ตั้งรหัสผ่านใหม่
    </h3>

    <?php echo $message; ?>

    <form method="post">
        <div class="mb-3">
            <input
                type="password"
                name="password"
                class="form-control rounded-pill py-2 px-3"
                placeholder="รหัสผ่านใหม่"
                required>
        </div>

        <div class="mb-3">
            <input
                type="password"
                name="confirm_password"
                class="form-control rounded-pill py-2 px-3"
                placeholder="ยืนยันรหัสผ่านใหม่"
                required>
        </div>

        <button type="submit" class="btn btn-main py-2 mt-2">
            บันทึกรหัสผ่านใหม่
        </button>
    </form>

    <div class="text-center mt-3">
        <a href="login.php" class="text-secondary small">← กลับไปหน้าเข้าสู่ระบบ</a>
    </div>
</div>

</body>
</html>
