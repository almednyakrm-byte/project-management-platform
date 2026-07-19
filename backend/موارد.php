<?php

require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Get input data from JSON or POST
$inputData = json_decode(file_get_contents('php://input'), true);
if (empty($inputData)) {
    $inputData = $_POST;
}

// Validate and sanitize input data
if (empty($inputData['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'ID is required']);
    exit;
}

$inputData['id'] = intval($inputData['id']);

if (isset($inputData['name'])) {
    $inputData['name'] = trim($inputData['name']);
    if (empty($inputData['name'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Name is required']);
        exit;
    }
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare('SELECT * FROM مورد WHERE deleted_at IS NULL');
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Only admins can create new items']);
        exit;
    }

    $stmt = $pdo->prepare('INSERT INTO مورد (name, created_by) VALUES (:name, :created_by)');
    $stmt->bindParam(':name', $inputData['name']);
    $stmt->bindParam(':created_by', $userID);
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['message' => 'Item created successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create item']);
    }
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Only admins can edit items']);
        exit;
    }

    $stmt = $pdo->prepare('UPDATE مورد SET name = :name, updated_by = :updated_by WHERE id = :id');
    $stmt->bindParam(':name', $inputData['name']);
    $stmt->bindParam(':updated_by', $userID);
    $stmt->bindParam(':id', $inputData['id']);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['message' => 'Item updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update item']);
    }
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Only admins can delete items']);
        exit;
    }

    $stmt = $pdo->prepare('UPDATE مورد SET deleted_at = NOW(), deleted_by = :deleted_by WHERE id = :id');
    $stmt->bindParam(':deleted_by', $userID);
    $stmt->bindParam(':id', $inputData['id']);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['message' => 'Item deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete item']);
    }
    exit;
}