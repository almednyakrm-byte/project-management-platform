<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Module slug
$mod_slug = 'المهام';

// Page title
$page_title = 'Create ' . $mod_slug;

// Include header
include 'header.php';
?>

<main class="h-screen flex flex-col items-center justify-center">
    <div class="bg-slate-900 p-8 rounded-lg shadow-lg w-1/2">
        <h2 class="text-2xl text-indigo-500 mb-4"><?= $page_title ?></h2>
        <form id="create-form">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-200">Title</label>
                <input type="text" id="title" name="title" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-200">Description</label>
                <textarea id="description" name="description" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-200">Status</label>
                <select id="status" name="status" class="mt-1 block w-full py-2 pl-3 pr-10 text-base text-gray-200 border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="priority" class="block text-sm font-medium text-gray-200">Priority</label>
                <select id="priority" name="priority" class="mt-1 block w-full py-2 pl-3 pr-10 text-base text-gray-200 border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="due_date" class="block text-sm font-medium text-gray-200">Due Date</label>
                <input type="date" id="due_date" name="due_date" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <button type="submit" class="py-2 px-4 bg-indigo-500 text-white rounded-md hover:bg-indigo-700">Create</button>
        </form>
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/<?= $mod_slug ?>.php',
                data: $(this).serialize(),
                success: function() {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>