<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-blue-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <div class="flex items-center">
                <span class="mr-4">Welcome, <?php echo $_SESSION['username']; ?></span>
                <a href="logout.php" class="bg-gray-200 hover:bg-gray-300 text-blue-500 font-bold py-2 px-4 rounded">Logout</a>
            </div>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">Projects</h1>
        <div class="flex justify-between mb-4">
            <a href="create_projects.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add New Item</a>
            <input type="search" id="search" class="py-2 pl-10 text-sm text-gray-700" placeholder="Search...">
        </div>
        <table id="projects-table" class="w-full table-auto border border-gray-200">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="projects-tbody">
                <!-- Table data will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch projects data from backend
        fetch('../backend/projects.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('projects-tbody');
                data.forEach(project => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${project.id}</td>
                        <td class="px-4 py-2">${project.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_projects.php?id=${project.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700 ml-4" onclick="deleteProject(${project.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete project using AJAX
        function deleteProject(id) {
            fetch('../backend/projects.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the deleted project from the table
                    const rows = document.getElementById('projects-tbody').children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            rows[i].remove();
                            break;
                        }
                    }
                } else {
                    console.error('Error deleting project:', data.error);
                }
            });
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const filter = searchInput.value.toUpperCase();
            const rows = document.getElementById('projects-tbody').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const nameCell = row.children[1];
                if (nameCell.textContent.toUpperCase().indexOf(filter) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>