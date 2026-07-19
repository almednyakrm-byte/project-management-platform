<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if (isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM أنشطة WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $activity = $stmt->fetch();
    if ($activity) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($activity);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Activity not found']);
    }
} elseif (isset($_GET['all'])) {
    $stmt = $pdo->query('SELECT * FROM أنشطة');
    $activities = $stmt->fetchAll();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($activities);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}

// Handle POST request
if (isset($input['title']) && isset($input['description'])) {
    // Validate and sanitize input data
    $title = filter_var($input['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'], FILTER_SANITIZE_STRING);
    
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }
    
    // Insert new activity
    $stmt = $pdo->prepare('INSERT INTO أنشطة (title, description) VALUES (:title, :description)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Activity created successfully']);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}

// Handle PUT request
if (isset($input['id']) && isset($input['title']) && isset($input['description'])) {
    // Validate and sanitize input data
    $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
    $title = filter_var($input['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'], FILTER_SANITIZE_STRING);
    
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }
    
    // Update existing activity
    $stmt = $pdo->prepare('UPDATE أنشطة SET title = :title, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Activity updated successfully']);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}

// Handle DELETE request
if (isset($input['id'])) {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }
    
    // Delete activity
    $stmt = $pdo->prepare('DELETE FROM أنشطة WHERE id = :id');
    $stmt->bindParam(':id', $input['id']);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Activity deleted successfully']);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}