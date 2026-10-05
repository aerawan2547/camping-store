<?php
session_start();
require_once 'config/db.php';

// เช็คว่าล็อกอินหรือยัง
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';

// --- ส่วนบันทึกข้อมูล (เมื่อกดปุ่ม Submit) ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Validation เบื้องต้น
    if (empty($fullname) || empty($email)) {
        $message = '<div class="alert alert-danger">กรุณากรอกชื่อ-นามสกุล และอีเมล</div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = '<div class="alert alert-danger">รูปแบบอีเมลไม่ถูกต้อง</div>';
    } else {
        // เช็คว่าอีเมลซ้ำกับคนอื่นไหม (ยกเว้นตัวเอง)
        $check_sql = "SELECT user_id FROM users WHERE email = ? AND user_id != ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            $message = '<div class="alert alert-danger">อีเมลนี้มีผู้ใช้งานแล้ว</div>';
        } else {
            // อัปเดตข้อมูลลง Database
            $sql = "UPDATE users SET fullname = ?, email = ?, phone = ?, address = ? WHERE user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $fullname, $email, $phone, $address, $user_id);

            if ($stmt->execute()) {
                // อัปเดต Session ชื่อใหม่ (เพื่อให้หน้าเว็บเปลี่ยนชื่อทันที)
                $_SESSION['fullname'] = $fullname;
                $message = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> บันทึกข้อมูลเรียบร้อยแล้ว</div>';
            } else {
                $message = '<div class="alert alert-danger">เกิดข้อผิดพลาด: ' . $conn->error . '</div>';
            }
        }
    }
}

// --- ดึงข้อมูลปัจจุบันมาแสดง ---
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>แก้ไขข้อมูลส่วนตัว - Camping Store</title>
    <link rel="icon" type="image/png" href="uploads/camp_icon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { 
            --primary-color: #40513B; 
            --secondary-color: #628141; 
            --bg-color: #E5D9B6; }

        body { 
            background-color: #f4f6f9; 
            font-family: 'Sarabun', sans-serif; }

        .card-custom { 
            border: none; 
            border-radius: 15px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); }

        .btn-save { 
            background-color: var(--secondary-color); 
            color: white; 
            border: none; }

        .btn-save:hover { 
            background-color: var(--primary-color); 
            color: white; }

    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4" style="background-color: var(--primary-color) !important;">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="fas fa-campground me-2"></i>Camping Store</a>
        <a href="index.php" class="btn btn-outline-light btn-sm">กลับหน้าหลัก</a>
    </div>
</nav>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card card-custom p-4">
                <h3 class="text-center mb-4 fw-bold" style="color: var(--primary-color);">
                    <i class="fas fa-user-edit me-2"></i>แก้ไขข้อมูลส่วนตัว
                </h3>
                
                <?php echo $message; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label text-muted small">Username</label>
                        <input type="text" class="form-control bg-light" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">ชื่อ-นามสกุล <span class="text-danger">*</span></label>
                        <input type="text" name="fullname" class="form-control" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">อีเมล <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">เบอร์โทรศัพท์</label>
                        <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="08x-xxx-xxxx">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ที่อยู่จัดส่งสินค้า (ค่าเริ่มต้น)</label>
                        <textarea name="address" class="form-control" rows="3" placeholder="บ้านเลขที่, ถนน, แขวง/ตำบล, เขต/อำเภอ..."><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-save py-2 fw-bold">บันทึกการเปลี่ยนแปลง</button>
                        <a href="index.php" class="btn btn-outline-secondary">ยกเลิก</a>

                        <hr class="my-2">
                        <a href="delete_account.php" class="btn btn-danger" onclick="return confirm('⚠️ คำเตือน!\n\nคุณแน่ใจหรือไม่ที่จะลบบัญชี?\nการกระทำนี้ไม่สามารถย้อนกลับได้ และประวัติข้อมูลของคุณจะหายไปทั้งหมด');">
                            <i class="fas fa-trash-alt me-2"></i>ลบบัญชีผู้ใช้
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>