<?php
session_start();
require_once 'config/db.php';

$message = '';

//เช็คข้อความแจ้งเตือนจากหน้าสมัครสมาชิก 
if (isset($_SESSION['success_msg'])) {
    $message = '<div class="alert alert-success text-center">'.$_SESSION['success_msg'].'</div>';
    unset($_SESSION['success_msg']);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Validation 
    if (empty($username) || empty($password)) {
        $message = '<div class="alert alert-warning text-center">กรุณากรอกชื่อผู้ใช้และรหัสผ่าน</div>';
    } else {
        //ค้นหาข้อมูลจาก Username
        $sql = "SELECT user_id, username, password, fullname, role FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            //ตรวจสอบรหัสผ่าน
            if (password_verify($password, $user['password'])) {
                //ล็อกอินสำเร็จ
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['fullname'] = $user['fullname'];
                $_SESSION['role'] = $user['role']; 

                // Redirect ตาม Role
                if ($user['role'] === 'admin') {
                    header("Location: admin/admin_dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $message = '<div class="alert alert-danger text-center">รหัสผ่านไม่ถูกต้อง</div>';
            }
        } else {
            $message = '<div class="alert alert-danger text-center">ไม่พบชื่อผู้ใช้นี้ในระบบ</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>เข้าสู่ระบบ - Camping Gear Store</title>
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
            background: white;
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
        <h3 class="text-center mb-4" style="color: #40513B; font-weight: 800;">เข้าสู่ระบบ</h3>
        
        <?php if(!empty($message)) echo $message; ?>
        
        <form action="" method="POST">
            <div class="mb-3">
                <input type="text" name="username" class="form-control rounded-pill py-2 px-3" required placeholder="ชื่อผู้ใช้ (Username)" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control rounded-pill py-2 px-3" required placeholder="รหัสผ่าน">
            </div>
            <button type="submit" class="btn btn-login py-2 mt-2">เข้าสู่ระบบ</button>
        </form>
        <div class="text-center mt-3">
            <small><a href="forgot_password.php" style="color: #d54040; font-weight: bold; text-decoration: none;">ลืมรหัสผ่าน?</a></small>
        </div>
        <div class="text-center mt-3">
            <small>ยังไม่มีบัญชี? <a href="register.php" style="color: #E67E22; font-weight: bold; text-decoration: none;">สมัครสมาชิกที่นี่</a></small>
        </div>
        <div class="text-center mt-2">
            <a href="index.php" class="text-secondary small text-decoration: none;">กลับหน้าหลัก</a>
        </div>
    </div>
</body>
</html>