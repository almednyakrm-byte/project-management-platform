<?php
// Import database connection file
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Unauthorized access']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle GET requests
if ($method == 'GET') {
    // Validate and sanitize input
    $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
    
    // SQL query structure: Select all or by id
    if ($id) {
        $stmt = $pdo->prepare('SELECT * FROM تيم WHERE id = :id');
        $stmt->bindParam(':id', $id);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM تيم');
    }
    
    // Execute query
    $stmt->execute();
    
    // Output processing
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($teams);
}

// Handle POST requests
elseif ($method == 'POST') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Forbidden access']);
        exit;
    }
    
    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'] ?? null, FILTER_SANITIZE_STRING);
    
    // SQL query structure: Insert new team
    $stmt = $pdo->prepare('INSERT INTO تيم (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    
    // Execute query
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Team created successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Failed to create team']);
    }
}

// Handle PUT requests
elseif ($method == 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Forbidden access']);
        exit;
    }
    
    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($data['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'] ?? null, FILTER_SANITIZE_STRING);
    
    // SQL query structure: Update existing team
    $stmt = $pdo->prepare('UPDATE تيم SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    
    // Execute query
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Team updated successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Failed to update team']);
    }
}

// Handle DELETE requests
elseif ($method == 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] != 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Forbidden access']);
        exit;
    }
    
    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate and sanitize input
    $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
    
    // SQL query structure: Delete team
    $stmt = $pdo->prepare('DELETE FROM تيم WHERE id = :id');
    $stmt->bindParam(':id', $id);
    
    // Execute query
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Team deleted successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Failed to delete team']);
    }
}

// Handle invalid request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Method not allowed']);
}