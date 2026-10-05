<?php
session_start();
require_once 'config/db.php';

$sql_featured = "SELECT * FROM products WHERE is_featured = 1 LIMIT 4";
$result_featured = $conn->query($sql_featured);

$sql_categories = "SELECT * FROM categories LIMIT 6";
$result_categories = $conn->query($sql_categories);

$sql_brands = "SELECT * FROM brands";
$result_brands = $conn->query($sql_brands);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camping Gear Store - อุปกรณ์แคมป์ปิ้ง</title>
    <link rel="icon" type="image/png" href="uploads/camp_icon.png">
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
            font-family: 'Sarabun', sans-serif;
            background-color: #f8f9fa;
            color: var(--text-color);
        }

        .navbar-custom {
            background-color: var(--primary-color);
            padding: 1rem 0;
        }
        .navbar-brand {
            color: var(--bg-color) ;
            font-weight: 800;
            letter-spacing: 1px;
            font-size: 1.5rem;
        }
        .nav-link {
            color: #e0e0e0 ;
            font-weight: 500;
            margin-left: 10px;
        }
        .nav-link:hover {
            color: var(--accent-color) ;
        }

        .carousel-item {
            height: 600px; 
            background-color: #000;
            position: relative;
        }
        
        @media (max-width: 768px) {
            .carousel-item {
                height: 250px;
            }
            .carousel-caption h2 { font-size: 1.5rem; } 
            .carousel-caption p { display: none; } 
            .carousel-caption .btn { font-size: 0.8rem; padding: 5px 15px; }
        }

        .carousel-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center; 
            opacity: 0.9; 
        }
        .carousel-caption {
            background: rgba(0, 0, 0, 0.4);
            padding: 20px 40px;
            border-radius: 15px;
            bottom: 30%;
        }

        .section-title {
            color: var(--primary-color);
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
        }
        .section-title::after {
            content: "";
            display: block;
            width: 60px;
            height: 3px;
            background-color: var(--accent-color);
            margin: 10px auto 0;
        }

        .product-card {
            border: none;
            border-radius: 12px;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            height: 100%;
            overflow: hidden;
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
            background-color: #fff;
            padding: 20px;
        }
        .product-card img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }
        .card-body {
            padding: 2rem;
        }
        .product-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 5px;
            height: 50px; 
            overflow: hidden;
        }
        .product-price {
            color: var(--accent-color);
            font-size: 1.2rem;
            font-weight: bold;
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

        .cat-circle {
            width: 120px;
            height: 120px;
            background-color: var(--bg-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            transition: 0.3s;
            font-size: 2rem;
            color: var(--primary-color);
        }
        .cat-item:hover .cat-circle {
            background-color: var(--primary-color);
            color: var(--bg-color);
        }
        .cat-name {
            font-weight: bold;
            color: var(--primary-color);
        }

        .brand-logo {
            filter: grayscale(100%);
            opacity: 0.6;
            transition: 0.3s;
            max-height: 50px;
        }
        .brand-logo:hover {
            filter: grayscale(0%);
            opacity: 1;
        }

        footer {
            background-color: var(--primary-color);
            color: var(--bg-color);
            padding: 3rem 0;
            margin-top: 5rem;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="fas fa-campground me-2"></i>CAMPING STORE</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            
            <form class="d-flex mx-auto my-2 my-lg-0" action="shop.php" method="GET" style="width: 100%; max-width: 400px;">
                <div class="input-group">
                    <input class="form-control rounded-start-pill border-0" type="search" name="q" placeholder="ค้นหาอุปกรณ์แคมป์ปิ้ง...">
                    <button class="btn btn-warning rounded-end-pill" type="submit"><i class="fas fa-search text-white"></i></button>
                </div>
            </form>

            <ul class="navbar-nav ms-auto align-items-center gap-2">
                
                <li class="nav-item"><a class="nav-link" href="index.php">หน้าแรก</a></li>
                <li class="nav-item"><a class="nav-link" href="organizer.php">ผู้จัดทำ</a></li>
                <li class="nav-item"><a class="nav-link" href="shop.php">สินค้าทั้งหมด</a></li>

                <li class="nav-item d-none d-lg-block mx-2 border-end" style="height: 20px; border-color: rgba(255,255,255,0.3) !important;"></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle btn btn-sm btn-outline-light border-0" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i> คุณ <?php echo htmlspecialchars($_SESSION['fullname']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <li>
                                    <a class="dropdown-item text-primary" href="admin/admin_dashboard.php">
                                        <i class="fas fa-tachometer-alt me-2"></i>จัดการร้านค้า (Admin)
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>

                            <li><a class="dropdown-item" href="edit_profile.php"><i class="fas fa-user-cog me-2"></i>แก้ไขข้อมูลส่วนตัว</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="order_history.php">ประวัติการสั่งซื้อ</a></li>
                            <li><a class="dropdown-item text-danger" href="logout.php">ออกจากระบบ</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt me-1"></i> เข้าสู่ระบบ</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-warning rounded-pill px-3 fw-bold" href="register.php">สมัครสมาชิก</a>
                    </li>
                <?php endif; ?>
                
                <li class="nav-item ms-2">
                    <a href="cart.php" class="position-relative text-white fs-5">
                        <i class="fas fa-shopping-cart"></i>
                        <?php 
                            $cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
                        ?>
                        <?php if ($cart_count > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                <?php echo $cart_count; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

    <div id="mainCarousel" class="carousel slide carousel-fade shadow-sm" data-bs-ride="carousel">
        
        <?php 
            // กำหนดข้อมูล Banner
            $banners = [
                ['img' => '1.jpg', 'title' => 'Camping Gear Store', 'desc' => 'A one-stop shop for camping equipment.'],
                ['img' => '2.jpg', 'title' => 'New Arrivals', 'desc' => 'The latest collection is now available for you to own.'],
                ['img' => '3.jpg', 'title' => 'Best Quality', 'desc' => 'We carefully select world-renowned brands for true camping enthusiasts.'],
                ['img' => '4.jpg', 'title' => 'Special Prices', 'desc' => 'Special prices to welcome the tourist season.']
            ];
        ?>

        <div class="carousel-indicators">
            <?php foreach ($banners as $index => $banner): ?>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>"></button>
            <?php endforeach; ?>
        </div>

        <div class="carousel-inner">
            <?php foreach ($banners as $index => $banner): ?>
                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>" data-bs-interval="4000">
                    <img src="banners/<?php echo $banner['img']; ?>" class="d-block w-100 h-100" alt="Banner <?php echo $index+1; ?>">
                    
                    <div class="carousel-caption d-none d-md-block">
                        <h2 class="display-4 fw-bold"><?php echo $banner['title']; ?></h2>
                        <p class="fs-4"><?php echo $banner['desc']; ?></p>
                        <a href="shop.php" class="btn btn-lg btn-warning rounded-pill px-4 fw-bold mt-2">SHOP NOW</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-dark rounded-circle p-3"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-dark rounded-circle p-3"></span>
        </button>
    </div>

    <div class="bg-white py-4 shadow-sm">
    <div class="container">
        <div class="row align-items-center justify-content-center text-center">
            <p class="text-muted small mb-2">PARTNER BRANDS</p>
            <div class="d-flex justify-content-center flex-wrap gap-4 gap-md-5">
                <?php 
                $result_brands->data_seek(0); 
                while($brand = $result_brands->fetch_assoc()): 
                ?>
                    <a href="brand.php?id=<?php echo $brand['brand_id']; ?>" class="text-decoration-none">
                        <h5 class="brand-logo text-uppercase fw-bold text-muted mb-0" style="cursor: pointer;">
                            <?php echo $brand['brand_name']; ?>
                        </h5>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

    <section class="container my-5 py-4" id="featured">
        <h2 class="section-title">สินค้าแนะนำ (Highlight)</h2>
        <div class="row g-4">
            <?php if ($result_featured->num_rows > 0): ?>
                <?php while($row = $result_featured->fetch_assoc()): ?>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <div class="product-card h-100">
                            <div class="product-img-wrapper">
                                <img src="uploads/<?php echo $row['image_url']; ?>" 
                                     onerror="this.src='https://via.placeholder.com/300?text=No+Image'" 
                                     alt="<?php echo $row['product_name']; ?>">
                            </div>
                            <div class="card-body d-flex flex-column p-4">
                                <small class="text-muted mb-1">สินค้าแนะนำ</small>
                                <h5 class="product-title text-truncate"><?php echo $row['product_name']; ?></h5>
                                <div class="d-flex justify-content-between align-items-center mt-auto mb-3">
                                    <span class="product-price">฿<?php echo number_format($row['price'], 0); ?></span>
                                </div>
                                <a href="product_detail.php?id=<?php echo $row['product_id']; ?>" class="btn btn-outline-custom mt-auto">ดูรายละเอียด</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center text-muted">ยังไม่มีสินค้าแนะนำ</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="container-fluid py-5" style="background-color: #f0efe9;">
        <div class="container">
            <h2 class="section-title">Shop by category</h2>
            <div class="row text-center justify-content-center mt-4">
                <?php while($cat = $result_categories->fetch_assoc()): ?>
                    <div class="col-6 col-md-4 col-lg-2 mb-4 cat-item">
                        <a href="shop.php?cat_id=<?php echo $cat['category_id']; ?>" class="text-decoration-none">
                            <div class="cat-circle shadow-sm">
                                <i class="fas fa-campground"></i> 
                            </div>
                            <p class="cat-name"><?php echo $cat['category_name']; ?></p>
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <section class="container my-5 py-4">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <h2 class="section-title mb-0 text-start">สินค้ามาใหม่ (New Arrivals)</h2>
            <a href="shop.php" class="text-decoration-none text-muted">ดูทั้งหมด <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <div class="row g-4">
            <?php 
            $sql_new = "SELECT * FROM products ORDER BY product_id DESC LIMIT 4";
            $result_new = $conn->query($sql_new);
            while($new = $result_new->fetch_assoc()): 
            ?>
                <div class="col-6 col-md-3">
                    <div class="product-card border h-100 position-relative">
                        <span class="position-absolute top-0 start-0 badge bg-warning text-dark m-2">NEW</span>
                        <div class="product-img-wrapper p-3 d-flex align-items-center justify-content-center" style="height: 200px; background: white;">
                            <img src="uploads/<?php echo $new['image_url']; ?>" class="img-fluid" style="max-height: 100%;" onerror="this.src='https://via.placeholder.com/150'">
                        </div>
                        <div class="card-body p-3">
                            <h6 class="fw-bold text-truncate"><?php echo $new['product_name']; ?></h6>
                            <p class="text-success fw-bold">฿<?php echo number_format($new['price']); ?></p>
                            <a href="product_detail.php?id=<?php echo $new['product_id']; ?>" class="btn btn-sm btn-outline-dark w-100 rounded-pill">ดูรายละเอียด</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">CAMPING STORE</h5>
                    <p class="small opacity-75">We are a leading supplier of camping equipment, 
                        sourcing <br>high-quality products from around the world to provide you <br>with the best possible 
                        camping experience.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">เมนูลัด</h5>
                    <ul class="list-unstyled small opacity-75">
                        <li><a href="index.php" class="text-white text-decoration-none">หน้าแรก</a></li>
                        <li><a href="shop.php" class="text-white text-decoration-none">สินค้าทั้งหมด</a></li>
                        <li><a href="#" class="text-white text-decoration-none">แจ้งชำระเงิน</a></li>
                        <li><a href="#" class="text-white text-decoration-none">ติดต่อเรา</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">ติดต่อเรา</h5>
                    <p class="small opacity-75">
                        <i class="fas fa-map-marker-alt me-2"></i> 123 ถ.แคมป์ปิ้ง กรุงเทพฯ 10400<br>
                        <i class="fas fa-phone me-2"></i> 02-123-4567<br>
                        <i class="fas fa-envelope me-2"></i> contact@campingstore.com
                    </p>
                </div>
            </div>
            <hr class="opacity-25">
            <div class="text-center small opacity-50">
                &copy; 2026 Camping Gear Store. All Rights Reserved. Designed for Educational Purpose.
            </div>
        </div>
    </footer>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>