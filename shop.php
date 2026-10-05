<?php
session_start();
require_once 'config/db.php';

$where_clauses = ["1=1"];
$params = [];
$types = "";

//กรองตามหมวด
if (isset($_GET['cat_id']) && !empty($_GET['cat_id'])) {
    $where_clauses[] = "p.category_id = ?";
    $params[] = $_GET['cat_id'];
    $types .= "i";
}

//กรองตามแบรนด์
    if (isset($_GET['brand_id']) && !empty($_GET['brand_id'])) { 
    $where_clauses[] = "p.brand_id = ?";
    $params[] = $_GET['brand_id'];
    $types .= "i";
}

//ค้นหาจากชื่อสินค้า
if (isset($_GET['q']) && !empty($_GET['q'])) {
    $where_clauses[] = "p.product_name LIKE ?";
    $params[] = "%" . $_GET['q'] . "%";
    $types .= "s";
}

//กรองราคา
if (isset($_GET['min_price']) && is_numeric($_GET['min_price'])) {
    $where_clauses[] = "p.price >= ?";
    $params[] = $_GET['min_price'];
    $types .= "d";
}
if (isset($_GET['max_price']) && is_numeric($_GET['max_price'])) {
    $where_clauses[] = "p.price <= ?";
    $params[] = $_GET['max_price'];
    $types .= "d";
}

