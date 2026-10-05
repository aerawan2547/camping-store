<?php
session_start();
require_once 'config/db.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    
    $sql = "SELECT p.*, b.brand_name, c.category_name 
            FROM products p 
            LEFT JOIN brands b ON p.brand_id = b.brand_id 
            LEFT JOIN categories c ON p.category_id = c.category_id 
            WHERE p.product_id = ?"; 
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "ไม่พบสินค้านี้";
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['product_name']; ?> - Camping Gear Store</title>
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

        .breadcrumb-item a { 
            color: var(--primary-color); 
            text-decoration: none; 
        }
        .price-text { 
            color: var(--accent-color); 
            font-size: 2rem; 
            font-weight: bold; 
        }
        
        .btn-add-cart {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: bold;
            width: 100%;
            transition: 0.3s;
        }
        .btn-add-cart:hover { background-color: #2c3e2e; color: white; }
        
        .product-img-large {
            max-height: 500px;
            object-fit: contain;
            width: 100%;
            background: white;
            padding: 20px;
            border-radius: 10px;
        }
        .stock-badge { font-size: 0.9rem; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php"><i class="fas fa-campground me-2"></i>CAMPING STORE</a>
            <div class="d-flex ms-auto">
                <a href="index.php" class="nav-link">หน้าแรก</a>
                <a href="cart.php" class="nav-link ms-3"><i class="fas fa-shopping-cart"></i></a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">หน้าแรก</a></li>
                <li class="breadcrumb-item"><a href="#"><?php echo $product['category_name']; ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $product['product_name']; ?></li>
            </ol>
        </nav>

        <div class="row bg-white p-4 rounded shadow-sm">
            <div class="col-md-6 mb-4">
                <img src="uploads/<?php echo $product['image_url']; ?>" 
                     onerror="this.src='https://via.placeholder.com/500?text=No+Image'"
                     class="product-img-large shadow-sm" alt="<?php echo $product['product_name']; ?>">
            </div>

            <div class="col-md-6">
                <div class="d-flex justify-content-between align-items-start">
                    <span class="badge bg-secondary mb-2"><?php echo $product['brand_name']; ?></span>
                    <?php if ($product['stock_quantity'] > 0): ?>
                        <span class="badge bg-success stock-badge">มีสินค้า (<?php echo $product['stock_quantity']; ?>)</span>
                    <?php else: ?>
                        <span class="badge bg-danger stock-badge">สินค้าหมด</span>
                    <?php endif; ?>
                </div>

                <h1 class="fw-bold mb-3"><?php echo $product['product_name']; ?></h1>
                <p class="price-text mb-3">฿<?php echo number_format($product['price'], 0); ?></p>
                
                <hr>
                
                <p class="text-muted mb-4" style="white-space: pre-line;"><?php echo $product['description']; ?></p>

                <form action="cart_action.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="row align-items-end mb-4">
                        <div class="col-4">
                            <label class="form-label fw-bold">จำนวน</label>
                            <input type="number" name="quantity" class="form-control text-center" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>">
                        </div>
                        <div class="col-8">
                            <button type="submit" class="btn btn-add-cart" <?php echo ($product['stock_quantity'] <= 0) ? 'disabled' : ''; ?>>
                                <i class="fas fa-cart-plus me-2"></i> เพิ่มลงตะกร้า
                            </button>
                        </div>
                    </div>
                </form>

                <div class="alert alert-light border mt-4">
                    <small><i class="fas fa-truck me-2"></i> จัดส่งฟรีเมื่อซื้อครบ 5,000 บาท</small><br>
                    <small><i class="fas fa-shield-alt me-2"></i> รับประกันสินค้าของแท้ 100%</small>
                </div>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>