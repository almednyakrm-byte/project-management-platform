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
    <title>Reports</title>
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
        <h1 class="text-3xl font-bold mb-4">Reports</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_reports.php'">Add New Item</button>
            <input type="search" id="search" class="py-2 pl-10 text-sm text-gray-700" placeholder="Search...">
        </div>
        <table id="reports-table" class="w-full table-auto border border-gray-200">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="reports-tbody">
                <!-- Table data will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch reports data from backend
        fetch('../backend/reports.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('reports-tbody');
                data.forEach(report => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${report.id}</td>
                        <td class="px-4 py-2">${report.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_reports.php?id=${report.id}" class="text-blue-500 hover:text-blue-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteReport(${report.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete report
        function deleteReport(id) {
            fetch('../backend/reports.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the deleted report from the table
                    const rows = document.getElementById('reports-tbody').children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            rows[i].remove();
                            break;
                        }
                    }
                }
            });
        }

        // Search functionality
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.getElementById('reports-tbody').children;
            for (let i = 0; i < rows.length; i++) {
                const rowText = rows[i].textContent.toLowerCase();
                if (rowText.includes(searchValue)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>