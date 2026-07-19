<?php

// Import database connection settings
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data from JSON body
$input = json_decode(file_get_contents('php://input'), true);

// Define database table name
$table_name = 'مهام';

// Define allowed columns for CRUD operations
$allowed_columns = ['title', 'description', 'status'];

// Define allowed roles for CRUD operations
$allowed_roles = ['admin', 'user'];

// Define HTTP response status codes
$success_status_code = 200;
$created_status_code = 201;
$updated_status_code = 200;
$deleted_status_code = 204;
$not_found_status_code = 404;
$invalid_request_status_code = 400;

// Define HTTP response headers
$headers = ['Content-Type' => 'application/json'];

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is logged in
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Prepare SQL query to select all tasks
    $query = 'SELECT * FROM ' . $table_name;
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Output tasks as JSON
    http_response_code($success_status_code);
    header($headers[0]);
    echo json_encode($tasks);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Validate input data
    if (!isset($input['title']) || !isset($input['description'])) {
        http_response_code($invalid_request_status_code);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize input data
    $title = $pdo->quote($input['title']);
    $description = $pdo->quote($input['description']);

    // Prepare SQL query to insert new task
    $query = 'INSERT INTO ' . $table_name . ' (title, description, status, user_id) VALUES (:title, :description, 0, :user_id)';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();

    // Output new task as JSON
    http_response_code($created_status_code);
    header($headers[0]);
    echo json_encode(['id' => $pdo->lastInsertId()]);
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is logged in and has admin role
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Validate input data
    if (!isset($input['id']) || !isset($input['title']) || !isset($input['description'])) {
        http_response_code($invalid_request_status_code);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize input data
    $id = (int) $input['id'];
    $title = $pdo->quote($input['title']);
    $description = $pdo->quote($input['description']);

    // Prepare SQL query to update task
    $query = 'UPDATE ' . $table_name . ' SET title = :title, description = :description WHERE id = :id';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Output updated task as JSON
    http_response_code($updated_status_code);
    header($headers[0]);
    echo json_encode(['message' => 'Task updated successfully']);
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is logged in and has admin role
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Validate input data
    if (!isset($input['id'])) {
        http_response_code($invalid_request_status_code);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize input data
    $id = (int) $input['id'];

    // Prepare SQL query to delete task
    $query = 'DELETE FROM ' . $table_name . ' WHERE id = :id';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Output deleted task as JSON
    http_response_code($deleted_status_code);
    header($headers[0]);
    echo json_encode(['message' => 'Task deleted successfully']);
    exit;
}

// Output error message if invalid request method
http_response_code($invalid_request_status_code);
echo json_encode(['error' => 'Invalid request method']);
exit;