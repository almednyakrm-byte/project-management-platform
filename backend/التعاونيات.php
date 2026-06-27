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
try {
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);
if (empty($input)) {
    $input = $_POST;
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid id']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('SELECT * FROM التعاونيات WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $result = $stmt->fetch();

    // Process output
    if ($result === false) {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
    } else {
        http_response_code(200);
        echo json_encode($result);
    }
    exit;
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'] ?? null, FILTER_SANITIZE_STRING);
    if (empty($name) || empty($description)) {
        http_response_code(400);
        echo json_encode(['error' => 'Name and description are required']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('INSERT INTO التعاونيات (name, description) VALUES (:name, :description)');
    $stmt->execute([':name' => $name, ':description' => $description]);
    $id = $pdo->lastInsertId();

    // Process output
    http_response_code(201);
    echo json_encode(['id' => $id, 'name' => $name, 'description' => $description]);
    exit;
}

// Handle PUT requests
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate and sanitize input
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'] ?? null, FILTER_SANITIZE_STRING);
    if ($id === false || empty($name) || empty($description)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid id, name, or description']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('UPDATE التعاونيات SET name = :name, description = :description WHERE id = :id');
    $stmt->execute([':id' => $id, ':name' => $name, ':description' => $description]);
    $rowCount = $stmt->rowCount();

    // Process output
    if ($rowCount === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
    } else {
        http_response_code(200);
        echo json_encode(['id' => $id, 'name' => $name, 'description' => $description]);
    }
    exit;
}

// Handle DELETE requests
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate and sanitize input
    $id = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    if ($id === false) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid id']);
        exit;
    }

    // Prepare and execute SQL query
    $stmt = $pdo->prepare('DELETE FROM التعاونيات WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $rowCount = $stmt->rowCount();

    // Process output
    if ($rowCount === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Not found']);
    } else {
        http_response_code(204);
        echo json_encode(['message' => 'Deleted successfully']);
    }
    exit;
}

// Handle invalid requests
http_response_code(405);
echo json_encode(['error' => 'Method not allowed']);