<?php
session_start();
session_unset();  // ล้างตัวแปร Session ทั้งหมด
session_destroy(); // ทำลาย Session ทิ้ง
header("Location: index.php"); // เด้งกลับหน้าแรก
exit();
?>