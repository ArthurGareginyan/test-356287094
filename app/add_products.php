<?php

require_once './database.php';

$conn = Database::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = file_get_contents('php://input');
    $products = json_decode($input, true);

    // Validate and sanitize input data
    if (is_array($products)) {
        $stmt = $conn->prepare("INSERT INTO products (title, price) VALUES (?, ?)");
        foreach ($products as $product) {
            $title = htmlspecialchars($product['title'], ENT_QUOTES, 'UTF-8');
            $price = filter_var($product['price'], FILTER_VALIDATE_FLOAT);
            if ($title && $price !== false) {
                $stmt->bind_param("sd", $title, $price);
                $stmt->execute();
            }
        }
        $stmt->close();
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid input"]);
    }
}

$conn->close();

?>
