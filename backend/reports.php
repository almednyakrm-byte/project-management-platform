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

// Function to validate user role
function validateUserRole($role) {
    // Assuming a session-based authentication system
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_role'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized access']);
        exit;
    }
    if ($role === 'admin' && $_SESSION['user_role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden access']);
        exit;
    }
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate user role
    validateUserRole('user');
    
    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM reports');
    $stmt->execute();
    
    // Fetch results
    $reports = $stmt->fetchAll();
    
    // Return results as JSON
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($reports);
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate user role
    validateUserRole('admin');
    
    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate input data
    if (!isset($data['title']) || !isset($data['content'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }
    
    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO reports (title, content) VALUES (:title, :content)');
    $stmt->bindParam(':title', $data['title']);
    $stmt->bindParam(':content', $data['content']);
    $stmt->execute();
    
    // Return created report ID as JSON
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['id' => $pdo->lastInsertId()]);
}

// Handle PUT requests
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate user role
    validateUserRole('admin');
    
    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate input data
    if (!isset($data['id']) || !isset($data['title']) || !isset($data['content'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }
    
    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE reports SET title = :title, content = :content WHERE id = :id');
    $stmt->bindParam(':id', $data['id']);
    $stmt->bindParam(':title', $data['title']);
    $stmt->bindParam(':content', $data['content']);
    $stmt->execute();
    
    // Return updated report ID as JSON
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['id' => $data['id']]);
}

// Handle DELETE requests
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate user role
    validateUserRole('admin');
    
    // Read input data
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Validate input data
    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }
    
    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM reports WHERE id = :id');
    $stmt->bindParam(':id', $data['id']);
    $stmt->execute();
    
    // Return deleted report ID as JSON
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['id' => $data['id']]);
}