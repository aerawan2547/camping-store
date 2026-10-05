<?php
session_start();
require_once '../config/db.php';

// เช็คสิทธิ์ Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// 1. ดึงข้อมูลหมวดหมู่ทั้งหมด (เพื่อเอาไปใส่ใน Dropdown ตัวกรอง)
$category_result = $conn->query("SELECT * FROM categories");


// 2. เตรียม Query สำหรับดึงสินค้า (แบบ Dynamic Filter)
$sql = "SELECT p.*, b.brand_name, c.category_name 
        FROM products p 
        LEFT JOIN brands b ON p.brand_id = b.brand_id 
        LEFT JOIN categories c ON p.category_id = c.category_id 
        WHERE 1=1"; // ใช้ 1=1 เพื่อให้ต่อ AND ง่าย 

$params = [];
$types = "";

// ถ้ามีการค้นหาชื่อสินค้า
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $sql .= " AND p.product_name LIKE ?";
    $params[] = "%" . $_GET['search'] . "%";
    $types .= "s";
}

// ถ้ามีการเลือกหมวดหมู่
if (isset($_GET['category_id']) && !empty($_GET['category_id'])) {
    $sql .= " AND p.category_id = ?";
    $params[] = $_GET['category_id'];
    $types .= "i";
}

// เรียงลำดับล่าสุดขึ้นก่อน
$sql .= " ORDER BY p.product_id DESC";

// Execute Query
if (!empty($params)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>จัดการสินค้า - Admin Panel</title>
    <link rel="icon" type="image/png" href="../uploads/camp_icon.png">
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
            background-color: var(--bg-color); 
            font-family: 'Sarabun', sans-serif; }
        
        .sidebar { 
            background-color: var(--primary-color); 
            min-height: 100vh; color: white; 
        }

        .sidebar a { 
            color: rgba(255,255,255,0.8); 
            text-decoration: none; 
            padding: 12px 20px; display: block; 
            transition: 0.3s; 
            border-radius: 8px; 
            margin-bottom: 5px; 
        }

        .sidebar a:hover, .sidebar a.active { 
            background-color: var(--secondary-color); 
            color: white; 
            transform: translateX(5px); 
        }
        
        .table-card { 
            border-radius: 12px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
            border: none; 
            background: white; 
        }

        .product-img { 
            width: 50px; 
            height: 50px; 
            object-fit: contain; 
            border-radius: 8px; 
            border: 1px solid #eee; 
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 sidebar p-4 d-none d-md-block">
            <h4 class="fw-bold mb-4 text-center"><i class="fas fa-campground me-2"></i>ADMIN</h4>
            <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
            <a href="admin_product_list.php" class="active"><i class="fas fa-box me-2"></i> จัดการสินค้า</a>
            <a href="admin_customer_list.php"><i class="fas fa-users me-2"></i> ลูกค้า</a>
            <hr>
            <a href="../index.php" target="_blank"><i class="fas fa-external-link-alt me-2"></i> ไปหน้าร้าน</a>
            <a href="../logout.php" class="text-danger mt-3"><i class="fas fa-sign-out-alt me-2"></i> ออกจากระบบ</a>
        </div>

        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark">จัดการสินค้า</h2>
                <a href="admin_product_form.php" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> เพิ่มสินค้าใหม่
                </a>
            </div>

            <div class="card table-card p-4">
                
                <form method="GET" action="admin_product_list.php" class="mb-4">
                    <div class="row g-2">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="ค้นหาชื่อสินค้า..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <select name="category_id" class="form-select">
                                <option value="">-- ทุกหมวดหมู่ --</option>
                                <?php 
                                // วนลูปแสดงหมวดหมู่ใน Dropdown
                                if($category_result->num_rows > 0){
                                    // เลื่อน pointer กลับไปที่แถวแรก (เผื่อมีการใช้ไปแล้ว)
                                    $category_result->data_seek(0); 
                                    while($cat = $category_result->fetch_assoc()){
                                        $selected = (isset($_GET['category_id']) && $_GET['category_id'] == $cat['category_id']) ? 'selected' : '';
                                        echo "<option value='".$cat['category_id']."' $selected>".$cat['category_name']."</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">ค้นหา</button>
                        </div>
                        
                        <div class="col-md-2">
                            <a href="admin_product_list.php" class="btn btn-outline-secondary w-100">ล้างค่า</a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>รูปภาพ</th>
                                <th>ชื่อสินค้า</th>
                                <th>แบรนด์</th>
                                <th>หมวดหมู่</th>
                                <th>ราคา</th>
                                <th>สต็อก</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <img src="../uploads/<?php echo $row['image_url']; ?>" class="product-img" onerror="this.src='https://via.placeholder.com/50'">
                                    </td>
                                    <td class="fw-bold text-dark"><?php echo $row['product_name']; ?></td>
                                    <td><span class="badge bg-light text-dark border"><?php echo $row['brand_name']; ?></span></td>
                                    <td><span class="badge bg-light text-dark border"><?php echo $row['category_name']; ?></span></td>
                                    <td class="fw-bold text-black">฿<?php echo number_format($row['price']); ?></td>
                                    <td>
                                        <?php if($row['stock_quantity'] == 0): ?>
                                            <span class="badge bg-danger">หมด</span>
                                        <?php elseif($row['stock_quantity'] < 6): ?>
                                            <span class="badge bg-danger text-white"><?php echo $row['stock_quantity']; ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-success"><?php echo $row['stock_quantity']; ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="admin_product_form.php?id=<?php echo $row['product_id']; ?>" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                            <i class="fas fa-edit"></i> แก้ไข
                                        </a>
                                        <a href="admin_product_delete.php?id=<?php echo $row['product_id']; ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('ยืนยันที่จะลบสินค้านี้?');">
                                            <i class="fas fa-trash"></i> ลบ
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-box-open fa-3x mb-3 opacity-25"></i><br>
                                        ไม่พบสินค้า
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>