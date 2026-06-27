<?php
require_once 'db.php';

// Get the input data from the request body
$inputData = json_decode(file_get_contents('php://input'), true);

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Check if the user is an admin
if (isset($inputData['action']) && in_array($inputData['action'], array('edit', 'delete'))) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
}

// Handle GET request
if (isset($inputData['action']) && $inputData['action'] == 'get') {
    // Validate the input
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize the input
    $id = intval($inputData['id']);

    // Prepare the SQL query
    $stmt = $pdo->prepare('SELECT * FROM أفراد WHERE id = :id');
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
} elseif (isset($inputData['action']) && $inputData['action'] == 'get_all') {
    // Prepare the SQL query
    $stmt = $pdo->prepare('SELECT * FROM أفراد');
    $stmt->execute();

    // Fetch all the results
    $results = $stmt->fetchAll();

    // Check if the results exist
    if ($results) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($results);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif (isset($inputData['action']) && $inputData['action'] == 'create') {
    // Validate the input
    if (!isset($inputData['name']) || !isset($inputData['email'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize the input
    $name = trim($inputData['name']);
    $email = trim($inputData['email']);

    // Prepare the SQL query
    $stmt = $pdo->prepare('INSERT INTO أفراد (name, email) VALUES (:name, :email)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Get the last inserted ID
    $id = $pdo->lastInsertId();

    // Check if the insertion was successful
    if ($id) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('id' => $id));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif (isset($inputData['action']) && $inputData['action'] == 'update') {
    // Validate the input
    if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['email'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize the input
    $id = intval($inputData['id']);
    $name = trim($inputData['name']);
    $email = trim($inputData['email']);

    // Prepare the SQL query
    $stmt = $pdo->prepare('UPDATE أفراد SET name = :name, email = :email WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Updated successfully'));
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} elseif (isset($inputData['action']) && $inputData['action'] == 'delete') {
    // Validate the input
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize the input
    $id = intval($inputData['id']);

    // Prepare the SQL query
    $stmt = $pdo->prepare('DELETE FROM أفراد WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Deleted successfully'));
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid request'));
}