<?php
require_once 'db.php';

// Initialize PDO connection
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
$pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Function to check if user is logged in
function isLoggedIn() {
    // Implement your own logic to check if user is logged in
    // For demonstration purposes, assume a logged-in user has a 'user_id' in the session
    return isset($_SESSION['user_id']);
}

// Function to check if user is admin
function isAdmin() {
    // Implement your own logic to check if user is admin
    // For demonstration purposes, assume an admin user has a 'role' of 'admin' in the session
    return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Validate and sanitize input
    $projectId = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if user is logged in
    if (!isLoggedIn()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // SQL query structure: Select all projects or a specific project by ID
    if ($projectId) {
        $stmt = $pdo->prepare('SELECT * FROM projects WHERE id = :id');
        $stmt->bindParam(':id', $projectId);
        $stmt->execute();
        $project = $stmt->fetch();
        if (!$project) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Project not found']);
            exit;
        }
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($project);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM projects');
        $stmt->execute();
        $projects = $stmt->fetchAll();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($projects);
    }
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user is logged in
    if (!isLoggedIn()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);

    // SQL query structure: Insert a new project
    $stmt = $pdo->prepare('INSERT INTO projects (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Get the ID of the newly inserted project
    $projectId = $pdo->lastInsertId();

    // Return the newly inserted project
    $stmt = $pdo->prepare('SELECT * FROM projects WHERE id = :id');
    $stmt->bindParam(':id', $projectId);
    $stmt->execute();
    $project = $stmt->fetch();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode($project);
}

// Handle PUT requests
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Check if user is logged in and admin
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $projectId = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);

    // SQL query structure: Update a project
    $stmt = $pdo->prepare('UPDATE projects SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $projectId);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Check if the project was found
    if ($stmt->rowCount() == 0) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Project not found']);
        exit;
    }

    // Return the updated project
    $stmt = $pdo->prepare('SELECT * FROM projects WHERE id = :id');
    $stmt->bindParam(':id', $projectId);
    $stmt->execute();
    $project = $stmt->fetch();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($project);
}

// Handle DELETE requests
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Check if user is logged in and admin
    if (!isLoggedIn() || !isAdmin()) {
        http_response_code(401);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $projectId = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);

    // SQL query structure: Delete a project
    $stmt = $pdo->prepare('DELETE FROM projects WHERE id = :id');
    $stmt->bindParam(':id', $projectId);
    $stmt->execute();

    // Check if the project was found
    if ($stmt->rowCount() == 0) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Project not found']);
        exit;
    }

    http_response_code(204);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Project deleted successfully']);
}