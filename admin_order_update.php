<?php
session_start();
require_once '../config/db.php';

//เช็คสิทธิ์ Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php");
    exit();
}
$order_id = $_GET['id'];

//อัปเดตสถานะ
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_status = $_POST['status'];
    $tracking = $_POST['tracking_number'];
    
    $sql_update = "UPDATE orders SET status = ?, tracking_number = ? WHERE order_id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("ssi", $new_status, $tracking, $order_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('บันทึกข้อมูลเรียบร้อย!'); window.location.href='admin_dashboard.php';</script>";
    }
}

//ดึงข้อมูล
$sql = "SELECT o.*, u.fullname, u.phone, u.email FROM orders o JOIN users u ON o.user_id = u.user_id WHERE o.order_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

$sql_items = "SELECT oi.*, p.product_name, p.image_url FROM orders_item oi JOIN products p ON oi.product_id = p.product_id WHERE oi.order_id = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items = $stmt_items->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>อัปเดตสถานะออเดอร์ - Admin</title>
    <link rel="icon" type="image/png" href="../uploads/camp_icon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #40513B;
            --secondary-color: #628141;
            --bg-color: #E5D9B6;
            --accent-color: #E67E22;
            --text-color: #333333;
        }
        body { 
            background-color: #f0efe9; 
            font-family: 'Sarabun', sans-serif; 
            color: var(--text-color); 
        }
        
        .card { 
            border: none; 
            border-radius: 12px; 
            box-shadow: 0 5px 15px rgba(64, 81, 59, 0.1); 
            overflow: hidden; 
        }
        .card-header-custom { 
            background-color: var(--primary-color); 
            color: var(--bg-color); 
            padding: 15px 20px; 
        }
        .btn-back { 
            color: var(--bg-color); 
            border: 1px solid var(--bg-color); 
            border-radius: 50px; 
        }
        .btn-back:hover { 
            background-color: var(--bg-color); 
            color: var(--primary-color); 
        }
        
        .btn-save {
            background-color: var(--accent-color);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 10px 30px;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-save:hover { 
            background-color: #d35400; 
            transform: translateY(-2px); 
        }
        
        .table thead { 
            background-color: #e9ecef; 
        }
        .section-title { 
            color: var(--primary-color); 
            font-weight: bold;
            border-bottom: 2px solid var(--secondary-color); 
            padding-bottom: 10px; 
            margin-bottom: 20px; 
        }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-tasks me-2"></i> จัดการคำสั่งซื้อ #ORD-<?php echo str_pad($order_id, 5, '0', STR_PAD_LEFT); ?></h5>
                    <a href="admin_dashboard.php" class="btn btn-sm btn-back"><i class="fas fa-arrow-left me-1"></i> กลับ</a>
                </div>
                <div class="card-body p-4">
                    
                    <form action="" method="POST" class="p-4 mb-5 bg-light rounded shadow-sm border">
                        <h6 class="fw-bold mb-3" style="color: var(--primary-color);">ดำเนินการ</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">สถานะปัจจุบัน</label>
                                <select name="status" class="form-select border-success">
                                    <option value="pending" <?php if($order['status']=='pending') echo 'selected'; ?>>Pending (รอตรวจสอบ)</option>
                                    <option value="paid" <?php if($order['status']=='paid') echo 'selected'; ?>>Paid (ชำระเงินแล้ว)</option>
                                    <option value="shipped" <?php if($order['status']=='shipped') echo 'selected'; ?>>Shipped (จัดส่งแล้ว)</option>
                                    <option value="cancelled" <?php if($order['status']=='cancelled') echo 'selected'; ?>>Cancelled (ยกเลิก)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">เลขพัสดุ (Tracking Number)</label>
                                <input type="text" name="tracking_number" class="form-control" value="<?php echo $order['tracking_number']; ?>" placeholder="เช่น KERRY12345">
                            </div>
                            <div class="col-12 text-end mt-4">
                                <button type="submit" class="btn btn-save"><i class="fas fa-save me-2"></i> บันทึกการเปลี่ยนแปลง</button>
                            </div>
                        </div>
                    </form>

                    <div class="row mb-5">
                        <div class="col-md-6 border-end">
                            <h6 class="section-title">หลักฐานการโอนเงิน</h6>
                            <?php if(!empty($order['payment_slip_url'])): ?>
                                <a href="../uploads/slips/<?php echo $order['payment_slip_url']; ?>" target="_blank">
                                    <img src="../uploads/slips/<?php echo $order['payment_slip_url']; ?>" class="img-fluid rounded border shadow-sm" style="max-height: 300px;">
                                </a>
                                <div class="mt-2 text-center small text-muted"><i class="fas fa-search-plus"></i> คลิกเพื่อดูรูปใหญ่</div>
                            <?php else: ?>
                                <div class="alert alert-danger text-center"><i class="fas fa-exclamation-circle"></i> ยังไม่มีการแนบสลิป</div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-6 ps-md-4">
                            <h6 class="section-title">ข้อมูลจัดส่ง & ลูกค้า</h6>
                            <div class="mb-3">
                                <strong class="text-muted small">ที่อยู่จัดส่ง:</strong>
                                <p class="mb-0 bg-light p-2 rounded"><?php echo nl2br($order['shipping_address']); ?></p>
                            </div>
                            <ul class="list-unstyled">
                                <li class="mb-2"><strong class="text-muted small">ชื่อลูกค้า:</strong> <?php echo $order['fullname']; ?></li>
                                <li class="mb-2"><strong class="text-muted small">เบอร์โทร:</strong> <?php echo $order['phone']; ?></li>
                                <li><strong class="text-muted small">Email:</strong> <?php echo $order['email']; ?></li>
                            </ul>
                        </div>
                    </div>

                    <h6 class="section-title">รายการสินค้า</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50%;">สินค้า</th>
                                    <th class="text-center">ราคา</th>
                                    <th class="text-center">จำนวน</th>
                                    <th class="text-end">รวม</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($item = $items->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="../uploads/<?php echo $item['image_url']; ?>" onerror="this.src='https://via.placeholder.com/50'" width="50" class="rounded me-3 border">
                                            <span><?php echo $item['product_name']; ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center">฿<?php echo number_format($item['price_at_purchase']); ?></td>
                                    <td class="text-center"><?php echo $item['quantity']; ?></td>
                                    <td class="text-end">฿<?php echo number_format($item['price_at_purchase'] * $item['quantity']); ?></td>
                                </tr>
                                <?php endwhile; ?>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">ยอดสุทธิ</td>
                                    <td class="text-end fw-bold fs-5" style="color: var(--accent-color);">฿<?php echo number_format($order['total_amount']); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>