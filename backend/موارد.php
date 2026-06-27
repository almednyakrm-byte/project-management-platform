<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $limit = (int) $inputData['limit'] ?? 10;
    $offset = (int) $inputData['offset'] ?? 0;
    $search = trim($inputData['search'] ?? '');

    // SQL query
    $stmt = $pdo->prepare('
        SELECT *
        FROM مورد
        WHERE name LIKE :search
        ORDER BY id DESC
        LIMIT :limit OFFSET :offset
    ');
    $stmt->bindParam(':search', '%' . $search . '%');
    $stmt->bindParam(':limit', $limit);
    $stmt->bindParam(':offset', $offset);

    // Execute query and fetch results
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output results
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($results);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $name = trim($inputData['name'] ?? '');
    $description = trim($inputData['description'] ?? '');

    // Check if admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query
    $stmt = $pdo->prepare('
        INSERT INTO مورد (name, description)
        VALUES (:name, :description)
    ');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);

    // Execute query
    if ($stmt->execute()) {
        // Output result
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Resource created successfully']);
        exit;
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
        exit;
    }
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate and sanitize input
    $id = (int) $inputData['id'] ?? 0;
    $name = trim($inputData['name'] ?? '');
    $description = trim($inputData['description'] ?? '');

    // Check if admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query
    $stmt = $pdo->prepare('
        UPDATE مورد
        SET name = :name, description = :description
        WHERE id = :id
    ');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $id);

    // Execute query
    if ($stmt->execute()) {
        // Output result
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Resource updated successfully']);
        exit;
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
        exit;
    }
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate and sanitize input
    $id = (int) $inputData['id'] ?? 0;

    // Check if admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query
    $stmt = $pdo->prepare('
        DELETE FROM مورد
        WHERE id = :id
    ');
    $stmt->bindParam(':id', $id);

    // Execute query
    if ($stmt->execute()) {
        // Output result
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Resource deleted successfully']);
        exit;
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
        exit;
    }
}