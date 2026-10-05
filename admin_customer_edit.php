<?php
session_start();
require_once '../config/db.php';

// เช็คสิทธิ์ Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: admin_customer_list.php");
    exit();
}

$user_id = $_GET['id'];
$message = '';

// ดึงข้อมูลเดิม
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    die("ไม่พบข้อมูลผู้ใช้");
}

// บันทึกการแก้ไข
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $username = trim($_POST['username']);

    // เช็ค Username ซ้ำ (กับคนอื่นที่ไม่ใช่ตัวเอง)
    $check = $conn->prepare("SELECT user_id FROM users WHERE username = ? AND user_id != ?");
    $check->bind_param("si", $username, $user_id);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        $message = '<div class="alert alert-danger">Username นี้มีผู้ใช้งานแล้ว</div>';
    } else {
        // เช็ค Email ซ้ำ
        $checkEmail = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
        $checkEmail->bind_param("si", $email, $user_id);
        $checkEmail->execute();
        
        if ($checkEmail->get_result()->num_rows > 0) {
            $message = '<div class="alert alert-danger">Email นี้มีผู้ใช้งานแล้ว</div>';
        } else {
            // อัปเดตข้อมูล
            if (!empty($new_password)) {
                // กรณีเปลี่ยนรหัสผ่านด้วย
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $sql_update = "UPDATE users SET fullname=?, email=?, phone=?, address=?, username=?, password=? WHERE user_id=?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("ssssssi", $fullname, $email, $phone, $address, $username, $hashed_password, $user_id);
            } else {
                // กรณีไม่เปลี่ยนรหัสผ่าน
                $sql_update = "UPDATE users SET fullname=?, email=?, phone=?, address=?, username=? WHERE user_id=?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("sssssi", $fullname, $email, $phone, $address, $username, $user_id);
            }

            if ($stmt_update->execute()) {
                $message = '<div class="alert alert-success">บันทึกข้อมูลสำเร็จ</div>';
                // รีเฟรชข้อมูลใหม่
                $stmt->execute();
                $user = $stmt->get_result()->fetch_assoc();
            } else {
                $message = '<div class="alert alert-danger">เกิดข้อผิดพลาด: ' . $conn->error . '</div>';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขลูกค้า - Admin Panel</title>
    <link rel="icon" type="image/png" href="../uploads/camp_icon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { 
            --primary-color: #40513B; 
            --bg-color: #E5D9B6; }
        body { 
            background-color: var(--bg-color); 
            font-family: 'Sarabun', sans-serif; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-user-edit me-2"></i>แก้ไขข้อมูลลูกค้า: <?php echo htmlspecialchars($user['fullname']); ?></h5>
                </div>
                <div class="card-body p-4">
                    
                    <?php echo $message; ?>

                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">ชื่อ-นามสกุล</label>
                                <input type="text" name="fullname" class="form-control" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">อีเมล</label>
                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">เบอร์โทรศัพท์</label>
                                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">ที่อยู่</label>
                            <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-end gap-2">
                            <a href="admin_customer_list.php" class="btn btn-secondary">ยกเลิก</a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> บันทึกการเปลี่ยนแปลง</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>