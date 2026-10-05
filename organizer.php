<?php
session_start();
require_once 'config/db.php';
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>คณะผู้จัดทำ - Camping Gear Store</title>
    <link rel="icon" type="image/png" href="uploads/camp_icon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { 
            --primary-color: #40513B;  
            --secondary-color: #628141; 
            --bg-color: #E5D9B6;       
            --accent-color: #E67E22;   
            --text-color: #333333; }

        body { 
            background-color: var(--bg-color); 
            font-family: 'Sarabun', sans-serif; }
        
        .navbar-custom { 
            background-color: var(--primary-color);
            padding: 1rem 0; }

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
        
        .hero-banner {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('banners/1.jpg') ;
            background-size: cover;
            background-position: center;
            color: white;
            padding: 80px 0;
            margin-bottom: 50px;
        }
        
        .member-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            height: 100%;
        }

        .member-card:hover { 
            transform: translateY(-10px); 
        }

        .member-img-wrapper {
            height: 300px;
            overflow: hidden;
            position: relative;
        }

        .member-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.3s;
        }
        .member-card:hover .member-img { 
            transform: scale(1.05); }

        .role-badge {
            background-color: var(--primary-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 10px;
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

    <header class="hero-banner text-center">
        <div class="container mt-4">
            <h1 class="fw-bold display-4">คณะผู้จัดทำ</h1>
            <p class="lead">รายชื่อสมาชิกกลุ่มและหน้าที่ความรับผิดชอบ</p>
        </div>
    </header>

    <div class="container mb-5">
        <div class="row g-4 justify-content-center">
            
            <div class="col-md-6 col-lg-3">
                <div class="member-card text-center">
                    <div class="member-img-wrapper">
                        <img src="uploads/member/member1.jpg" class="member-img" alt="Member 1">
                    </div>
                    <div class="card-body p-4">
                        <span class="role-badge">Project Manager</span>
                        <h5 class="fw-bold mb-1">ชื่อ-นามสกุล 1</h5>
                        <p class="text-muted mb-3 small">รหัสนักศึกษา: 6xxxxxxxxx</p>
                        <hr class="mx-auto opacity-25" style="width: 50px;">
                        <p class="small text-muted">
                            วางแผนโครงการ, ควบคุมดูแลภาพรวม, ประสานงานสมาชิก
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="member-card text-center">
                    <div class="member-img-wrapper">
                        <img src="uploads/member/member1.jpg" class="member-img" alt="Member 1">
                    </div>
                    <div class="card-body p-4">
                        <span class="role-badge">Front-end Developer</span>
                        <h5 class="fw-bold mb-1">ชื่อ-นามสกุล 2</h5>
                        <p class="text-muted mb-3 small">รหัสนักศึกษา: 6xxxxxxxxx</p>
                        <hr class="mx-auto opacity-25" style="width: 50px;">
                        <p class="small text-muted">
                            ออกแบบ UI/UX, เขียน HTML/CSS, ดูแลความสวยงามหน้าเว็บ
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="member-card text-center">
                    <div class="member-img-wrapper">
                        <img src="uploads/member/member1.jpg" class="member-img" alt="Member 1">
                    </div>
                    <div class="card-body p-4">
                        <span class="role-badge">Back-end Developer</span>
                        <h5 class="fw-bold mb-1">ชื่อ-นามสกุล 3</h5>
                        <p class="text-muted mb-3 small">รหัสนักศึกษา: 6xxxxxxxxx</p>
                        <hr class="mx-auto opacity-25" style="width: 50px;">
                        <p class="small text-muted">
                            ออกแบบฐานข้อมูล, เขียนระบบ PHP, เชื่อมต่อ Database
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="member-card text-center">
                    <div class="member-img-wrapper">
                        <img src="uploads/member/member1.jpg" class="member-img" alt="Member 1">
                    </div>
                    <div class="card-body p-4">
                        <span class="role-badge">Tester / Doc</span>
                        <h5 class="fw-bold mb-1">ชื่อ-นามสกุล 4</h5>
                        <p class="text-muted mb-3 small">รหัสนักศึกษา: 6xxxxxxxxx</p>
                        <hr class="mx-auto opacity-25" style="width: 50px;">
                        <p class="small text-muted">
                            ทดสอบระบบ, จัดทำเอกสารรายงาน, นำเสนอโปรเจกต์
                        </p>
                    </div>
                </div>
            </div>

        </div>
        
        <div class="text-center mt-5">
            <a href="index.php" class="btn btn-outline-dark rounded-pill px-4">
                <i class="fas fa-home me-2"></i> กลับหน้าหลัก
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>