$sql = "SELECT p.*, c.category_name, b.brand_name 
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id
        LEFT JOIN brands b ON p.brand_id = b.brand_id
        WHERE " . implode(" AND ", $where_clauses);

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
$categories = $conn->query("SELECT * FROM categories");
$brands = $conn->query("SELECT * FROM brands");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>สินค้าทั้งหมด - Camping Gear Store</title>
    <link rel="icon" type="image/png" href="uploads/camp_icon.png">
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
        font-family: 'Sarabun', sans-serif; 
        background-color: #f8f9fa; 
        }
        .navbar-custom { 
        background-color: var(--primary-color); 
        }
        
        .filter-sidebar { 
        background: white; 
        padding: 20px; 
        border-radius: 10px; 
        box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
        }
        .filter-title { 
        font-weight: bold; 
        color: var(--primary-color); 
        margin-bottom: 15px; 
        border-bottom: 2px solid var(--secondary-color); 
        padding-bottom: 5px; 
        }
        .category-link { 
        display: block; 
        padding: 8px 0; color: #555; 
        text-decoration: none; 
        transition: 0.2s; 
        border-bottom: 1px solid #eee; 
        }
        .category-link:hover, .category-link.active { 
        color: var(--accent-color); 
        padding-left: 5px; 
        font-weight: bold; 
        }
        
        .product-card { 
        border: none; 
        transition: 0.3s; 
        height: 100%; 
        background: white; 
        border-radius: 10px; 
        overflow: hidden; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
        }
        .product-card:hover { 
        transform: translateY(-5px); 
        box-shadow: 0 10px 20px rgba(0,0,0,0.1); 
        }
        .product-img { 
        height: 200px; 
        object-fit: contain; 
        padding: 20px; 
        }
        
        .btn-filter { 
        background-color: var(--primary-color); 
        color: white; 
        width: 100%; 
        border-radius: 50px; 
        }
        .btn-filter:hover { 
        background-color: var(--secondary-color); 
        color: white; 
        }

        .category-scroll-box {
            max-height: 220px;
            overflow-y: auto;  
            padding-right: 5px;
            position: relative;
        }

        .category-scroll-box::-webkit-scrollbar {
            width: 6px;
        }
        .category-scroll-box::-webkit-scrollbar-track {
            background: #f1f1f1; 
            border-radius: 10px;
        }
        .category-scroll-box::-webkit-scrollbar-thumb {
            background: var(--secondary-color);
            border-radius: 10px;
        }
        .category-scroll-box::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="index.php"><i class="fas fa-campground me-2"></i>CAMPING STORE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarNav">
                
                <form class="d-flex mx-auto" action="shop.php" method="GET" style="width: 50%; max-width: 600px;">
                    <div class="input-group">
                        <input class="form-control rounded-start-pill border-0" type="search" name="q" placeholder="ค้นหาอุปกรณ์แคมป์ปิ้ง..." value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">
                        <button class="btn btn-warning rounded-end-pill" type="submit"><i class="fas fa-search text-white"></i></button>
                    </div>
                </form>

                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link text-white" href="index.php">หน้าแรก</a></li>
                    <li class="nav-item"><a class="nav-link text-white active fw-bold" href="shop.php">สินค้าทั้งหมด</a></li>
                    <li class="nav-item ms-2">
                        <a href="cart.php" class="position-relative text-white fs-5">
                            <i class="fas fa-shopping-cart"></i>
                            <?php 
                                $cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
                                if($cart_count > 0) echo '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">'.$cart_count.'</span>';
                            ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="filter-sidebar sticky-top" style="top: 100px; z-index: 1;">
                    <form action="shop.php" method="GET">
                        <input type="hidden" name="q" value="<?php echo isset($_GET['q']) ? htmlspecialchars($_GET['q']) : ''; ?>">

                        <div class="mb-4">
                            <h5 class="filter-title">หมวดหมู่สินค้า</h5>
                            
                            <div class="category-scroll-box">
                                <a href="shop.php" class="category-link <?php echo !isset($_GET['cat_id']) ? 'active' : ''; ?>">ทั้งหมด</a>
                                
                                <?php 
                                $categories->data_seek(0); 
                                while($cat = $categories->fetch_assoc()): 
                                ?>
                                    <a href="shop.php?cat_id=<?php echo $cat['category_id']; ?>" 
                                       class="category-link <?php echo (isset($_GET['cat_id']) && $_GET['cat_id'] == $cat['category_id']) ? 'active' : ''; ?>">
                                        <?php echo $cat['category_name']; ?>
                                    </a>
                                <?php endwhile; ?>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="filter-title">แบรนด์</h5>
                            <select name="brand_id" class="form-select mb-2">
                                <option value="">ทุกแบรนด์</option>
                                <?php while($b = $brands->fetch_assoc()): ?>
                                    <option value="<?php echo $b['brand_id']; ?>" <?php echo (isset($_GET['brand_id']) && $_GET['brand_id'] == $b['brand_id']) ? 'selected' : ''; ?>>
                                        <?php echo $b['brand_name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <h5 class="filter-title">ช่วงราคา</h5>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <input type="number" name="min_price" class="form-control form-control-sm" placeholder="ต่ำสุด" value="<?php echo isset($_GET['min_price']) ? $_GET['min_price'] : ''; ?>">
                                <span>-</span>
                                <input type="number" name="max_price" class="form-control form-control-sm" placeholder="สูงสุด" value="<?php echo isset($_GET['max_price']) ? $_GET['max_price'] : ''; ?>">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-filter">กรองสินค้า</button>
                        <a href="shop.php" class="btn btn-outline-secondary w-100 mt-2 rounded-pill">ล้างค่ากรอง</a>
                    </form>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold" style="color: var(--primary-color);">
                        <?php 
                            if(isset($_GET['q']) && !empty($_GET['q'])) echo 'ผลการค้นหา: "' . htmlspecialchars($_GET['q']) . '"';
                            else echo 'สินค้าทั้งหมด';
                        ?>
                    </h4>
                    <span class="text-muted">พบ <?php echo $result->num_rows; ?> รายการ</span>
                </div>

                <div class="row g-4">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="product-card h-100">
                                    
                                    <div class="text-center bg-white">
                                        <a href="product_detail.php?id=<?php echo $row['product_id']; ?>">
                                            <img src="uploads/<?php echo $row['image_url']; ?>" class="product-img img-fluid" 
                                                onerror="this.src='https://via.placeholder.com/300'" 
                                                alt="<?php echo $row['product_name']; ?>">
                                        </a>
                                    </div>
                                    <div class="p-3 d-flex flex-column h-100">
                                        <small class="text-muted"><?php echo $row['brand_name']; ?></small>
                                        
                                        <a href="product_detail.php?id=<?php echo $row['product_id']; ?>" class="text-decoration-none text-dark">
                                            <h6 class="fw-bold text-truncate"><?php echo $row['product_name']; ?></h6>
                                        </a>

                                        <div class="mt-auto d-flex justify-content-between align-items-center">
                                            <span class="text-success fw-bold fs-5">฿<?php echo number_format($row['price']); ?></span>
                                            
                                            <a href="product_detail.php?id=<?php echo $row['product_id']; ?>" class="btn btn-sm btn-outline-dark rounded-circle">
                                                <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h4>ไม่พบสินค้าที่คุณค้นหา</h4>
                            <p class="text-muted">ลองเปลี่ยนคำค้นหา หรือปรับตัวกรองราคา</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <script src="js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const scrollBox = document.querySelector('.category-scroll-box');
            const activeItem = scrollBox.querySelector('.active');

            if (scrollBox && activeItem) {
                const topPos = activeItem.offsetTop - scrollBox.offsetTop;
                scrollBox.scrollTop = topPos - (scrollBox.offsetHeight / 2) + (activeItem.offsetHeight / 2);
            }
        });
    </script>
</body>
</html>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>