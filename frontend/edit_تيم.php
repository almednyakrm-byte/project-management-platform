**edit_تيم.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details
$existingRecord = json_decode(file_get_contents('../backend/تيم.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit تيم</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h2 class="text-slate-900 text-lg font-bold mb-4">Edit تيم</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="text-slate-900 text-sm font-bold">Name:</label>
                <input type="text" id="name" name="name" class="w-full p-2 text-sm text-gray-900 bg-gray-100 rounded-md" value="<?= $existingRecord['name'] ?>">
            </div>
            <div>
                <label for="description" class="text-slate-900 text-sm font-bold">Description:</label>
                <textarea id="description" name="description" class="w-full p-2 text-sm text-gray-900 bg-gray-100 rounded-md"><?= $existingRecord['description'] ?></textarea>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
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
                    url: '../backend/تيم.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
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


**تيم.php (backend)**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    echo json_encode(array('error' => 'ID not set'));
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get existing record
$stmt = $conn->prepare("SELECT * FROM تيم WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch existing record
$existingRecord = $result->fetch_assoc();

// Close connection
$conn->close();

// Output existing record as JSON
echo json_encode($existingRecord);
?>