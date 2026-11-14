<?php
include 'condb.php';
header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $conn->query("SELECT category_id, category_name FROM categorys ORDER BY category_id ASC");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["success" => true, "data" => $categories, "categories" => $categories]);
        exit;
    }

    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $name = trim($_POST['category_name'] ?? '');
        if ($name === '') {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "กรุณาระบุชื่อประเภทสินค้า"]);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO categorys (category_name) VALUES (?)");
        $stmt->execute([$name]);
        echo json_encode(["success" => true, "message" => "เพิ่มประเภทสินค้าเรียบร้อย"]);
        exit;
    }

    if ($action === 'update') {
        $id = $_POST['category_id'] ?? '';
        $name = trim($_POST['category_name'] ?? '');
        if ($id === '' || $name === '') {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "กรุณาระบุข้อมูลประเภทสินค้าให้ครบ"]);
            exit;
        }

        $stmt = $conn->prepare("UPDATE categorys SET category_name = ? WHERE category_id = ?");
        $stmt->execute([$name, $id]);
        echo json_encode(["success" => true, "message" => "แก้ไขประเภทสินค้าสำเร็จ"]);
        exit;
    }

    if ($action === 'delete') {
        $id = $_POST['category_id'] ?? '';
        if ($id === '') {
            http_response_code(400);
            echo json_encode(["success" => false, "message" => "ไม่พบรหัสประเภทสินค้า"]);
            exit;
        }

        $stmt = $conn->prepare("DELETE FROM categorys WHERE category_id = ?");
        $stmt->execute([$id]);
        echo json_encode(["success" => true, "message" => "ลบประเภทสินค้าเรียบร้อย"]);
        exit;
    }

    http_response_code(400);
    echo json_encode(["success" => false, "message" => "ไม่พบคำสั่งที่ต้องการ"]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>