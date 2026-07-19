<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Current user info
$current_user = $_SESSION['username'];

// HTML content
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-blue-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <span class="text-lg font-bold">Welcome, <?php echo $current_user; ?></span>
            <a href="logout.php" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">Tasks List</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_tasks.php'">Add New Item</button>
            <input type="text" id="search" class="py-2 pl-10 text-sm text-gray-700" placeholder="Search...">
        </div>
        <table id="tasks-table" class="w-full table-auto border border-gray-200">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Task Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="tasks-tbody">
                <!-- Table content will be populated by JavaScript -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch tasks data from backend
        fetch('../backend/tasks.php')
            .then(response => response.json())
            .then(data => {
                const tasksTbody = document.getElementById('tasks-tbody');
                tasksTbody.innerHTML = '';
                data.forEach(task => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${task.id}</td>
                        <td class="px-4 py-2">${task.task_name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_tasks.php?id=${task.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteTask(${task.id})">Delete</button>
                        </td>
                    `;
                    tasksTbody.appendChild(row);
                });
            });

        // Delete task by ID
        function deleteTask(id) {
            fetch('../backend/tasks.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove deleted task from table
                    const tasksTbody = document.getElementById('tasks-tbody');
                    const rows = tasksTbody.children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            tasksTbody.removeChild(rows[i]);
                            break;
                        }
                    }
                } else {
                    console.error('Error deleting task:', data.error);
                }
            });
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const filter = searchInput.value.toLowerCase();
            const rows = document.getElementById('tasks-tbody').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const taskName = row.children[1].textContent.toLowerCase();
                if (taskName.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>