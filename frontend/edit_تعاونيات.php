**edit_تعاونيات.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/تعاونيات.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

// Initialize form data
$form_data = [
    'name' => $data['name'],
    'description' => $data['description'],
    // Add other form fields as needed
];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعاونيات | تعديل</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-slate-900 mb-4">تعاونيات | تعديل</h1>
        <form id="edit-form" class="bg-white p-4 rounded shadow-md">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-slate-700">الاسم</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 border border-gray-300 rounded-md" value="<?= $form_data['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-slate-700">الوصف</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 border border-gray-300 rounded-md"><?= $form_data['description'] ?></textarea>
            </div>
            <!-- Add other form fields as needed -->
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">حفظ</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    type: 'PUT',
                    url: '../backend/تعاونيات.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_تعاونيات.php';
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/تعاونيات.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'ID not set']);
    exit;
}

// Get ID
$id = $_GET['id'];

// Fetch existing record details
$query = "SELECT * FROM تعاونيات WHERE id = '$id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Return JSON response
echo json_encode($row);


Note: This code assumes you have a MySQL database connection established in the `backend/تعاونيات.php` file. You should replace the `mysqli_query` and `mysqli_fetch_assoc` functions with your preferred database library. Additionally, this code does not include any validation or sanitization of user input. You should add this to prevent SQL injection and other security vulnerabilities.