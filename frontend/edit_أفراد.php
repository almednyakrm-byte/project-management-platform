**edit_أفراد.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/أفراد.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Set form fields
$name = $data['name'];
$age = $data['age'];
$address = $data['address'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit أفراد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-emerald-600 mb-4">Edit أفراد</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
                <input type="text" id="name" name="name" class="block w-full p-2 border border-gray-300 rounded-md" value="<?= $name ?>">
            </div>
            <div class="mb-4">
                <label for="age" class="block text-sm font-medium text-gray-700">Age:</label>
                <input type="number" id="age" name="age" class="block w-full p-2 border border-gray-300 rounded-md" value="<?= $age ?>">
            </div>
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700">Address:</label>
                <textarea id="address" name="address" class="block w-full p-2 border border-gray-300 rounded-md"><?= $address ?></textarea>
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
                    url: '../backend/أفراد.php',
                    data: formData,
                    success: function(response) {
                        if (response === 'success') {
                            window.location.href = 'list_أفراد.php';
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


**backend/أفراد.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
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

// Fetch existing record details
$sql = "SELECT * FROM أفراد WHERE id = '$id'";
$result = $conn->query($sql);

// Check if record exists
if ($result->num_rows > 0) {
    // Get record details
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $age = $row['age'];
    $address = $row['address'];

    // Output JSON
    echo json_encode(array('name' => $name, 'age' => $age, 'address' => $address));
} else {
    echo 'Record not found';
}

// Close connection
$conn->close();
?>

<?php
// Update record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents('php://input'), $data);

    // Connect to database
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update record
    $sql = "UPDATE أفراد SET name = '$data[name]', age = '$data[age]', address = '$data[address]' WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo 'Error updating record';
    }

    // Close connection
    $conn->close();
}
?>