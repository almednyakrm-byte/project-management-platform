**create_الموارد-البشرية.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Create a new resource
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];
    $department = $_POST['department'];

    // Validate input
    if (empty($name) || empty($email) || empty($phone) || empty($position) || empty($department)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert into database
        $sql = "INSERT INTO resources (name, email, phone, position, department) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $email, $phone, $position, $department);
        $stmt->execute();

        // Redirect back to list page
        header('Location: list_الموارد-البشرية.php');
        exit;
    }
}

// Include header and navigation
require_once '../includes/header.php';
?>

<!-- Create resource form -->
<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">Create Resource</h2>
    <form id="create-resource-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:border-teal-500 focus:ring focus:ring-teal-500" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:border-teal-500 focus:ring focus:ring-teal-500" required>
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
            <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:border-teal-500 focus:ring focus:ring-teal-500" required>
        </div>
        <div class="mb-4">
            <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
            <input type="text" id="position" name="position" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:border-teal-500 focus:ring focus:ring-teal-500" required>
        </div>
        <div class="mb-4">
            <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
            <input type="text" id="department" name="department" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:border-teal-500 focus:ring focus:ring-teal-500" required>
        </div>
        <button type="submit" name="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">Create Resource</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>


**create_الموارد-البشرية.js**
javascript
// Get form element
const form = document.getElementById('create-resource-form');

// Add event listener to form submission
form.addEventListener('submit', (e) => {
    e.preventDefault();

    // Get form data
    const formData = new FormData(form);

    // Send AJAX request to backend
    fetch('../backend/الموارد-البشرية.php', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            // Redirect back to list page
            window.location.href = 'list_الموارد-البشرية.php';
        } else {
            // Display error message
            alert(data.error);
        }
    })
    .catch((error) => {
        console.error(error);
    });
});


**backend/الموارد-البشرية.php**

<?php
// Include database connection
require_once '../config/database.php';

// Check if form data is sent
if (isset($_POST['submit'])) {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];
    $department = $_POST['department'];

    // Validate input
    if (empty($name) || empty($email) || empty($phone) || empty($position) || empty($department)) {
        echo json_encode(array('success' => false, 'error' => 'Please fill in all fields'));
        exit;
    }

    // Insert into database
    $sql = "INSERT INTO resources (name, email, phone, position, department) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $email, $phone, $position, $department);
    $stmt->execute();

    // Return success message
    echo json_encode(array('success' => true));
    exit;
}