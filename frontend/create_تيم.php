**create_تيم.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
?>

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-2xl font-bold text-slate-900 mb-4">Create تيم</h1>

    <form id="create-tim-form" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label for="name" class="block text-slate-700 text-sm font-bold mb-2">Name</label>
            <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Name">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-slate-700 text-sm font-bold mb-2">Description</label>
            <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Description"></textarea>
        </div>

        <div class="mb-4">
            <label for="status" class="block text-slate-700 text-sm font-bold mb-2">Status</label>
            <select id="status" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-slate-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create تيم</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#create-tim-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/تيم.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_تيم.php';
                    } else {
                        alert('Error creating تيم');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
require_once 'footer.php';
?>


**تيم.php (backend)**

<?php
// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['status'])) {
    // Connect to database
    $conn = mysqli_connect('localhost', 'username', 'password', 'database');

    // Check connection
    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    // Insert data into database
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $sql = "INSERT INTO تيم (name, description, status) VALUES ('$name', '$description', '$status')";

    if (mysqli_query($conn, $sql)) {
        echo 'success';
    } else {
        echo 'Error creating تيم';
    }

    // Close connection
    mysqli_close($conn);
} else {
    echo 'Error creating تيم';
}
?>