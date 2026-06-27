<?php
// Start the session to handle user authentication
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response indicating their status
    echo json_encode(array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']));
    exit;
}

// Handle the login request
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if the username and password are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Sanitize the input fields to prevent SQL injection
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Prepare the SQL query to select the user from the database
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the user exists in the database
        if ($result->num_rows > 0) {
            // Fetch the user's data from the database
            $user = $result->fetch_assoc();

            // Verify the password using password_verify()
            if (password_verify($password, $user['password'])) {
                // If the password is correct, log the user in and return a JSON response
                $_SESSION['user_id'] = $user['id'];
                echo json_encode(array('status' => 'logged_in', 'user_id' => $_SESSION['user_id']));
            } else {
                // If the password is incorrect, return a JSON response indicating an error
                echo json_encode(array('status' => 'error', 'message' => 'Invalid password'));
            }
        } else {
            // If the user does not exist, return a JSON response indicating an error
            echo json_encode(array('status' => 'error', 'message' => 'Invalid username or password'));
        }
    } else {
        // If the username or password is missing, return a JSON response indicating an error
        echo json_encode(array('status' => 'error', 'message' => 'Missing username or password'));
    }
} elseif (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if the username, email, and password are set
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        // Sanitize the input fields to prevent SQL injection
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Check if the username and email are valid
        if (preg_match('/^[a-zA-Z0-9]+$/', $username) && preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
            // Prepare the SQL query to insert the new user into the database
            $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, password_hash($password, PASSWORD_DEFAULT));
            $stmt->execute();

            // Check if the user was inserted successfully
            if ($stmt->affected_rows > 0) {
                // If the user was inserted, return a JSON response indicating success
                echo json_encode(array('status' => 'success', 'message' => 'User created successfully'));
            } else {
                // If the user was not inserted, return a JSON response indicating an error
                echo json_encode(array('status' => 'error', 'message' => 'Failed to create user'));
            }
        } else {
            // If the username or email is invalid, return a JSON response indicating an error
            echo json_encode(array('status' => 'error', 'message' => 'Invalid username or email'));
        }
    } else {
        // If the username, email, or password is missing, return a JSON response indicating an error
        echo json_encode(array('status' => 'error', 'message' => 'Missing username, email, or password'));
    }
} elseif (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Log the user out by destroying the session
    session_destroy();
    echo json_encode(array('status' => 'logged_out'));
}

// Close the database connection
$mysqli->close();
?>