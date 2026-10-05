<?php
session_start();
require_once 'config/db.php';

//ดึงข้อมูลสินค้าที่อยู่ในตะกร้ามาแสดง
$cart_products = array();
$total_price = 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $ids = implode(',', array_keys($_SESSION['cart']));
    
    $sql = "SELECT * FROM products WHERE product_id IN ($ids)";
    $result = $conn->query($sql);
    
    while($row = $result->fetch_assoc()) {
        $cart_products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตะกร้าสินค้า - Camping Gear Store</title>
    <link rel="icon" type="image/png" href="uploads/camp_icon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { 
            --primary-color: #40513B; 
            --accent-color: #E67E22; 
            --bg-color: #E5D9B6; 
        }
        body { 
            font-family: 'Sarabun', sans-serif; 
            background-color: #f8f9fa; 
            color: #333; 
        }
        .navbar-custom { 
            background-color: var(--primary-color); 
        }
        .navbar-brand { 
            color: var(--bg-color) !important; 
            font-weight: bold; 
        }
        .nav-link { 
            color: #e0e0e0 !important; 
        }
        .cart-header { 
            background-color: var(--bg-color); 
            color: var(--primary-color); 
            border: none; 
        }
        .btn-checkout {
            background-color: var(--accent-color); 
            color: white; 
            border: none;
            padding: 10px 30px; 
            border-radius: 50px; 
            font-weight: bold; 
            width: 100%; 
            transition: 0.3s;
        }
        .btn-checkout:hover { 
            background-color: #d35400; 
            color: white; 
        }
        .img-cart { 
            width: 80px; 
            height: 80px; 
            object-fit: contain; 
            border: 1px solid #ddd; 
            border-radius: 8px; 
            background: white; 
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
        <h2 class="mb-4 fw-bold text-center" style="color: var(--primary-color);">ตะกร้าสินค้าของคุณ</h2>

        <?php if (empty($cart_products)): ?>
            <div class="text-center py-5">
                <i class="fas fa-shopping-basket fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">ตะกร้าของคุณว่างเปล่า</h4>
                <a href="index.php" class="btn btn-outline-success mt-3 rounded-pill">ไปเลือกซื้อสินค้า</a>
            </div>
        <?php else: ?>
            
            <form action="cart_action.php" method="POST">
                <input type="hidden" name="action" value="update">
                
                <div class="row">
                    <div class="col-lg-8">
                        <div class="table-responsive shadow-sm bg-white rounded p-3">
                            <table class="table align-middle">
                                <thead class="cart-header">
                                    <tr>
                                        <th>สินค้า</th>
                                        <th class="text-center">ราคา</th>
                                        <th class="text-center">จำนวน</th>
                                        <th class="text-end">รวม</th>
                                        <th class="text-center">ลบ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart_products as $product): 
                                        $p_id = $product['product_id'];
                                        $qty = $_SESSION['cart'][$p_id];
                                        $line_total = $product['price'] * $qty;
                                        $total_price += $line_total;
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="uploads/<?php echo $product['image_url']; ?>" 
                                                     onerror="this.src='https://via.placeholder.com/80?text=No+Image'" class="img-cart me-3">
                                                <div>
                                                    <h6 class="mb-0 fw-bold"><?php echo $product['product_name']; ?></h6>
                                                    <small class="text-muted">สต็อก: <?php echo $product['stock_quantity']; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">฿<?php echo number_format($product['price']); ?></td>
                                        <td class="text-center" style="width: 120px;">
                                            <input type="number" name="amount[<?php echo $p_id; ?>]" 
                                                   value="<?php echo $qty; ?>" min="1" max="<?php echo $product['stock_quantity']; ?>" 
                                                   class="form-control text-center">
                                        </td>
                                        <td class="text-end fw-bold text-success">฿<?php echo number_format($line_total); ?></td>
                                        <td class="text-center">
                                            <a href="cart_action.php?action=delete&id=<?php echo $p_id; ?>" 
                                               class="text-danger" onclick="return confirm('ยืนยันการลบสินค้า?');">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-3">
                            <a href="cart_action.php?action=clear" class="btn btn-outline-danger btn-sm rounded-pill" onclick="return confirm('ต้องการล้างตะกร้าทั้งหมด?');">ล้างตะกร้า</a>
                            <button type="submit" class="btn btn-secondary btn-sm rounded-pill">คำนวณราคาใหม่ (Update Cart)</button>
                        </div>
                    </div>

                    <div class="col-lg-4 mt-4 mt-lg-0">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white fw-bold">สรุปคำสั่งซื้อ</div>
                            <div class="card-body">
                                <?php if ($total_price >= 5000): ?>
                                    <div class="alert alert-success small mb-3">
                                        <i class="fas fa-check-circle me-1"></i> ยอดครบ 5,000 บาท <b>จัดส่งฟรี!</b>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-warning small mb-3">
                                        <i class="fas fa-info-circle me-1"></i> ซื้อเพิ่มอีก <b>฿<?php echo number_format(5000 - $total_price); ?></b> เพื่อรับสิทธิ์ส่งฟรี
                                    </div>
                                <?php endif; ?>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>ยอดรวมสินค้า</span>
                                    <span>฿<?php echo number_format($total_price, 2); ?></span>
                                </div>
                                <div class="d-flex justify-content-between mb-3 text-muted">
                                    <span>ค่าจัดส่ง</span>
                                    <span>ฟรี</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-4">
                                    <span class="fw-bold fs-5">ยอดสุทธิ</span>
                                    <span class="fw-bold fs-5 text-success">฿<?php echo number_format($total_price, 2); ?></span>
                                </div>
                                
                                <?php if (isset($_SESSION['user_id'])): ?>
                                    <a href="checkout.php" class="btn btn-checkout">ดำเนินการสั่งซื้อ <i class="fas fa-arrow-right ms-2"></i></a>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-warning w-100 rounded-pill fw-bold">เข้าสู่ระบบเพื่อสั่งซื้อ</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
        <?php endif; ?>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>