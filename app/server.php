<?php

require_once './database.php';

$conn = Database::getInstance()->getConnection();

// Fetch client ID from GET parameters and validate it
$client_id = isset($_GET['client_id']) ? intval($_GET['client_id']) : 0;

if ($client_id > 0) {
    $stmt = $conn->prepare(
        "SELECT u.first_name, u.second_name, p.title, p.price
         FROM user u
         JOIN user_order o ON u.id = o.user_id
         JOIN products p ON o.product_id = p.id
         WHERE u.id = ?
         ORDER BY p.price DESC, p.title ASC"
    );
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = [
                'full_name' => htmlspecialchars($row['first_name'] . ' ' . $row['second_name']),
                'title' => htmlspecialchars($row['title']),
                'price' => htmlspecialchars($row['price'])
            ];
        }

        // Generate JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        // No records found for the given client ID
        echo json_encode(["error" => "Client ID does not exist"]);
    }

    $stmt->close();
} else {
    echo json_encode(["error" => "Invalid client ID"]);
}

$conn->close();

?>
