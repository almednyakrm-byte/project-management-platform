**edit_أهداف.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/أهداف.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Check if data was fetched successfully
if ($data) {
    // Populate form fields
    $title = $data['title'];
    $description = $data['description'];
} else {
    // Handle error
    echo 'Error fetching data';
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit أهداف</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">Edit أهداف</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title</label>
                <input type="text" id="title" name="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $title ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?= $description ?></textarea>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
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
                    url: '../backend/أهداف.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_أهداف.php';
                        } else {
                            alert('Error updating record');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/أهداف.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('success' => false));
    exit;
}

// Get ID
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get existing record details
$sql = "SELECT * FROM أهداف WHERE id = '$id'";
$result = $conn->query($sql);

// Check if record exists
if ($result->num_rows > 0) {
    // Get record details
    $row = $result->fetch_assoc();
    $title = $row['title'];
    $description = $row['description'];

    // Update record via PUT
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        parse_str(file_get_contents('php://input'), $putData);
        $title = $putData['title'];
        $description = $putData['description'];

        $sql = "UPDATE أهداف SET title = '$title', description = '$description' WHERE id = '$id'";
        if ($conn->query($sql) === TRUE) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false));
        }
    } else {
        echo json_encode(array('title' => $title, 'description' => $description));
    }
} else {
    echo json_encode(array('success' => false));
}

// Close connection
$conn->close();
?>