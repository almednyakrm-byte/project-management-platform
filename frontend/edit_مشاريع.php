**edit_مشاريع.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get project ID from URL
$id = $_GET['id'];

// Fetch existing project details via AJAX
$script = '<script>
    fetch("../backend/مشاريع.php?id=' . $id . '")
    .then(response => response.json())
    .then(data => {
        document.getElementById("title").value = data.title;
        document.getElementById("description").value = data.description;
    });
</script>';

// Include HTML form
include 'edit_form.php';
?>

<?php echo $script; ?>


**edit_form.php**

<div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">Edit Project</h2>
    <form id="edit-form" class="space-y-6">
        <div>
            <label for="title" class="block text-sm font-medium text-slate-700">Title</label>
            <input type="text" id="title" name="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-slate-700">Description</label>
            <textarea id="description" name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
        </div>
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Save Changes</button>
    </form>
</div>

<script>
    document.getElementById("edit-form").addEventListener("submit", function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch("../backend/مشاريع.php", {
            method: "PUT",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = "list_مشاريع.php";
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error(error));
    });
</script>


**backend/مشاريع.php**

<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get project ID from URL
$id = $_GET['id'];

// Fetch existing project details
$project = get_project($id);

// Update project details
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    update_project($id, $title, $description);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

function get_project($id) {
    // Database query to fetch project details
    // ...
}

function update_project($id, $title, $description) {
    // Database query to update project details
    // ...
}
?>