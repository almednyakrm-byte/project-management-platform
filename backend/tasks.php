<?php
// Import database connection
require_once 'db.php';

// Initialize database connection
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);

// Function to validate user role
function validateUserRole($role) {
    // For demonstration purposes, assume a logged-in user with admin role
    // In a real application, you would retrieve the user's role from a session or database
    $loggedInUserRole = 'admin'; // Replace with actual user role
    if ($role === 'admin' && $loggedInUserRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate user role
    validateUserRole('user');

    // Prepare SQL query to retrieve tasks
    $stmt = $pdo->prepare('SELECT * FROM tasks');
    $stmt->execute();

    // Fetch tasks
    $tasks = $stmt->fetchAll();

    // Set HTTP response headers
    http_response_code(200);
    header('Content-Type: application/json');

    // Output tasks in JSON format
    echo json_encode($tasks);
}

// Handle POST requests
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate user role
    validateUserRole('user');

    // Read input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($inputData['title']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $title = filter_var($inputData['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($inputData['description'], FILTER_SANITIZE_STRING);

    // Prepare SQL query to insert task
    $stmt = $pdo->prepare('INSERT INTO tasks (title, description) VALUES (:title, :description)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);

    // Execute SQL query
    if ($stmt->execute()) {
        // Set HTTP response headers
        http_response_code(201);
        header('Content-Type: application/json');

        // Output created task in JSON format
        echo json_encode(['message' => 'Task created successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create task']);
    }
}

// Handle PUT requests
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate user role
    validateUserRole('admin');

    // Read input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['title']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
    $title = filter_var($inputData['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($inputData['description'], FILTER_SANITIZE_STRING);

    // Prepare SQL query to update task
    $stmt = $pdo->prepare('UPDATE tasks SET title = :title, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);

    // Execute SQL query
    if ($stmt->execute()) {
        // Set HTTP response headers
        http_response_code(200);
        header('Content-Type: application/json');

        // Output updated task in JSON format
        echo json_encode(['message' => 'Task updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update task']);
    }
}

// Handle DELETE requests
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate user role
    validateUserRole('admin');

    // Read input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare SQL query to delete task
    $stmt = $pdo->prepare('DELETE FROM tasks WHERE id = :id');
    $stmt->bindParam(':id', $id);

    // Execute SQL query
    if ($stmt->execute()) {
        // Set HTTP response headers
        http_response_code(200);
        header('Content-Type: application/json');

        // Output deleted task in JSON format
        echo json_encode(['message' => 'Task deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete task']);
    }
}

// Handle invalid request methods
else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}