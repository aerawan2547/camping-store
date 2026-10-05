<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: admin_customer_list.php");
    exit();
}
$user_id = $_GET['id'];

//ดึงข้อมูลลูกค้า
$sql_user = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql_user);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

//ดึงประวัติการสั่งซื้อของลูกค้ารายนั้นๆ
$sql_orders = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt2 = $conn->prepare($sql_orders);
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$orders = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ประวัติลูกค้า: <?php echo $user['fullname']; ?></title>
    <link rel="icon" type="image/png" href="../uploads/camp_icon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { 
            --primary-color: #40513B; 
            --secondary-color: #628141; 
            --bg-color: #E5D9B6; 
        }
        body { 
            background-color: #f0efe9; 
            font-family: 'Sarabun', sans-serif; 
        }
        .card-header-custom { 
            background-color: var(--primary-color); 
            color: white; 
        }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold" style="color: var(--primary-color);">ข้อมูลลูกค้า</h3>
        <a href="admin_customer_list.php" class="btn btn-outline-secondary rounded-pill"><i class="fas fa-arrow-left me-2"></i> กลับหน้ารายชื่อ</a>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center p-5">
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-user fa-3x text-secondary"></i>
                    </div>
                    <h5 class="fw-bold"><?php echo $user['fullname']; ?></h5>
                    <p class="text-muted mb-1">@<?php echo $user['username']; ?></p>
                    <p class="text-muted small"><?php echo $user['email']; ?></p>
                    <hr>
                    <div class="d-flex justify-content-between px-4">
                        <span>จำนวนคำสั่งซื้อ</span>
                        <span class="fw-bold text-success"><?php echo $orders->num_rows; ?> ครั้ง</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header card-header-custom">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i> ประวัติการสั่งซื้อ</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Order ID</th>
                                <th>วันที่</th>
                                <th>ยอดเงิน</th>
                                <th>สถานะ</th>
                                <th>ดูข้อมูล</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($orders->num_rows > 0): ?>
                                <?php while($row = $orders->fetch_assoc()): ?>
                                <tr>
                                    <td class="ps-4 fw-bold">#ORD-<?php echo str_pad($row['order_id'], 5, '0', STR_PAD_LEFT); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['order_date'])); ?></td>
                                    <td>฿<?php echo number_format($row['total_amount']); ?></td>
                                    <td>
                                        <?php 
                                            $st = $row['status'];
                                            $color = ($st=='paid') ? 'primary' : (($st=='shipped') ? 'success' : 'secondary');
                                        ?>
                                        <span class="badge bg-<?php echo $color; ?> rounded-pill"><?php echo ucfirst($st); ?></span>
                                    </td>
                                    <td>
                                        <a href="admin_order_update.php?id=<?php echo $row['order_id']; ?>" class="btn btn-sm btn-light border">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted">ยังไม่มีประวัติการสั่งซื้อ</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>