<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: order_history.php");
    exit();
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

//ดึงข้อมูลหัวบิล
$sql_order = "SELECT * FROM orders WHERE order_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql_order);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    echo "ไม่พบคำสั่งซื้อนี้";
    exit();
}

//ดึงรายการสินค้าในบิล (JOIN กับตาราง Products เอารูป+ชื่อ)
$sql_items = "SELECT oi.*, p.product_name, p.image_url 
              FROM orders_item oi 
              JOIN products p ON oi.product_id = p.product_id 
              WHERE oi.order_id = ?"; 
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$result_items = $stmt_items->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>รายละเอียดคำสั่งซื้อ #<?php echo $order_id; ?></title>
    <link rel="icon" type="image/png" href="uploads/camp_icon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { 
            background-color: #f8f9fa; 
            font-family: 'Sarabun', sans-serif; 
        }
        .card-header { 
            background-color: #40513B; 
            color: #fff; 
        }
    </style>
</head>
<body>

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold" style="color: #40513B;">
                <i class="fas fa-file-invoice me-2"></i> คำสั่งซื้อ #ORD-<?php echo str_pad($order_id, 5, '0', STR_PAD_LEFT); ?>
            </h3>
            <a href="order_history.php" class="btn btn-outline-secondary rounded-pill"><i class="fas fa-arrow-left me-2"></i> ย้อนกลับ</a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header">รายการสินค้า</div>
                    <div class="card-body p-0">
                        <table class="table align-middle mb-0">
                            <tbody>
                                <?php while ($item = $result_items->fetch_assoc()): ?>
                                <tr>
                                    <td class="ps-4" style="width: 80px;">
                                        <img src="uploads/<?php echo $item['image_url']; ?>" 
                                             onerror="this.src='https://via.placeholder.com/80?text=No+Image'"
                                             class="rounded border" width="60" height="60" style="object-fit: cover;">
                                    </td>
                                    <td>
                                        <h6 class="mb-0 fw-bold"><?php echo $item['product_name']; ?></h6>
                                        <small class="text-muted">ราคาต่อชิ้น: ฿<?php echo number_format($item['price_at_purchase']); ?></small>
                                    </td>
                                    <td class="text-center">x <?php echo $item['quantity']; ?></td>
                                    <td class="text-end pe-4 fw-bold">฿<?php echo number_format($item['price_at_purchase'] * $item['quantity']); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold pt-3">ยอดรวมทั้งสิ้น</td>
                                    <td class="text-end pe-4 fw-bold text-success fs-5 pt-3">฿<?php echo number_format($order['total_amount'], 2); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-header">ข้อมูลการจัดส่ง</div>
                    <div class="card-body">
                        <p class="mb-1"><strong>ที่อยู่จัดส่ง:</strong></p>
                        <p class="text-muted mb-3" style="white-space: pre-line;"><?php echo isset($order['shipping_address']) ? $order['shipping_address'] : (isset($order['address']) ? $order['address'] : 'ไม่พบข้อมูลที่อยู่'); ?></p>
                        
                        <p class="mb-1"><strong>วันที่สั่งซื้อ:</strong></p>
                        <p class="text-muted mb-3"><?php echo date('d/m/Y H:i', strtotime($order['order_date'])); ?></p>

                        <p class="mb-1"><strong>สถานะปัจจุบัน:</strong></p>
                        <?php
                            $status_color = 'secondary';
                            $status_txt = 'รอตรวจสอบ';
                            if($order['status'] == 'paid') { $status_color = 'primary'; $status_txt = 'ชำระเงินแล้ว'; }
                            if($order['status'] == 'shipped') { $status_color = 'success'; $status_txt = 'จัดส่งแล้ว'; }
                            if($order['status'] == 'cancelled') { $status_color = 'danger'; $status_txt = 'ยกเลิก'; }
                        ?>
                        <span class="badge bg-<?php echo $status_color; ?> fs-6"><?php echo $status_txt; ?></span>

                        <?php if(!empty($order['tracking_number'])): ?>
                            <div class="alert alert-success mt-3 mb-0">
                                <small class="fw-bold">Tracking Number:</small><br>
                                <?php echo $order['tracking_number']; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($order['status'] == 'pending'): ?>
                    <div class="alert alert-warning border-0 shadow-sm">
                        <i class="fas fa-clock me-2"></i> อยู่ระหว่างตรวจสอบการชำระเงิน
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($order['payment_slip_url'])): ?>
                    <div class="d-grid">
                        <a href="uploads/slips/<?php echo $order['payment_slip_url']; ?>" target="_blank" class="btn btn-outline-info">
                            <i class="fas fa-receipt me-2"></i> ดูหลักฐานการโอนเงิน
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</body>
</html>