<?php

require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET request
if ($method === 'GET') {
    // Validate and sanitize input
    $projectID = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if project ID is provided
    if (!$projectID) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid project ID'));
        exit;
    }

    // Query database to retrieve project data
    $stmt = $pdo->prepare('SELECT * FROM مشاريع WHERE id = :id');
    $stmt->bindParam(':id', $projectID);
    $stmt->execute();
    $project = $stmt->fetch();

    // Check if project exists
    if (!$project) {
        http_response_code(404);
        echo json_encode(array('error' => 'Project not found'));
        exit;
    }

    // Return project data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($project);
}

// Handle POST request
elseif ($method === 'POST') {
    // Read input data from JSON
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $projectName = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $projectDescription = filter_var($inputData['description'], FILTER_SANITIZE_STRING);

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Insert new project into database
    $stmt = $pdo->prepare('INSERT INTO مشاريع (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $projectName);
    $stmt->bindParam(':description', $projectDescription);
    $stmt->execute();

    // Return new project ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $pdo->lastInsertId()));
}

// Handle PUT request
elseif ($method === 'PUT') {
    // Read input data from JSON
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $projectID = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
    $projectName = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $projectDescription = filter_var($inputData['description'], FILTER_SANITIZE_STRING);

    // Check if project ID is provided
    if (!$projectID) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid project ID'));
        exit;
    }

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Update project data in database
    $stmt = $pdo->prepare('UPDATE مشاريع SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $projectID);
    $stmt->bindParam(':name', $projectName);
    $stmt->bindParam(':description', $projectDescription);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Project updated successfully'));
}

// Handle DELETE request
elseif ($method === 'DELETE') {
    // Validate and sanitize input
    $projectID = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if project ID is provided
    if (!$projectID) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid project ID'));
        exit;
    }

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Delete project from database
    $stmt = $pdo->prepare('DELETE FROM مشاريع WHERE id = :id');
    $stmt->bindParam(':id', $projectID);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Project deleted successfully'));
}

// Return error message for unsupported request methods
else {
    http_response_code(405);
    echo json_encode(array('error' => 'Method not allowed'));
}