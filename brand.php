<?php
session_start();
require_once 'config/db.php';

//รับค่า Brand ID
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$brand_id = $_GET['id'];

//ดึงข้อมูลแบรนด์
$sql_brand = "SELECT * FROM brands WHERE brand_id = ?";
$stmt = $conn->prepare($sql_brand);
$stmt->bind_param("i", $brand_id);
$stmt->execute();
$brand = $stmt->get_result()->fetch_assoc();
if (!$brand) {
    header("Location: index.php");
    exit();
}

//ดึงสินค้าทั้งหมดของแบรนด์
$sql_products = "SELECT * FROM products WHERE brand_id = ?";
$stmt = $conn->prepare($sql_products);
$stmt->bind_param("i", $brand_id);
$stmt->execute();
$result_products = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $brand['brand_name']; ?> - Camping Gear Store</title>
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
        
        .brand-banner {
            background-color: var(--primary-color);
            color: var(--bg-color);
            padding: 3rem 0;
            text-align: center;
        }
        .product-card {
            border: none; border-radius: 12px; background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s;
            height: 100%; overflow: hidden;
        }
        .product-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 10px 25px rgba(64, 81, 59, 0.2); 
        }
        .product-img-wrapper { 
            height: 250px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding: 20px; 
        }
        .product-card img { 
            max-height: 100%; 
            max-width: 100%; 
            object-fit: contain; 
        }
        .btn-outline-custom { 
            border: 2px solid var(--primary-color); 
            color: var(--primary-color); 
            border-radius: 50px; 
            width: 100%; 
            font-weight: 600; 
        }
        .btn-outline-custom:hover { 
            background-color: var(--primary-color); 
            color: white; 
        }
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

    <header class="brand-banner">
        <div class="container">
            <h1 class="display-4 fw-bold text-uppercase"><?php echo $brand['brand_name']; ?></h1>
            <p class="lead opacity-75 mx-auto" style="max-width: 700px;"><?php echo $brand['description']; ?></p>
            <?php if (!empty($brand['website_url'])): ?>
                <a href="<?php echo $brand['website_url']; ?>" target="_blank" class="btn btn-outline-light btn-sm rounded-pill mt-2">Official Website <i class="fas fa-external-link-alt ms-1"></i></a>
            <?php endif; ?>
        </div>
    </header>

    <div class="container my-5">
        <div class="row g-4">
            <?php if ($result_products->num_rows > 0): ?>
                <?php while($row = $result_products->fetch_assoc()): ?>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="product-card">
                            <div class="product-img-wrapper">
                                <img src="uploads/<?php echo $row['image_url']; ?>" 
                                     onerror="this.src='https://via.placeholder.com/300?text=No+Image'" 
                                     alt="<?php echo $row['product_name']; ?>">
                            </div>
                            <div class="card-body d-flex flex-column p-4">
                                <h5 class="fw-bold text-truncate"><?php echo $row['product_name']; ?></h5>
                                <p class="text-muted small mb-3 text-truncate"><?php echo $row['description']; ?></p>
                                <div class="mt-auto">
                                    <h5 class="text-success fw-bold mb-3">฿<?php echo number_format($row['price'], 0); ?></h5>
                                    <a href="product_detail.php?id=<?php echo $row['product_id']; ?>" class="btn btn-outline-custom">ดูรายละเอียด</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <h3 class="text-muted">ยังไม่มีสินค้าในแบรนด์นี้</h3>
                    <a href="index.php" class="btn btn-secondary mt-3">กลับหน้าหลัก</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

   <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>