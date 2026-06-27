**create_أهداف.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $target_date = trim($_POST['target_date']);

    if (empty($name) || empty($description) || empty($target_date)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $query = "INSERT INTO أهداف (name, description, target_date) VALUES ('$name', '$description', '$target_date')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Redirect back to list page
            header('Location: list_أهداف.php');
            exit;
        } else {
            $error = 'Error inserting data';
        }
    }
}

// Include header
require_once '../backend/header.php';

?>

<!-- Create new أهداف form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold mb-4">Create New أهداف</h2>
    <form id="create-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 border-gray-300 rounded-md focus:border-blue-600 focus:ring-blue-600" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 border-gray-300 rounded-md focus:border-blue-600 focus:ring-blue-600" required></textarea>
        </div>
        <div class="mb-4">
            <label for="target_date" class="block text-sm font-medium text-gray-700">Target Date</label>
            <input type="date" id="target_date" name="target_date" class="block w-full px-4 py-2 text-sm text-gray-700 border-gray-300 rounded-md focus:border-blue-600 focus:ring-blue-600" required>
        </div>
        <button type="submit" name="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
    <?php if (isset($error)) : ?>
        <p class="text-red-600 mt-2"><?= $error ?></p>
    <?php endif; ?>
</div>

<!-- Include footer -->
<?php require_once '../backend/footer.php'; ?>


**create_أهداف.js**
javascript
// Get form element
const form = document.getElementById('create-form');

// Add event listener to form submission
form.addEventListener('submit', (e) => {
    e.preventDefault();

    // Get form data
    const formData = new FormData(form);

    // Send AJAX request to backend
    fetch('../backend/أهداف.php', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            // Redirect back to list page
            window.location.href = 'list_أهداف.php';
        } else {
            // Display error message
            const errorElement = document.createElement('p');
            errorElement.textContent = data.error;
            errorElement.classList.add('text-red-600', 'mt-2');
            form.appendChild(errorElement);
        }
    })
    .catch((error) => console.error(error));
});


**أهداف.php (backend)**

<?php
// Include database connection
require_once '../backend/db.php';

// Check if form data has been sent
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['target_date'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $target_date = trim($_POST['target_date']);

    if (empty($name) || empty($description) || empty($target_date)) {
        echo json_encode(['success' => false, 'error' => 'Please fill in all fields']);
        exit;
    }

    // Insert data into database
    $query = "INSERT INTO أهداف (name, description, target_date) VALUES ('$name', '$description', '$target_date')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error inserting data']);
    }
}