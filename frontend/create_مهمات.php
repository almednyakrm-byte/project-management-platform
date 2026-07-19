**create_مهمات.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit;
}

// Include header
include '../includes/header.php';

// Include navigation
include '../includes/navigation.php';

// Form validation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];

    // Validation
    if (empty($title) || empty($description) || empty($priority) || empty($status)) {
        $error = 'Please fill all fields';
    } else {
        // Insert data
        $sql = "INSERT INTO مهمات (title, description, priority, status) VALUES ('$title', '$description', '$priority', '$status')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $success = 'Record added successfully';
            header('Location: list_مهمات.php');
            exit;
        } else {
            $error = 'Error adding record';
        }
    }
}

// Include form
include '../includes/create_مهمات_form.php';

// Include footer
include '../includes/footer.php';
?>


**create_مهمات_form.php**

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h2 class="text-2xl font-bold mb-4">Add New مهمات</h2>
    <form action="" method="post" id="create-form">
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input type="text" id="title" name="title" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500" required></textarea>
        </div>
        <div class="mb-4">
            <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
            <select id="priority" name="priority" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500" required>
                <option value="">Select priority</option>
                <option value="High">High</option>
                <option value="Medium">Medium</option>
                <option value="Low">Low</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select id="status" name="status" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:border-indigo-500 focus:ring-indigo-500" required>
                <option value="">Select status</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
            </select>
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Add Record</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/مهمات.php',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        alert(response.success);
                        window.location.href = 'list_مهمات.php';
                    } else {
                        alert(response.error);
                    }
                }
            });
        });
    });
</script>


**مهمات.php (backend)**

<?php
// Database connection
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Check connection
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Form data
$title = $_POST['title'];
$description = $_POST['description'];
$priority = $_POST['priority'];
$status = $_POST['status'];

// Insert data
$sql = "INSERT INTO مهمات (title, description, priority, status) VALUES ('$title', '$description', '$priority', '$status')";
$result = mysqli_query($conn, $sql);

// Check if query was successful
if ($result) {
    $success = 'Record added successfully';
} else {
    $error = 'Error adding record';
}

// Close connection
mysqli_close($conn);

// Output response
echo json_encode(['success' => $success, 'error' => $error]);
?>