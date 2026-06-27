<?php

// Import database connection settings
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized access'));
    exit;
}

// Get user role
$userRole = $_SESSION['user_role'];

// Check if user is admin
if ($userRole !== 'admin') {
    http_response_code(403);
    echo json_encode(array('error' => 'Forbidden access'));
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input parameters
    $params = array();
    if (isset($_GET['id'])) {
        $params['id'] = intval($_GET['id']);
    }

    // Prepare SQL query
    $sql = "SELECT * FROM الموارد_البشرية";
    if (!empty($params['id'])) {
        $sql .= " WHERE id = :id";
    }

    // Execute query using PDO Prepared Statement
    $stmt = $pdo->prepare($sql);
    if (!empty($params['id'])) {
        $stmt->bindParam(':id', $params['id']);
    }
    $stmt->execute();

    // Fetch and return data
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read input data from JSON
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    if (!isset($inputData['name']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request data'));
        exit;
    }

    // Prepare SQL query
    $sql = "INSERT INTO الموارد_البشرية (name, description) VALUES (:name, :description)";

    // Execute query using PDO Prepared Statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $inputData['name']);
    $stmt->bindParam(':description', $inputData['description']);
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array('message' => 'Resource created successfully'));
        exit;
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to create resource'));
        exit;
    }
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Read input data from JSON
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request data'));
        exit;
    }

    // Prepare SQL query
    $sql = "UPDATE الموارد_البشرية SET name = :name, description = :description WHERE id = :id";

    // Execute query using PDO Prepared Statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $inputData['id']);
    $stmt->bindParam(':name', $inputData['name']);
    $stmt->bindParam(':description', $inputData['description']);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Resource updated successfully'));
        exit;
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to update resource'));
        exit;
    }
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate and sanitize input parameters
    $params = array();
    if (isset($_GET['id'])) {
        $params['id'] = intval($_GET['id']);
    }

    // Prepare SQL query
    $sql = "DELETE FROM الموارد_البشرية WHERE id = :id";

    // Execute query using PDO Prepared Statement
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $params['id']);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array('message' => 'Resource deleted successfully'));
        exit;
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to delete resource'));
        exit;
    }
}