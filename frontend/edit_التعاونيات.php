**edit_التعاونيات.php**

<?php
session_start();

// Validate session
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/التعاونيات.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data exists
if ($data) {
    $title = $data['title'];
    $description = $data['description'];
    $status = $data['status'];
} else {
    header('Location: list_التعاونيات.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit التعاونيات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4 text-emerald-600">Edit التعاونيات</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
                <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $title ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
                <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?= $description ?></textarea>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
                <select id="status" name="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="active" <?= $status == 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $status == 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">Update</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/التعاونيات.php',
                    data: formData,
                    success: function(response) {
                        if (response == 'success') {
                            window.location.href = 'list_التعاونيات.php';
                        } else {
                            alert('Error updating record');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/التعاونيات.php**

<?php
// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details from database
// Replace with your actual database query
$data = array(
    'title' => 'تعاونية جديدة',
    'description' => 'وصف التعاونية',
    'status' => 'active'
);

// Return data as JSON
header('Content-Type: application/json');
echo json_encode($data);