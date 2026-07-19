<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input parameters
    $team_id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // Prepare SQL query
    $query = 'SELECT * FROM teams';
    $params = [];

    // Add condition if team_id is provided
    if ($team_id !== null) {
        $query .= ' WHERE id = :id';
        $params = ['id' => $team_id];
    }

    // Execute query using PDO Prepared Statement
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    // Fetch and process results
    $teams = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return HTTP response with JSON data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($teams);
}

// Handle POST requests
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user has admin role
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }

    // Read input data from request body
    $input_data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $name = filter_var($input_data['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($input_data['description'] ?? null, FILTER_SANITIZE_STRING);

    // Check for required fields
    if ($name === null || $description === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // Prepare SQL query
    $query = 'INSERT INTO teams (name, description) VALUES (:name, :description)';
    $params = ['name' => $name, 'description' => $description];

    // Execute query using PDO Prepared Statement
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    // Return HTTP response with JSON data
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Team created successfully']);
}

// Handle PUT requests
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user has admin role
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }

    // Read input data from request body
    $input_data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $id = filter_var($input_data['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($input_data['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($input_data['description'] ?? null, FILTER_SANITIZE_STRING);

    // Check for required fields
    if ($id === null || $name === null || $description === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // Prepare SQL query
    $query = 'UPDATE teams SET name = :name, description = :description WHERE id = :id';
    $params = ['id' => $id, 'name' => $name, 'description' => $description];

    // Execute query using PDO Prepared Statement
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    // Return HTTP response with JSON data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Team updated successfully']);
}

// Handle DELETE requests
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user has admin role
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }

    // Read input data from request body
    $input_data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $id = filter_var($input_data['id'] ?? null, FILTER_VALIDATE_INT);

    // Check for required fields
    if ($id === null) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // Prepare SQL query
    $query = 'DELETE FROM teams WHERE id = :id';
    $params = ['id' => $id];

    // Execute query using PDO Prepared Statement
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    // Return HTTP response with JSON data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Team deleted successfully']);
}

// Handle invalid request methods
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}