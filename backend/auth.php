<?php
// Start the session to handle user authentication
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response indicating the user is logged in
    echo json_encode(['status' => 'logged_in', 'user_id' => $_SESSION['user_id']]);
    exit;
}

// Handle the login request
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if the username and password are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Sanitize the input fields to prevent SQL injection
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Prepare the SQL query to select the user
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        // Fetch the user data
        $user = $stmt->fetch();

        // Check if the user exists and the password is correct
        if ($user && password_verify($password, $user['password'])) {
            // If the password is correct, log the user in and return a JSON response
            $_SESSION['user_id'] = $user['id'];
            echo json_encode(['status' => 'logged_in', 'user_id' => $_SESSION['user_id']]);
        } else {
            // If the password is incorrect, return a JSON response indicating the login failed
            echo json_encode(['status' => 'login_failed']);
        }
    } else {
        // If the username or password is missing, return a JSON response indicating the login failed
        echo json_encode(['status' => 'login_failed']);
    }
}

// Handle the register request
if (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if the username, email, and password are set
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        // Sanitize the input fields to prevent SQL injection
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Check if the username and email are unique
        $stmt = $db->prepare('SELECT * FROM users WHERE username = :username OR email = :email');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Fetch the user data
        $user = $stmt->fetch();

        // Check if the username or email is already taken
        if ($user) {
            // If the username or email is taken, return a JSON response indicating the registration failed
            echo json_encode(['status' => 'registration_failed']);
        } else {
            // If the username and email are unique, hash the password and insert the user into the database
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->execute();

            // Return a JSON response indicating the registration was successful
            echo json_encode(['status' => 'registered']);
        }
    } else {
        // If the username, email, or password is missing, return a JSON response indicating the registration failed
        echo json_encode(['status' => 'registration_failed']);
    }
}

// Handle the logout request
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Destroy the session to log the user out
    session_destroy();
    echo json_encode(['status' => 'logged_out']);
}

// Handle the get request to check the session status
if (isset($_GET['action']) && $_GET['action'] == 'check_session') {
    // Return a JSON response indicating the session status
    echo json_encode(['status' => 'logged_in' ? 'logged_in' : 'logged_out']);
}