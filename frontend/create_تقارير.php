**create_تقارير.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include header and footer
include 'header.php';
include 'footer.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $status = $_POST['status'];

    // Insert data into database
    $db = new PDO('mysql:host=localhost;dbname=database_name', 'username', 'password');
    $stmt = $db->prepare('INSERT INTO تقارير (name, description, status) VALUES (:name, :description, :status)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':status', $status);
    $stmt->execute();

    // Redirect back to list_{mod_slug}.php
    header('Location: list_{mod_slug}.php');
    exit;
}
?>

<!-- Create تقارير form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold mb-4">Create تقارير</h2>
    <form id="create-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-bold mb-2">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 border border-gray-300 rounded-md">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-bold mb-2">Description:</label>
            <textarea id="description" name="description" class="block w-full p-2 border border-gray-300 rounded-md"></textarea>
        </div>
        <div class="mb-4">
            <label for="status" class="block text-sm font-bold mb-2">Status:</label>
            <select id="status" name="status" class="block w-full p-2 border border-gray-300 rounded-md">
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
</div>

<!-- AJAX script to submit form -->
<script>
    $(document).ready(function() {
        $('#create-form').submit(function(event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/تقارير.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_{mod_slug}.php';
                    } else {
                        alert('Error creating تقارير');
                    }
                }
            });
        });
    });
</script>

**Note:** Replace `{mod_slug}` with the actual slug of your module. Also, make sure to update the database connection settings and the AJAX URL to match your actual backend setup.