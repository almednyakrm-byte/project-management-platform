<?php
require_once 'db.php';

// Get user data from session
$user = $_SESSION['user'];

// Check if user is logged in
if (!$user) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    'GET' => array(
        '/all' => 'getAll',
        '/:id' => 'getById'
    ),
    'POST' => array(
        '/' => 'create'
    ),
    'PUT' => array(
        '/:id' => 'update'
    ),
    'DELETE' => array(
        '/:id' => 'delete'
    )
);

// Get route
$route = $_SERVER['REQUEST_URI'];
$route = explode('/', $route);
array_shift($route);
array_shift($route);
$route = implode('/', $route);

// Get method
$method = $_SERVER['REQUEST_METHOD'];

// Check if route exists
if (!isset($routes[$method][$route])) {
    http_response_code(404);
    echo json_encode(array('error' => 'Not found'));
    exit;
}

// Get function
$func = $routes[$method][$route];

// Call function
$func();

// Functions
function getAll() {
    global $pdo;
    $stmt = $pdo->prepare('SELECT * FROM أهداف');
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
}

function getById() {
    global $pdo;
    $id = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM أهداف WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$data) {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
        exit;
    }
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
}

function create() {
    global $pdo;
    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    // Sanitize input
    $name = htmlspecialchars($input['name']);
    $description = htmlspecialchars($input['description']);
    // Check if user is admin
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    // Insert data
    $stmt = $pdo->prepare('INSERT INTO أهداف (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Created successfully'));
}

function update() {
    global $pdo;
    $id = $_GET['id'];
    // Validate input
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    // Sanitize input
    $name = htmlspecialchars($input['name']);
    $description = htmlspecialchars($input['description']);
    // Check if user is admin
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    // Update data
    $stmt = $pdo->prepare('UPDATE أهداف SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Updated successfully'));
}

function delete() {
    global $pdo;
    $id = $_GET['id'];
    // Check if user is admin
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    // Delete data
    $stmt = $pdo->prepare('DELETE FROM أهداف WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Deleted successfully'));
}