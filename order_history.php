<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

//ดึงข้อมูลออเดอร์ทั้งหมดของ User
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ประวัติการสั่งซื้อ - Camping Gear Store</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="uploads/camp_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { 
            background-color: #f8f9fa; 
            font-family: 'Sarabun', sans-serif; 
        }
        .navbar-custom { 
            background-color: #40513B; 
        }
        .navbar-brand { 
            color: #E5D9B6 !important; 
            font-weight: bold; 
        }
        .nav-link { 
            color: #e0e0e0 !important; 
        }
        
        .status-pending { 
            background-color: #f39c12; 
            color: white; 
        }
        .status-paid { 
            background-color: #3498db; 
            color: white; 
        }
        .status-shipped { 
            background-color: #27ae60; 
            color: white; 
        }
        .status-cancelled { 
            background-color: #c0392b; 
            color: white; 
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fas fa-campground me-2"></i>CAMPING STORE</a>
            <div class="d-flex ms-auto">
                <a href="index.php" class="nav-link">เลือกซื้อสินค้าต่อ</a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h2 class="mb-4 fw-bold" style="color: #40513B;"><i class="fas fa-history me-2"></i> ประวัติการสั่งซื้อ</h2>

        <?php if ($result->num_rows > 0): ?>
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3 ps-4">เลขที่คำสั่งซื้อ</th>
                                    <th>วันที่สั่งซื้อ</th>
                                    <th>ยอดรวม</th>
                                    <th>สถานะ</th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($order = $result->fetch_assoc()): 
                                    $status_class = "bg-secondary";
                                    $status_text = "รอตรวจสอบ";
                                    
                                    switch($order['status']) {
                                        case 'pending': $status_class = "status-pending"; $status_text = "รอตรวจสอบ"; break;
                                        case 'paid': $status_class = "status-paid"; $status_text = "ชำระเงินแล้ว"; break;
                                        case 'shipped': $status_class = "status-shipped"; $status_text = "จัดส่งแล้ว"; break;
                                        case 'cancelled': $status_class = "status-cancelled"; $status_text = "ยกเลิก"; break;
                                    }
                                ?>
                                <tr>
                                    <td class="ps-4 fw-bold">#ORD-<?php echo str_pad($order['order_id'], 5, '0', STR_PAD_LEFT); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></td>
                                    <td class="fw-bold text-success">฿<?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td>
                                        <span class="badge rounded-pill <?php echo $status_class; ?>">
                                            <?php echo $status_text; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="order_details.php?id=<?php echo $order['order_id']; ?>" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                                            ดูรายละเอียด
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">คุณยังไม่มีประวัติการสั่งซื้อ</h4>
                <a href="index.php" class="btn btn-success mt-3 rounded-pill">ไปช้อปปิ้งกันเลย!</a>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>