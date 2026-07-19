<?php
require_once 'db.php';

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get the user role
$userRole = $_SESSION['user_role'];

// Handle GET request
if ($method === 'GET') {
    // Validate the request
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize the ID
    $id = intval($_GET['id']);

    // Prepare the SQL query
    $stmt = $pdo->prepare('SELECT * FROM المهام WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->fetch();

    // Check if the result exists
    if ($result) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif ($method === 'POST') {
    // Validate the request
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data || !isset($data['title']) || !isset($data['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize the input
    $title = trim($data['title']);
    $description = trim($data['description']);

    // Prepare the SQL query
    $stmt = $pdo->prepare('INSERT INTO المهام (title, description) VALUES (:title, :description)');
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Get the ID of the newly inserted record
    $id = $pdo->lastInsertId();

    // Return the result
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $id, 'title' => $title, 'description' => $description));
} elseif ($method === 'PUT') {
    // Validate the request
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data || !isset($data['id']) || !isset($data['title']) || !isset($data['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize the input
    $id = intval($data['id']);
    $title = trim($data['title']);
    $description = trim($data['description']);

    // Check if the user is an admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare the SQL query
    $stmt = $pdo->prepare('UPDATE المهام SET title = :title, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->rowCount() === 1) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('id' => $id, 'title' => $title, 'description' => $description));
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif ($method === 'DELETE') {
    // Validate the request
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize the ID
    $id = intval($_GET['id']);

    // Check if the user is an admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare the SQL query
    $stmt = $pdo->prepare('DELETE FROM المهام WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Check if the delete was successful
    if ($stmt->rowCount() === 1) {
        http_response_code(204);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
}