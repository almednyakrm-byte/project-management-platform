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

// Define routes
$routes = array(
    'GET' => array('/' => 'getAll'),
    'POST' => array('/' => 'create'),
    'PUT' => array('/:id' => 'update'),
    'DELETE' => array('/:id' => 'delete')
);

// Determine route and method
$method = $_SERVER['REQUEST_METHOD'];
$route = $_SERVER['REQUEST_URI'];

// Check if route exists
if (!isset($routes[$method][$route])) {
    http_response_code(405);
    echo json_encode(array('error' => 'Method Not Allowed'));
    exit;
}

// Call route handler
$handler = $routes[$method][$route];
$handler();

// Route handlers
function getAll() {
    global $db;
    $stmt = $db->prepare('SELECT * FROM تعاونيات');
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($rows);
}

function create() {
    global $db;
    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $name = $db->quote($input['name']);
    $description = $db->quote($input['description']);

    // Insert data
    $stmt = $db->prepare('INSERT INTO تعاونيات (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return ID of newly created record
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('id' => $db->lastInsertId()));
}

function update($id) {
    global $db;
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Sanitize input
    $name = $db->quote($input['name']);
    $description = $db->quote($input['description']);

    // Update data
    $stmt = $db->prepare('UPDATE تعاونيات SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Updated successfully'));
}

function delete($id) {
    global $db;
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Delete data
    $stmt = $db->prepare('DELETE FROM تعاونيات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Deleted successfully'));
}
?>