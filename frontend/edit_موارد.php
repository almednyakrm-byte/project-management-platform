**edit_موارد.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get record ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/موارد.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Set form fields
$name = $data['name'];
$description = $data['description'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit مورد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">Edit مورد</h2>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" value="<?php echo $name; ?>" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-emerald-600 focus:border-emerald-600" rows="4"><?php echo $description; ?></textarea>
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-emerald-600 border border-emerald-600 rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">Save Changes</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'PUT',
                    url: '../backend/موارد.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_موارد.php';
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


**backend/موارد.php**

<?php
// Check if record ID is set
if (!isset($_GET['id'])) {
    header('Location: list_موارد.php');
    exit;
}

// Get record ID
$id = $_GET['id'];

// Fetch existing record details
$query = "SELECT * FROM مورد WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Return JSON response
echo json_encode($data);
?>