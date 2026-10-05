<?php
session_start();
require_once 'config/db.php';

// 1. เช็คล็อกอิน
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. เช็คของในตะกร้า
if (empty($_SESSION['cart'])) {
    header("Location: index.php");
    exit();
}

// 3. ดึงข้อมูลผู้ใช้จาก Database 
$user_id = $_SESSION['user_id'];
$sql_user = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql_user);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// 4. คำนวณราคาสินค้าในตะกร้า (Subtotal)
$ids = implode(',', array_keys($_SESSION['cart']));
$sql = "SELECT * FROM products WHERE product_id IN ($ids)";
$result = $conn->query($sql);

$total_price = 0; // ราคาสินค้าไม่รวมค่าส่ง
$cart_items = []; // เก็บข้อมูลสินค้าไว้แสดงผล

while($row = $result->fetch_assoc()) {
    $qty = $_SESSION['cart'][$row['product_id']];
    $row['qty'] = $qty;
    $row['subtotal'] = $row['price'] * $qty;
    $total_price += $row['subtotal'];
    $cart_items[] = $row;
}

// 5. ตรรกะคำนวณค่าส่ง (Shipping Logic)
$shipping_cost = 50; // ค่าส่งเริ่มต้น
$is_free_shipping = false;

if ($total_price >= 5000) {
    $shipping_cost = 0;
    $is_free_shipping = true;
}

// ราคาสุทธิ (Grand Total)
$grand_total = $total_price + $shipping_cost;
?>


<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ชำระเงิน - Camping Gear Store</title>
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
            color: #E5D9B6; 
            font-weight: bold; 
        }
        .btn-confirm { 
            background-color: #E67E22; 
            color: white; 
            width: 100%; 
            border-radius: 50px;
            font-weight: bold;
            border: none;
        }
        .btn-confirm:hover { 
            background-color: #d35400; 
            color: white; 
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h2 class="mb-4 text-center fw-bold" style="color: #40513B;">
            <i class="fas fa-file-invoice-dollar me-2"></i>ยืนยันคำสั่งซื้อ
        </h2>
        
        <form action="save_order.php" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-7 mb-4">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header">
                            <i class="fas fa-map-marker-alt me-2"></i>ที่อยู่จัดส่งสินค้า
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3">
                                <label class="form-label">ชื่อ-นามสกุล ผู้รับ</label>
                                <input type="text" name="fullname" class="form-control" required 
                                       value="<?php echo htmlspecialchars($user['fullname']); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">เบอร์โทรศัพท์</label>
                                <input type="text" name="phone" class="form-control" required 
                                       value="<?php echo htmlspecialchars($user['phone']); ?>">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">ที่อยู่จัดส่ง</label>
                                <textarea name="address" class="form-control" rows="3" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <label class="form-label fw-bold">รูปแบบการจัดส่ง</label>
                                
                                <?php if ($is_free_shipping): ?>
                                    <div class="alert alert-success d-flex align-items-center border-0 bg-success-subtle text-success-emphasis">
                                        <i class="fas fa-gift fs-3 me-3"></i>
                                        <div>
                                            <strong><i class="fas fa-truck-fast"></i> จัดส่งฟรี (Free Shipping)</strong><br>
                                            <small>เนื่องจากมียอดสั่งซื้อครบ 5,000 บาท</small>
                                        </div>
                                    </div>
                                    <input type="hidden" name="courier" value="Free Shipping">
                                    <input type="hidden" name="shipping_cost" value="0">
                                
                                <?php else: ?>
                                    <select name="courier" class="form-select mb-2">
                                        <option value="Kerry Express">Kerry Express (มาตรฐาน)</option>
                                        <option value="Flash Express">Flash Express</option>
                                        <option value="Thailand Post">ไปรษณีย์ไทย EMS</option>
                                    </select>
                                    <div class="alert alert-warning d-flex align-items-center py-2">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <span>ค่าจัดส่งมาตรฐาน <strong>50 บาท</strong> (ซื้อครบ 5,000 ส่งฟรี)</span>
                                    </div>
                                    <input type="hidden" name="shipping_cost" value="50">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <i class="fas fa-money-bill-wave me-2"></i>แจ้งชำระเงิน
                        </div>
                        <div class="card-body p-4">
                            <div class="alert alert-info border-0">
                                <h6 class="fw-bold"><i class="fas fa-university me-2"></i>ธนาคารกสิกรไทย (KBANK)</h6>
                                <p class="mb-0">เลขบัญชี: <strong>123-4-56789-0</strong></p>
                                <p class="mb-0">ชื่อบัญชี: <strong>Camping Gear Store</strong></p>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">อัปโหลดสลิปโอนเงิน <span class="text-danger">*</span></label>
                                <input type="file" name="payment_slip" class="form-control" accept="image/*" required>
                                <div class="form-text">รองรับไฟล์ .jpg, .png, .jpeg</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <i class="fas fa-shopping-basket me-2"></i>สรุปรายการสินค้า
                        </div>
                        <div class="card-body p-4">
                            <ul class="list-group list-group-flush mb-3">
                                <?php foreach($cart_items as $item): ?>
                                <li class="list-group-item d-flex justify-content-between lh-sm px-0">
                                    <div>
                                        <h6 class="my-0 small fw-bold"><?php echo $item['product_name']; ?></h6>
                                        <small class="text-muted">x <?php echo $item['qty']; ?> ชิ้น</small>
                                    </div>
                                    <span class="text-muted">฿<?php echo number_format($item['subtotal']); ?></span>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>ค่าสินค้า</span>
                                <span>฿<?php echo number_format($total_price, 2); ?></span>
                            </div>
                            
                            <div class="d-flex justify-content-between mb-3 text-muted">
                                <span>ค่าจัดส่ง</span>
                                <?php if($is_free_shipping): ?>
                                    <span class="text-success fw-bold">ฟรี (0.00)</span>
                                <?php else: ?>
                                    <span>+ ฿<?php echo number_format($shipping_cost, 2); ?></span>
                                <?php endif; ?>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between fw-bold fs-4 text-success pt-2">
                                <span>ยอดสุทธิ</span>
                                <span>฿<?php echo number_format($grand_total, 2); ?></span>
                            </div>
                            
                            <input type="hidden" name="total_amount" value="<?php echo $grand_total; ?>">
                            
                            <button type="submit" class="btn btn-confirm mt-4">
                                <i class="fas fa-check-circle me-1"></i> ยืนยันการสั่งซื้อ
                            </button>
                            <a href="cart.php" class="btn btn-outline-secondary w-100 mt-2 rounded-pill">
                                กลับไปแก้ไขตะกร้า
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>