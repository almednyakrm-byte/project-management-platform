<?php

require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Get all tasks
    $stmt = $pdo->prepare('SELECT * FROM مهمات');
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return tasks
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($tasks);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        http_response_code(401);
        echo json_encode(array('error' => 'Unauthorized'));
        exit;
    }

    // Validate input data
    if (!isset($input['title']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $title = htmlspecialchars($input['title']);
    $description = htmlspecialchars($input['description']);

    // Insert task
    $stmt = $pdo->prepare('INSERT INTO مهمات (title, description, user_id) VALUES (:title, :description, :user_id)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();

    // Return task ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $pdo->lastInsertId()));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['id']) || !isset($input['title']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = intval($input['id']);
    $title = htmlspecialchars($input['title']);
    $description = htmlspecialchars($input['description']);

    // Update task
    $stmt = $pdo->prepare('UPDATE مهمات SET title = :title, description = :description WHERE id = :id');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Task updated successfully'));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input data
    if (!isset($input['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input data
    $id = intval($input['id']);

    // Delete task
    $stmt = $pdo->prepare('DELETE FROM مهمات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Task deleted successfully'));
    exit;
}

// Return error message
http_response_code(405);
echo json_encode(array('error' => 'Method not allowed'));
exit;