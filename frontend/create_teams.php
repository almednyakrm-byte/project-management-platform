<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Get module slug
$mod_slug = 'teams';

// Page title
$page_title = 'Create Team';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 pt-6 mt-10 bg-gray-200 rounded-lg shadow-md">
        <h2 class="text-2xl text-blue-500"><?php echo $page_title; ?></h2>
        <form id="create-team-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required></textarea>
            </div>
            <div class="mb-4">
                <label for="leader" class="block text-sm font-medium text-gray-700">Leader</label>
                <input type="text" id="leader" name="leader" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <button type="submit" class="py-2 px-4 bg-blue-500 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-blue-500 focus:border-blue-500">Create Team</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-team-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/teams.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>