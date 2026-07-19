<?php
// create_projects.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

include_once '../config.php';
$mod_slug = 'projects';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Project</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-gray-200 rounded-md shadow-md">
        <h2 class="text-2xl text-blue-500 font-bold mb-4">Create Project</h2>
        <form id="create-project-form">
            <div class="mb-4">
                <label for="project_name" class="block text-gray-700 font-bold mb-2">Project Name:</label>
                <input type="text" id="project_name" name="project_name" class="block w-full p-2 bg-gray-200 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="project_description" class="block text-gray-700 font-bold mb-2">Project Description:</label>
                <textarea id="project_description" name="project_description" class="block w-full p-2 bg-gray-200 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <div class="mb-4">
                <label for="project_start_date" class="block text-gray-700 font-bold mb-2">Project Start Date:</label>
                <input type="date" id="project_start_date" name="project_start_date" class="block w-full p-2 bg-gray-200 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="project_end_date" class="block text-gray-700 font-bold mb-2">Project End Date:</label>
                <input type="date" id="project_end_date" name="project_end_date" class="block w-full p-2 bg-gray-200 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="project_status" class="block text-gray-700 font-bold mb-2">Project Status:</label>
                <select id="project_status" name="project_status" class="block w-full p-2 bg-gray-200 border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <button type="submit" class="w-full p-2 bg-blue-500 text-white font-bold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Create Project</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-project-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/projects.php',
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