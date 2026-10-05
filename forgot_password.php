<?php
require_once 'config/db.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);

    if (!empty($email)) {
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $token = bin2hex(random_bytes(32));
            $expires = date("Y-m-d H:i:s", time() + 3600);

            $update = $conn->prepare(
                "UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?"
            );
            $update->bind_param("sss", $token, $expires, $email);
            $update->execute();

            $message = '
            <div class="alert alert-success text-center">
                กรุณาใช้ลิงก์ด้านล่างเพื่อตั้งรหัสผ่านใหม่<br><br>
                <a href="reset_password.php?token='.$token.'" class="fw-bold">
                    รีเซ็ตรหัสผ่าน
                </a>
            </div>'; 
        } else {
            $message = '<div class="alert alert-danger text-center">ไม่พบอีเมลนี้ในระบบ</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ลืมรหัสผ่าน - Camping Gear Store</title>
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
            max-width: 400px; 
            border-radius: 15px; 
            border: none; 
            box-shadow: 0 10px 20px rgba(64,81,59,0.15); 
        }
        .btn-login { 
            background-color: #40513B; 
            color: white; 
            border-radius: 50px; 
            width: 100%; 
            font-weight: bold; 
            border: none; 
        }
        .btn-login:hover { 
            background-color: #2c3e2e; 
            color: white; 
        }
    </style>
</head>
<body>

<div class="card card-custom p-4">
    <h3 class="text-center mb-4" style="color: #40513B; font-weight: 800;">
        ลืมรหัสผ่าน
    </h3>

    <?php echo $message; ?>

    <form method="post">
        <div class="mb-3">
            <input 
                type="email" 
                name="email" 
                class="form-control rounded-pill py-2 px-3" 
                required 
                placeholder="อีเมลที่ใช้สมัครสมาชิก">
        </div>

        <button type="submit" class="btn btn-login py-2 mt-2">
            ขอลิงก์รีเซ็ตรหัสผ่าน
        </button>
    </form>

    <div class="text-center mt-3">
        <a href="login.php" class="text-secondary small">← กลับไปหน้าเข้าสู่ระบบ</a>
    </div>
</div>

</body>
</html>
