<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $p_id = $_POST['p_id'];
    $quantity = intval($_POST['quantity']);

    $sql = "SELECT p_price, p_stocks, p_name FROM products WHERE p_id = '$p_id'";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);

    if (!$product) {
        echo json_encode(['status' => 'error', 'message' => 'Product not found']);
        exit;
    }

    $currentStock = $product['p_stocks'];

    if ($quantity > $currentStock) {
        echo json_encode(['status' => 'error', 'message' => 'Order exceeds available stock']);
        exit;
    }

    if (!$result) {
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
    exit;
}

    $newStock = $currentStock - $quantity;

    $sql_update = "UPDATE products SET p_stocks = '$newStock' WHERE p_id = '$p_id'";
    mysqli_query($conn, $sql_update);

    echo json_encode([
        'status' => 'success',
        'message' => "You purchased $quantity x {$product['p_name']}",
        'new_stock' => $newStock
    ]);
}