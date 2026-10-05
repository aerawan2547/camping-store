<?php
session_start();
require_once '../config/db.php';

// เช็คสิทธิ์ Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// ตั้งค่า Query เริ่มต้น (ดึงเฉพาะลูกค้า)
$sql = "SELECT * FROM users WHERE role = 'customer'";
$params = [];
$types = "";

// ถ้ามีการค้นหา
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = "%" . $_GET['search'] . "%";
    // ค้นหาได้ทั้ง ชื่อ, username, หรือ email
    $sql .= " AND (fullname LIKE ? OR username LIKE ? OR email LIKE ?)";
    $params[] = $search;
    $params[] = $search;
    $params[] = $search;
    $types .= "sss"; // string 3 ตัว
}

// เรียงลำดับล่าสุดขึ้นก่อน
$sql .= " ORDER BY user_id DESC";

// Execute Query
if (!empty($params)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการลูกค้า - Admin Panel</title>
    <link rel="icon" type="image/png" href="../uploads/camp_icon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { 
            --primary-color: #40513B; 
            --secondary-color: #628141;
            --bg-color: #E5D9B6; 
            --accent-color: #E67E22; 
        }
        body { 
            background-color: var(--bg-color); 
            font-family: 'Sarabun', sans-serif; }
        
        .sidebar { 
            background-color: var(--primary-color); 
            min-height: 100vh; color: white; 
        }

        .sidebar a { 
            color: rgba(255,255,255,0.8); 
            text-decoration: none; 
            padding: 12px 20px; display: block; 
            transition: 0.3s; 
            border-radius: 8px; 
            margin-bottom: 5px; 
        }

        .sidebar a:hover, .sidebar a.active { 
            background-color: var(--secondary-color); 
            color: white; 
            transform: translateX(5px); 
        }
        
        .table-card { 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
            border: none; 
            background: white; 
        }

        .avatar-circle { 
            width: 40px; 
            height: 40px; 
            background-color: #e9ecef; 
            border-radius: 50%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: var(--primary-color); 
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-4 d-none d-md-block">
            <h4 class="fw-bold mb-4 text-center"><i class="fas fa-campground me-2"></i>ADMIN</h4>
            <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
            <a href="admin_product_list.php"><i class="fas fa-box me-2"></i> จัดการสินค้า</a>
            <a href="admin_customer_list.php" class="active"><i class="fas fa-users me-2"></i> ลูกค้า</a>
            <hr>
            <a href="../index.php" target="_blank"><i class="fas fa-external-link-alt me-2"></i> ไปหน้าร้าน</a>
            <a href="../logout.php" class="text-danger mt-3"><i class="fas fa-sign-out-alt me-2"></i> ออกจากระบบ</a>
        </div>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">รายชื่อลูกค้า</h2>
                <div class="text-muted">ผลลัพธ์ <?php echo $result->num_rows; ?> คน</div>
            </div>

            <div class="card table-card p-4">
                <form method="GET" action="admin_customer_list.php" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="ค้นหาชื่อ, username หรือ อีเมล..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">ค้นหา</button>
                        </div>
                        <div class="col-md-2">
                            <a href="admin_customer_list.php" class="btn btn-outline-secondary w-100">ล้างค่า</a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th>Username</th>
                                <th>ข้อมูลติดต่อ</th>
                                <th style="width: 250px;">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="ps-4 text-muted">#<?php echo str_pad($row['user_id'], 4, '0', STR_PAD_LEFT); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <span class="fw-bold text-dark"><?php echo $row['fullname']; ?></span>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?php echo $row['username']; ?></span></td>
                                    <td>
                                        <small class="d-block"><i class="fas fa-envelope me-1 text-muted"></i> <?php echo $row['email']; ?></small>
                                        <?php if(!empty($row['phone'])): ?>
                                            <small class="d-block"><i class="fas fa-phone me-1 text-muted"></i> <?php echo $row['phone']; ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="admin_customer_detail.php?id=<?php echo $row['user_id']; ?>" class="btn btn-sm btn-outline-info" title="ดูประวัติ">
                                            <i class="fas fa-history"></i>
                                        </a>
                                        <a href="admin_customer_edit.php?id=<?php echo $row['user_id']; ?>" class="btn btn-sm btn-outline-warning" title="แก้ไขข้อมูล">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="admin_customer_delete.php?id=<?php echo $row['user_id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('คำเตือน! การลบลูกค้าจะทำให้ประวัติการสั่งซื้อทั้งหมดหายไปด้วย\nยืนยันที่จะลบหรือไม่?');" title="ลบลูกค้า">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="fas fa-user-slash fa-3x mb-3 opacity-25"></i><br>
                                        ไม่พบข้อมูลลูกค้าที่ค้นหา
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>