**edit_مشاريع.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get project ID from URL
$id = $_GET['id'];

// Fetch project details via AJAX
$project = json_decode(file_get_contents('../backend/مشاريع.php?id=' . $id), true);

// Check if project exists
if (empty($project)) {
    echo 'Project not found';
    exit;
}

// Set page title and mod slug
$page_title = 'Edit Project';
$mod_slug = 'projects';

// Include header and navigation
include 'header.php';
?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $page_title ?></h1>

    <!-- Form -->
    <form id="edit-project-form" class="bg-white rounded shadow-md p-4">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
            <input type="text" id="name" name="name" class="block w-full p-2 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $project['name'] ?>">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
            <textarea id="description" name="description" class="block w-full p-2 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"><?= $project['description'] ?></textarea>
        </div>

        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
    </form>
</div>

<!-- JavaScript -->
<script>
    // Fetch project details via AJAX
    fetch('../backend/مشاريع.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            // Populate form fields
            document.getElementById('name').value = data.name;
            document.getElementById('description').value = data.description;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX
    document.getElementById('edit-project-form').addEventListener('submit', event => {
        event.preventDefault();

        // Get form data
        const formData = new FormData(event.target);

        // Send PUT request to backend
        fetch('../backend/مشاريع.php', {
            method: 'PUT',
            body: formData,
            headers: {
                'X-CSRF-Token': '<?= $_SESSION['csrf_token'] ?>'
            }
        })
            .then(response => response.json())
            .then(data => {
                // Redirect to list page
                window.location.href = 'list_' + <?= $mod_slug ?> + '.php';
            })
            .catch(error => console.error(error));
    });
</script>

<!-- Include footer -->
<?php include 'footer.php'; ?>


**header.php**

<?php
// Include session start
include 'session.php';

// Include navigation
include 'navigation.php';
?>


**footer.php**

<?php
// Include copyright information
include 'copyright.php';
?>


**session.php**

<?php
// Start session
session_start();

// Check if CSRF token exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>


**copyright.php**

<p class="text-sm text-slate-900">Copyright &copy; <?= date('Y') ?> Your Company</p>


**navigation.php**

<nav class="bg-slate-900 py-4">
    <div class="container mx-auto px-4">
        <ul class="flex justify-between items-center">
            <li><a href="index.php" class="text-sm text-slate-900 hover:text-indigo-500">Home</a></li>
            <li><a href="list_projects.php" class="text-sm text-slate-900 hover:text-indigo-500">Projects</a></li>
            <li><a href="login.php" class="text-sm text-slate-900 hover:text-indigo-500">Login</a></li>
            <li><a href="logout.php" class="text-sm text-slate-900 hover:text-indigo-500">Logout</a></li>
        </ul>
    </div>
</nav>


**backend/مشاريع.php**

<?php
// Check if PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Get project ID
    $id = $_GET['id'];

    // Get form data
    $name = $_POST['name'];
    $description = $_POST['description'];

    // Update project in database
    // ...

    // Return success message
    echo json_encode(['message' => 'Project updated successfully']);
    exit;
}

// Check if GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get project ID
    $id = $_GET['id'];

    // Fetch project from database
    // ...

    // Return project details
    echo json_encode($project);
    exit;
}
?>