<?php
session_start();
require_once 'config/db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation แบบ Server-side 
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $message = '<div class="alert alert-danger">Username หรือ Email มีผู้ใช้แล้ว</div>';
    } elseif ($password !== $confirm_password) {
        $message = '<div class="alert alert-danger">รหัสผ่านไม่ตรงกัน</div>';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_stmt = $conn->prepare("INSERT INTO users (fullname, username, email, password, role) VALUES (?, ?, ?, ?, 'customer')");
        $insert_stmt->bind_param("ssss", $fullname, $username, $email, $hashed_password);
        
        if ($insert_stmt->execute()) {
            $_SESSION['success_msg'] = "สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ";
            header("Location: login.php");
            exit();
        } else {
            $message = '<div class="alert alert-danger">เกิดข้อผิดพลาด!</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สมัครสมาชิก - Camping Gear Store</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="uploads/camp_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            max-width: 500px; 
            border-radius: 15px; 
            border: none; 
            box-shadow: 0 10px 20px rgba(64,81,59,0.15); 
            background: white;
        }
        .btn-custom { 
            background-color: #E67E22; 
            color: white; 
            border-radius: 50px; 
            width: 100%; 
            font-weight: bold; 
            border: none; 
        }
        .btn-custom:hover { 
            background-color: #d35400; 
            color: white; 
        }
        .text-primary-custom { 
            color: #40513B; 
            font-weight: bold; 
        }
        .status-msg { 
            margin-top: 5px; 
            display: block; }
    </style>
</head>
<body>
    <div class="card card-custom p-4 my-4">
        <h3 class="text-center text-primary-custom mb-4">สมัครสมาชิกใหม่</h3>
        
        <?php if(!empty($message)) echo $message; ?>

        <form action="" method="POST" id="registerForm">
            <div class="mb-3">
                <label class="form-label small text-muted">ชื่อ-นามสกุล</label>
                <input type="text" name="fullname" class="form-control" required placeholder="เช่น สมชาย ใจดี">
            </div>
            
            <div class="mb-3">
                <label class="form-label small text-muted">Username (ภาษาอังกฤษ/ตัวเลข)</label>
                <input type="text" name="username" id="username" class="form-control" required placeholder="เช่น somchai01" autocomplete="off">
                <span id="username_status" class="status-msg"></span>
            </div>

            <div class="mb-3">
                <label class="form-label small text-muted">อีเมล</label>
                <input type="email" name="email" id="email" class="form-control" required placeholder="email@example.com">
                <span id="email_status" class="status-msg"></span>
            </div>

            <div class="mb-3">
                <label class="form-label small text-muted">รหัสผ่าน (ขั้นต่ำ 6 ตัวอักษร)</label>
                <input type="password" name="password" id="password" class="form-control" required>
                <span id="password_status" class="status-msg"></span>
            </div>

            <div class="mb-3">
                <label class="form-label small text-muted">ยืนยันรหัสผ่าน</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                <span id="confirm_status" class="status-msg"></span>
            </div>

            <button type="submit" name="register_btn" id="submitBtn" class="btn btn-custom py-2 mt-3" disabled>ลงทะเบียน</button>
        </form>

        <div class="text-center mt-3">
            <small>เป็นสมาชิกอยู่แล้ว? <a href="login.php" style="color: #628141; font-weight: bold; text-decoration: none;">เข้าสู่ระบบที่นี่</a></small>
        </div>
        <div class="text-center mt-2">
            <a href="index.php" class="text-secondary small text-decoration: none;">กลับหน้าหลัก</a>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            let userValid = false;
            let emailValid = false; // สมมติให้ผ่านไว้ก่อนถ้า format ถูก
            let passValid = false;
            let confirmValid = false;

            function checkFormValidity() {
                if(userValid && passValid && confirmValid) {
                    $('#submitBtn').prop('disabled', false);
                } else {
                    $('#submitBtn').prop('disabled', true);
                }
            }

            // 1. ตรวจสอบ Username แบบ Real-time (AJAX)
            $('#username').on('blur keyup', function(){
                let username = $(this).val();
                
                // กฎ Regex: ต้องมีเลข 1 ตัวขึ้นไป และยาวรวม 6 ตัวขึ้นไป
                let usernameRegex = /^(?=.*\d)[a-zA-Z0-9_]{6,}$/;

                if(usernameRegex.test(username)){
                    // ถ้ารูปแบบผ่าน ค่อยยิงไปเช็คว่าซ้ำไหม
                    $.ajax({
                        url: 'check_availability.php',
                        method: 'POST',
                        data: {username: username},
                        success: function(response){
                            $('#username_status').html(response);
                            if(response.includes('ใช้ได้')){
                                userValid = true;
                            } else {
                                userValid = false;
                            }
                            checkFormValidity();
                        }
                    });
                } else {
                    // ถ้ารูปแบบไม่ผ่าน
                    if(username.length < 6) {
                        $('#username_status').html('<span class="text-danger small">ต้องมีความยาวอย่างน้อย 6 ตัวอักษร</span>');
                    } else {
                        $('#username_status').html('<span class="text-danger small">ต้องมีตัวเลขผสมอยู่อย่างน้อย 1 ตัว (เช่น somchai01)</span>');
                    }
                    userValid = false;
                    checkFormValidity();
                }
            });

            // 2. ตรวจสอบ Email (AJAX)
            $('#email').on('blur', function(){
                let email = $(this).val();
                let filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                
                if(filter.test(email)){
                    $.ajax({
                        url: 'check_availability.php',
                        method: 'POST',
                        data: {email: email},
                        success: function(response){
                            if(response != ''){
                                $('#email_status').html(response);
                                emailValid = false; // ถ้ามี response กลับมาแปลว่าซ้ำ
                            } else {
                                $('#email_status').html('<span class="text-success small"><i class="fas fa-check-circle"></i> อีเมลใช้ได้</span>');
                                emailValid = true;
                            }
                            checkFormValidity();
                        }
                    });
                } else {
                    $('#email_status').html('<span class="text-danger small">รูปแบบอีเมลไม่ถูกต้อง</span>');
                    emailValid = false;
                    checkFormValidity();
                }
            });

            // 3. ตรวจสอบ Password Length
            $('#password').on('keyup', function(){
                let password = $(this).val();
                if(password.length < 6){
                    $('#password_status').html('<span class="text-danger small"><i class="fas fa-times-circle"></i> รหัสผ่านต้องอย่างน้อย 6 ตัวอักษร</span>');
                    passValid = false;
                } else {
                    $('#password_status').html('<span class="text-success small"><i class="fas fa-check-circle"></i> รหัสผ่านใช้ได้</span>');
                    passValid = true;
                }
                
                // เช็ค confirm password ซ้ำทันทีถ้ามีการแก้ไข
                if($('#confirm_password').val() != ''){
                    $('#confirm_password').trigger('keyup');
                }
                checkFormValidity();
            });

            // 4. ตรวจสอบ Confirm Password Match
            $('#confirm_password').on('keyup', function(){
                let password = $('#password').val();
                let confirm = $(this).val();
                
                if(confirm == ''){
                     $('#confirm_status').html('');
                     confirmValid = false;
                } else if(password != confirm){
                    $('#confirm_status').html('<span class="text-danger small"><i class="fas fa-times-circle"></i> รหัสผ่านไม่ตรงกัน</span>');
                    confirmValid = false;
                } else {
                    $('#confirm_status').html('<span class="text-success small"><i class="fas fa-check-circle"></i> รหัสผ่านตรงกัน</span>');
                    confirmValid = true;
                }
                checkFormValidity();
            });
        });
    </script>
</body>
</html>