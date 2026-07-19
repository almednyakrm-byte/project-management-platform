**create_موارد.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);

    if (!empty($name) && !empty($description) && !empty($price)) {
        // Insert data into database
        $sql = "INSERT INTO موارد (name, description, price) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $description, $price]);

        // Redirect back to list page
        header('Location: list_موارد.php');
        exit;
    } else {
        $error = 'Please fill in all fields';
    }
}

// Include header and navigation
require_once '../includes/header.php';
?>

<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-4 text-emerald-600">Create New مورد</h1>

    <form id="create-muadar-form" class="bg-white p-8 rounded-lg shadow-md" method="POST">
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter name">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
            <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter description"></textarea>
        </div>

        <div class="mb-4">
            <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price:</label>
            <input type="number" id="price" name="price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter price">
        </div>

        <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Create</button>
        <?php if (isset($error)) : ?>
            <p class="text-red-500 text-sm mt-2"><?= $error ?></p>
        <?php endif; ?>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>

<script>
    $(document).ready(function() {
        $('#create-muadar-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/موارد.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_موارد.php';
                    } else {
                        console.error(response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>


**backend/موارد.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been sent
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);

    if (!empty($name) && !empty($description) && !empty($price)) {
        // Insert data into database
        $sql = "INSERT INTO موارد (name, description, price) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $description, $price]);

        // Return success response
        echo 'success';
    } else {
        // Return error response
        echo 'Error: Please fill in all fields';
    }
}