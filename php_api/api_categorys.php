<?php
include 'condb.php';
header('Content-Type: application/json; charset=utf-8');

$action = $_POST['action'] ?? '';

try {
 if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // ดึงสินค้าพร้อมชื่อประเภท


        // ดึงรายการประเภทด้วย
        $catStmt = $conn->prepare("SELECT * FROM categorys ORDER BY category_id ASC");
        $catStmt->execute();
        $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(["success" => true, "data"=> $categories, "categories" => $categories]);
        exit;
    }


    // เพิ่มประเภทสินค้า
    if ($action === 'add') {
        $desc = $_POST['categorys.category_id'] ?? '';
        $name = $_POST['categorys.category_name'];
        

        $imageName = null;
        if (!empty($_FILES['image']['name'])) {
            $imageName = time() . '_' . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], "uploads/$imageName");
        }

        $stmt = $conn->prepare("INSERT INTO categorys (categorys_name, description)
                                VALUES (?, ?)");
        $stmt->execute([$name, $desc]);
        echo json_encode(["message" => "เพิ่มประเภทสินค้าเรียบร้อย"]);
        exit;
    }

    // แก้ไขสินค้า
    if ($action === 'update') {
        $id = $_POST['categorys_id'];
        $name = $_POST['categorys_name'];

        $sql = "UPDATE categorys SET categorys_name=?, description=? WHERE categorys_id=?";
        $params = [$name, $desc, $id];

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        echo json_encode(["message" => "แก้ไขประเภทสินค้าสำเร็จ"]);
        exit;
    }

    // ลบสินค้า
    if ($action === 'delete') {
        $id = $_POST['categorys_id'];
        $stmt = $conn->prepare("DELETE FROM categorys WHERE categorys_id=?");
        $stmt->execute([$id]);
        echo json_encode(["message" => "ลบประเภทสินค้าเรียบร้อย"]);
        exit;
    }
    
        $sql .= " WHERE categorys_id=?";
        $params[] = $id;

        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        echo json_encode(["message" => "แก้ไขประเภทสินค้าสำเร็จ"]);
        exit;
    

    // ลบสินค้า
    if ($action === 'delete') {
        $id = $_POST['categorys_id'];
        $stmt = $conn->prepare("DELETE FROM categorys WHERE categorys_id=?");
        $stmt->execute([$id]);
        echo json_encode(["message" => "ลบประเภทสินค้าเรียบร้อย"]);
        exit;
    }

}catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

?>