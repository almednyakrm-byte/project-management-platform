**create_موارد.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);

    if (empty($name) || empty($description) || empty($price)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert new record into database
        $sql = "INSERT INTO موارد (name, description, price) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $description, $price);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_موارد.php');
        exit;
    }
}

// Include header
require_once '../includes/header.php';

// Include premium Tailwind UI form
?>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-slate-900 text-lg font-bold mb-4">Create New مورد</h2>
    <form method="POST" id="create-form">
        <div class="mb-4">
            <label for="name" class="block text-slate-900 text-sm font-bold mb-2">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 bg-slate-100 border border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter name">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-slate-900 text-sm font-bold mb-2">Description:</label>
            <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 bg-slate-100 border border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter description"></textarea>
        </div>
        <div class="mb-4">
            <label for="price" class="block text-slate-900 text-sm font-bold mb-2">Price:</label>
            <input type="number" id="price" name="price" class="block w-full p-2 pl-10 text-sm text-slate-900 placeholder-slate-400 bg-slate-100 border border-slate-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter price">
        </div>
        <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/موارد.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_موارد.php';
                    } else {
                        alert('Error creating new مورد');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
require_once '../includes/footer.php';
?>

**backend/موارد.php**

<?php
// Include database connection
require_once '../config/db.php';

// Check if form data has been sent
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price'])) {
    // Insert new record into database
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = trim($_POST['price']);

    $sql = "INSERT INTO موارد (name, description, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $description, $price);
    $stmt->execute();

    echo 'success';
} else {
    echo 'Error creating new مورد';
}
?>