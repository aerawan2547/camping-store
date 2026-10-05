<?php
session_start();
require_once '../config/db.php';

//เช็คสิทธิ์ Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

//ออเดอร์ที่ถูกยกเลิก
$cancelledOrdersResult = $conn->query("SELECT COUNT(*) AS total FROM orders WHERE status = 'cancelled'");
$cancelledOrders = $cancelledOrdersResult->fetch_assoc()['total'];

//ดึงข้อมูลออเดอร์ทั้งหมด
$sql = "SELECT o.*, u.fullname 
        FROM orders o 
        JOIN users u ON o.user_id = u.user_id 
        ORDER BY o.order_date DESC"; 
$result = $conn->query($sql);

//ออเดอร์ทั้งหมด
$totalOrders = $conn->query("SELECT COUNT(*) AS total FROM orders")
                    ->fetch_assoc()['total'];
//รอชำระ
$pendingOrders = $conn->query(
    "SELECT COUNT(*) AS total FROM orders WHERE status = 'pending'"
)->fetch_assoc()['total'];

$paidOrders = $conn->query(
    "SELECT COUNT(*) AS total FROM orders WHERE status = 'paid'"
)->fetch_assoc()['total'];

$totalRevenue = $conn->query(
    "SELECT SUM(total_amount) AS total FROM orders WHERE status IN ('paid', 'shipped', 'completed')"
)->fetch_assoc()['total'] ?? 0;

// --- ส่วนการค้นหาและกรองข้อมูล ---

// 1. ตั้งค่า Query เริ่มต้น
$sql = "SELECT o.*, u.fullname 
        FROM orders o 
        JOIN users u ON o.user_id = u.user_id 
        WHERE 1=1"; // ใช้ 1=1 เพื่อให้ต่อ AND ง่าย 

$params = [];
$types = "";

// 2. ถ้ามีการค้นหาชื่อลูกค้า
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $sql .= " AND u.fullname LIKE ?";
    $params[] = "%" . $_GET['search'] . "%";
    $types .= "s";
}

// 3. ถ้ามีการเลือกสถานะ
if (isset($_GET['status']) && !empty($_GET['status'])) {
    $sql .= " AND o.status = ?";
    $params[] = $_GET['status'];
    $types .= "s";
}

// 4. เรียงลำดับล่าสุดขึ้นก่อน
$sql .= " ORDER BY o.order_date DESC";

