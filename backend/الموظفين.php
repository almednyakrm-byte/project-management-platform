<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized access'));
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    // Select all employees
    $stmt = $pdo->prepare('SELECT * FROM employees');
    $stmt->execute();
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($employees);
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_one') {
    // Select one employee by ID
    $stmt = $pdo->prepare('SELECT * FROM employees WHERE id = :id');
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($employee);
}

// Handle POST request
if (isset($_POST['action']) && $_POST['action'] == 'create') {
    // Validate input data
    if (!isset($inputData['name']) || !isset($inputData['email']) || !isset($inputData['role'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($inputData['email'], FILTER_SANITIZE_EMAIL);
    $role = filter_var($inputData['role'], FILTER_SANITIZE_STRING);

    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }

    // Insert new employee
    $stmt = $pdo->prepare('INSERT INTO employees (name, email, role) VALUES (:name, :email, :role)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);
    $stmt->execute();
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Employee created successfully'));
}

// Handle PUT request
if (isset($_PUT['action']) && $_PUT['action'] == 'update') {
    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['email']) || !isset($inputData['role'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($inputData['email'], FILTER_SANITIZE_EMAIL);
    $role = filter_var($inputData['role'], FILTER_SANITIZE_STRING);

    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }

    // Update employee
    $stmt = $pdo->prepare('UPDATE employees SET name = :name, email = :email, role = :role WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Employee updated successfully'));
}

// Handle DELETE request
if (isset($_DELETE['action']) && $_DELETE['action'] == 'delete') {
    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input data'));
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden access'));
        exit;
    }

    // Delete employee
    $stmt = $pdo->prepare('DELETE FROM employees WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Employee deleted successfully'));
}