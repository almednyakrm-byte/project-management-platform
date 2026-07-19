<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define database table name
$table_name = 'أفراد';

// Define validation rules
$validation_rules = [
    'name' => 'required|string',
    'email' => 'required|email',
    'phone' => 'required|numeric',
    'role' => 'required|in:admin,user'
];

// Validate input data
foreach ($validation_rules as $field => $rule) {
    if (isset($input[$field])) {
        $input[$field] = filter_var($input[$field], FILTER_SANITIZE_STRING);
        if (!filter_var($input[$field], FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^' . $rule . '$/']])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid ' . $field]);
            exit;
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'Missing ' . $field]);
        exit;
    }
}

// Handle CRUD operations
if (isset($input['id'])) {
    // Update operation
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare update query
    $stmt = $pdo->prepare("UPDATE $table_name SET name = :name, email = :email, phone = :phone, role = :role WHERE id = :id");
    $stmt->bindParam(':id', $input['id']);
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':email', $input['email']);
    $stmt->bindParam(':phone', $input['phone']);
    $stmt->bindParam(':role', $input['role']);

    // Execute update query
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['message' => 'Updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update']);
    }
} elseif (isset($input['id']) && $input['id'] === 0) {
    // Delete operation
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Prepare delete query
    $stmt = $pdo->prepare("DELETE FROM $table_name WHERE id = :id");
    $stmt->bindParam(':id', $input['id']);

    // Execute delete query
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['message' => 'Deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete']);
    }
} else {
    // Insert operation
    // Prepare insert query
    $stmt = $pdo->prepare("INSERT INTO $table_name (name, email, phone, role) VALUES (:name, :email, :phone, :role)");
    $stmt->bindParam(':name', $input['name']);
    $stmt->bindParam(':email', $input['email']);
    $stmt->bindParam(':phone', $input['phone']);
    $stmt->bindParam(':role', $input['role']);

    // Execute insert query
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['message' => 'Created successfully', 'id' => $pdo->lastInsertId()]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create']);
    }
}

// Select operation
if (!isset($input['id']) && !isset($input['id']) && $input['id'] !== 0) {
    // Prepare select query
    $stmt = $pdo->prepare("SELECT * FROM $table_name");
    $stmt->execute();

    // Fetch and return data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    echo json_encode($data);
}