// 5. Execute Query
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
    <title>Admin Dashboard - จัดการคำสั่งซื้อ</title>
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
            font-family: 'Sarabun', sans-serif; 
        }

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

        .stat-card { 
            border: none; 
            border-radius: 12px; 
            transition: 0.3s; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.05); 
        }

        .stat-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 8px 15px rgba(0,0,0,0.1); 
        }

        .table-card { 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
            border: none; 
        }

        .status-badge { 
            padding: 5px 12px; 
            border-radius: 20px; 
            font-size: 0.85rem; 
            font-weight: 500; 
        }
        
        .btn-update {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        .btn-update:hover {
            background-color: var(--primary-color);
            color: white;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-4 d-none d-md-block">
            <h4 class="fw-bold mb-4 text-center"><i class="fas fa-campground me-2"></i>ADMIN</h4>
            
            <a href="admin_dashboard.php" class="active d-flex justify-content-between align-items-center">
                <span><i class="fas fa-tachometer-alt me-2"></i> Dashboard</span>
                
                <?php if($pendingOrders > 0): ?>
                    <span class="badge bg-danger rounded-pill shadow-sm">
                        <?php echo $pendingOrders; ?>
                    </span>
                <?php endif; ?>
            </a>
            <a href="admin_product_list.php"><i class="fas fa-box me-2"></i> จัดการสินค้า</a>
            <a href="admin_customer_list.php"><i class="fas fa-users me-2"></i> ลูกค้า</a>
            <hr>
            <a href="../index.php" target="_blank"><i class="fas fa-external-link-alt me-2"></i> ไปหน้าร้าน</a>
            <a href="../logout.php" class="text-danger mt-3"><i class="fas fa-sign-out-alt me-2"></i> ออกจากระบบ</a>
        </div>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">Dashboard</h2>
                <div class="user-profile d-flex align-items-center gap-2">
                    <span class="text-muted">สวัสดี, <?php echo $_SESSION['username']; ?></span>
                    <div class="bg-secondary rounded-circle" style="width: 35px; height: 35px;"></div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card stat-card bg-primary text-white p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="opacity-75">ยอดขายรวม</h6>
                                <h3 class="fw-bold">฿<?php echo number_format($totalRevenue); ?></h3>
                            </div>
                            <i class="fas fa-wallet fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card bg-danger text-white p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="opacity-75">รายการยกเลิก</h6> <h3 class="fw-bold"><?php echo $cancelledOrders; ?></h3> </div>
                            <i class="fas fa-times-circle fa-2x opacity-50"></i> </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card bg-warning text-white p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="opacity-75">รอตรวจสอบ</h6>
                                <h3 class="fw-bold"><?php echo $pendingOrders; ?></h3>
                            </div>
                            <i class="fas fa-clock fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card bg-success text-white p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="opacity-75">ชำระแล้ว/ส่งแล้ว</h6>
                                <h3 class="fw-bold"><?php echo $paidOrders; ?></h3>
                            </div>
                            <i class="fas fa-check-circle fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card table-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h5 class="fw-bold mb-0">รายการคำสั่งซื้อล่าสุด</h5>
                </div>

                <form method="GET" action="admin_dashboard.php" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="ค้นหาชื่อลูกค้า..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">-- ทุกสถานะ --</option>
                                <option value="pending" <?php if(isset($_GET['status']) && $_GET['status'] == 'pending') echo 'selected'; ?>>รอชำระเงิน (Pending)</option>
                                <option value="paid" <?php if(isset($_GET['status']) && $_GET['status'] == 'paid') echo 'selected'; ?>>ชำระแล้ว (Paid)</option>
                                <option value="shipped" <?php if(isset($_GET['status']) && $_GET['status'] == 'shipped') echo 'selected'; ?>>จัดส่งแล้ว (Shipped)</option>
                                <option value="cancelled" <?php if(isset($_GET['status']) && $_GET['status'] == 'cancelled') echo 'selected'; ?>>ยกเลิก (Cancelled)</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">กรองข้อมูล</button>
                        </div>
                        <div class="col-md-2">
                            <a href="admin_dashboard.php" class="btn btn-outline-secondary w-100">ล้างค่า</a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>ลูกค้า</th>
                                <th>วันที่สั่งซื้อ</th>
                                <th>ยอดรวม</th>
                                <th>สถานะ</th>
                                <th>หลักฐานโอน</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td>#<?php echo str_pad($row['order_id'], 5, '0', STR_PAD_LEFT); ?></td>
                                        <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($row['order_date'])); ?></td>
                                        <td class="fw-bold">฿<?php echo number_format($row['total_amount']); ?></td>
                                        <td>
                                            <?php 
                                            $status_class = match($row['status']) {
                                                'pending' => 'bg-warning text-dark',
                                                'paid' => 'bg-success text-white',
                                                'shipped' => 'bg-primary text-white',
                                                'cancelled' => 'bg-danger text-white',
                                                default => 'bg-light text-dark'
                                            };
                                            $status_text = match($row['status']) {
                                                'pending' => 'รอชำระ/ตรวจสอบ',
                                                'paid' => 'ชำระแล้ว',
                                                'shipped' => 'จัดส่งแล้ว',
                                                'cancelled' => 'ยกเลิก',
                                                default => $row['status']
                                            };
                                            ?>
                                            <span class="badge status-badge <?php echo $status_class; ?>">
                                                <?php echo $status_text; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if(!empty($row['payment_slip_url'])): ?>
                                                <a href="../uploads/slips/<?php echo $row['payment_slip_url']; ?>" target="_blank" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-image"></i> ดูสลิป
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="admin_order_update.php?id=<?php echo $row['order_id']; ?>" class="btn btn-sm btn-primary rounded-pill px-3">
                                                <i class="fas fa-edit"></i> อัปเดต
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i><br>
                                        ไม่พบข้อมูลคำสั่งซื้อ
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

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>