<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$product = null;
$title = "เพิ่มสินค้าใหม่";

//ถ้ามี ID ส่งมา จะเป็นการแก้ไข
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE product_id = $id";
    $product = $conn->query($sql)->fetch_assoc();
    $title = "แก้ไขสินค้า: " . $product['product_name'];
}

//ดึงแบรนด์+หมวดหมู่
$brands = $conn->query("SELECT * FROM brands");
$categories = $conn->query("SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <link rel="icon" type="image/png" href="../uploads/camp_icon.png">
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><?php echo $title; ?></h5>
            </div>
            <div class="card-body">
                <form action="admin_product_save.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="product_id" value="<?php echo isset($product) ? $product['product_id'] : ''; ?>">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">ชื่อสินค้า</label>
                            <input type="text" name="product_name" class="form-control" required value="<?php echo isset($product) ? $product['product_name'] : ''; ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">แบรนด์</label>
                            <select name="brand_id" class="form-select">
                                <?php while($b = $brands->fetch_assoc()): ?>
                                    <option value="<?php echo $b['brand_id']; ?>" <?php echo (isset($product) && $product['brand_id'] == $b['brand_id']) ? 'selected' : ''; ?>>
                                        <?php echo $b['brand_name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">หมวดหมู่</label>
                            <select name="category_id" class="form-select">
                                <?php while($c = $categories->fetch_assoc()): ?>
                                    <option value="<?php echo $c['category_id']; ?>" <?php echo (isset($product) && $product['category_id'] == $c['category_id']) ? 'selected' : ''; ?>>
                                        <?php echo $c['category_name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">รายละเอียดสินค้า</label>
                        <textarea name="description" class="form-control" rows="4"><?php echo isset($product) ? $product['description'] : ''; ?></textarea>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">ราคา (บาท)</label>
                            <input type="number" name="price" class="form-control" required value="<?php echo isset($product) ? $product['price'] : ''; ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">จำนวนสต็อก</label>
                            <input type="number" name="stock_quantity" class="form-control" required value="<?php echo isset($product) ? $product['stock_quantity'] : ''; ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">รูปภาพสินค้า</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <input type="hidden" name="old_image" value="<?php echo isset($product) ? $product['image_url'] : ''; ?>">
                            <?php if(isset($product) && !empty($product['image_url'])): ?>
                                <small class="text-muted">รูปปัจจุบัน: <?php echo $product['image_url']; ?></small>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featuredCheck" <?php echo (isset($product) && $product['is_featured'] == 1) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="featuredCheck">
                            ตั้งเป็นสินค้าแนะนำ (Featured)
                        </label>
                    </div>

                    <hr>
                    <button type="submit" class="btn btn-success px-4">บันทึกข้อมูล</button>
                    <a href="admin_product_list.php" class="btn btn-secondary">ยกเลิก</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>