<?php
require_once 'db.php';

// Get user role and logged-in status from session
$userRole = $_SESSION['userRole'] ?? null;
$loggedIn = $_SESSION['loggedIn'] ?? false;

// Check if user is logged-in and authorized
if (!$loggedIn || ($userRole !== 'admin' && $_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'DELETE')) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Get input data from JSON body
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare('SELECT * FROM تقارير');
    $stmt->execute();
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($reports);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input data
    if (!isset($inputData['title']) || !isset($inputData['description'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $title = htmlspecialchars($inputData['title']);
    $description = htmlspecialchars($inputData['description']);

    // Insert report into database
    $stmt = $pdo->prepare('INSERT INTO تقارير (title, description) VALUES (:title, :description)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Report created successfully']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['title']) || !isset($inputData['description'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $id = htmlspecialchars($inputData['id']);
    $title = htmlspecialchars($inputData['title']);
    $description = htmlspecialchars($inputData['description']);

    // Update report in database
    $stmt = $pdo->prepare('UPDATE تقارير SET title = :title, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Report updated successfully']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $id = htmlspecialchars($inputData['id']);

    // Delete report from database
    $stmt = $pdo->prepare('DELETE FROM تقارير WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Report deleted successfully']);
}