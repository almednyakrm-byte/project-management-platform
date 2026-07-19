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

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Validate input parameters
    if (!isset($inputData['projectID'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing projectID parameter'));
        exit;
    }

    // Sanitize input parameters
    $projectID = filter_var($inputData['projectID'], FILTER_SANITIZE_NUMBER_INT);

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM مشاريع WHERE projectID = :projectID');
    $stmt->bindParam(':projectID', $projectID);
    $stmt->execute();

    // Fetch and return data
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($project) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($project);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Project not found'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input parameters
    if (!isset($inputData['projectName']) || !isset($inputData['projectDescription'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing projectName or projectDescription parameter'));
        exit;
    }

    // Sanitize input parameters
    $projectName = filter_var($inputData['projectName'], FILTER_SANITIZE_STRING);
    $projectDescription = filter_var($inputData['projectDescription'], FILTER_SANITIZE_STRING);

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO مشاريع (projectName, projectDescription, createdByID) VALUES (:projectName, :projectDescription, :createdByID)');
    $stmt->bindParam(':projectName', $projectName);
    $stmt->bindParam(':projectDescription', $projectDescription);
    $stmt->bindParam(':createdByID', $userID);
    $stmt->execute();

    // Return created project ID
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('projectID' => $pdo->lastInsertId()));
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Validate input parameters
    if (!isset($inputData['projectID']) || !isset($inputData['projectName']) || !isset($inputData['projectDescription'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing projectID, projectName, or projectDescription parameter'));
        exit;
    }

    // Sanitize input parameters
    $projectID = filter_var($inputData['projectID'], FILTER_SANITIZE_NUMBER_INT);
    $projectName = filter_var($inputData['projectName'], FILTER_SANITIZE_STRING);
    $projectDescription = filter_var($inputData['projectDescription'], FILTER_SANITIZE_STRING);

    // Check if user is admin
    if ($userRole != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE مشاريع SET projectName = :projectName, projectDescription = :projectDescription WHERE projectID = :projectID AND createdByID = :createdByID');
    $stmt->bindParam(':projectID', $projectID);
    $stmt->bindParam(':projectName', $projectName);
    $stmt->bindParam(':projectDescription', $projectDescription);
    $stmt->bindParam(':createdByID', $userID);
    $stmt->execute();

    // Return updated project ID
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('projectID' => $projectID));
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Validate input parameters
    if (!isset($inputData['projectID'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Missing projectID parameter'));
        exit;
    }

    // Sanitize input parameters
    $projectID = filter_var($inputData['projectID'], FILTER_SANITIZE_NUMBER_INT);

    // Check if user is admin
    if ($userRole != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM مشاريع WHERE projectID = :projectID AND createdByID = :createdByID');
    $stmt->bindParam(':projectID', $projectID);
    $stmt->bindParam(':createdByID', $userID);
    $stmt->execute();

    // Return deleted project ID
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('projectID' => $projectID));
}