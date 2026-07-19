**edit_مهام.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$url = '../backend/مهام.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if record exists
if (!$data) {
    echo 'Error: Record not found';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit مهام</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Edit مهام</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-slate-900">Title</label>
                <input type="text" id="title" name="title" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['title'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $data['description'] ?></textarea>
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 border border-indigo-500 rounded-md hover:bg-indigo-600 focus:ring-indigo-500">Save Changes</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مهام.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error: ' + response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/مهام.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('success' => false, 'message' => 'Error: ID not set'));
    exit;
}

// Get ID
$id = $_GET['id'];

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    echo json_encode(array('success' => false, 'message' => 'Error: User not logged in'));
    exit;
}

// Get existing record details
$query = "SELECT * FROM مهام WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Check if record exists
if (!$data) {
    echo json_encode(array('success' => false, 'message' => 'Error: Record not found'));
    exit;
}

// Update record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents('php://input'), $putData);
    $title = $putData['title'];
    $description = $putData['description'];
    $query = "UPDATE مهام SET title = '$title', description = '$description' WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error: Update failed'));
    }
} else {
    echo json_encode($data);
}