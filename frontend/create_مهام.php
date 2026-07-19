**create_مهام.php**

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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $due_date = trim($_POST['due_date']);

    if (empty($name) || empty($description) || empty($due_date)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert new record into database
        $sql = "INSERT INTO مهام (name, description, due_date) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $name, $description, $due_date);
        $stmt->execute();
        $stmt->close();

        // Redirect back to list page
        header('Location: list_مهام.php');
        exit;
    }
}

// Include header
require_once '../backend/header.php';

?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-12 2xl:p-12">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-8 2xl:p-8">
        <h2 class="text-slate-900 font-bold text-lg mb-4">Create New مهام</h2>

        <?php if (isset($error)) : ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form id="create-form" class="space-y-4" method="post">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="name">
                        Name
                    </label>
                    <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="name" type="text" name="name" required>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="description">
                        Description
                    </label>
                    <textarea class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="description" name="description" required></textarea>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full px-3">
                    <label class="block uppercase tracking-wide text-slate-900 text-xs font-bold mb-2" for="due_date">
                        Due Date
                    </label>
                    <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="due_date" type="date" name="due_date" required>
                </div>
            </div>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" type="submit">
                Create
            </button>
        </form>
    </div>
</div>

<!-- Include footer -->
<?php require_once '../backend/footer.php'; ?>


**create_مهام.js**
javascript
// Get form element
const form = document.getElementById('create-form');

// Add event listener to form submission
form.addEventListener('submit', (e) => {
    // Prevent default form submission
    e.preventDefault();

    // Get form data
    const formData = new FormData(form);

    // Send AJAX request to backend
    fetch('../backend/مهام.php', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        // Redirect back to list page
        window.location.href = 'list_مهام.php';
    })
    .catch((error) => console.error(error));
});


**backend/مهام.php**

<?php
// Include database connection
require_once '../db.php';

// Check if form data has been sent
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $due_date = trim($_POST['due_date']);

    // Insert new record into database
    $sql = "INSERT INTO مهام (name, description, due_date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $name, $description, $due_date);
    $stmt->execute();
    $stmt->close();

    // Output success message
    echo json_encode(['success' => true]);
} else {
    // Output error message
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}