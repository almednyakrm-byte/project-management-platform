<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Current user info
$current_user = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teams Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-blue-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Back to Index</a>
            <span class="text-lg font-bold">Welcome, <?= $current_user ?></span>
            <a href="logout.php" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">Teams Management</h1>
        <div class="flex justify-between mb-4">
            <a href="create_teams.php" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add New Item</a>
            <input type="text" id="search" class="py-2 pl-10 text-sm text-gray-700" placeholder="Search...">
        </div>
        <table id="teams-table" class="w-full table-auto border border-gray-200">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="teams-tbody">
                <!-- Table content will be populated via AJAX -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch teams data from backend
        fetch('../backend/teams.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('teams-tbody');
                data.forEach(team => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${team.id}</td>
                        <td class="px-4 py-2">${team.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_teams.php?id=${team.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteTeam(${team.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete team via AJAX
        function deleteTeam(id) {
            fetch('../backend/teams.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the deleted team from the table
                    const rows = document.getElementById('teams-tbody').children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            rows[i].remove();
                            break;
                        }
                    }
                } else {
                    console.error('Error deleting team:', data.error);
                }
            });
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const filter = searchInput.value.toLowerCase();
            const rows = document.getElementById('teams-tbody').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const name = row.children[1].textContent.toLowerCase();
                if (name.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>