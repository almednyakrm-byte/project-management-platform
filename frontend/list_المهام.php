<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المهام</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-white">
    <header class="bg-indigo-500 py-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="mr-4"><?= $_SESSION['username'] ?></span>
                <a href="logout.php" class="text-lg font-bold">تسجيل الخروج</a>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <h1 class="text-3xl font-bold mb-4">المهام</h1>
        <div class="flex justify-between mb-4">
            <a href="create_المهام.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
            <input type="search" id="search" class="bg-slate-800 text-white py-2 px-4 rounded" placeholder="بحث...">
        </div>
        <table id="table" class="w-full text-center">
            <thead class="bg-slate-800">
                <tr>
                    <th class="py-2">الاسم</th>
                    <th class="py-2">الوصف</th>
                    <th class="py-2">العمليات</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table data will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch table data from backend
        fetch('../backend/المهام.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('table-body');
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.name}</td>
                        <td>${item.description}</td>
                        <td>
                            <a href="edit_المهام.php?id=${item.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                            <button class="text-red-500 hover:text-red-700" onclick="deleteItem(${item.id})">حذف</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            });

        // Delete item using AJAX
        function deleteItem(id) {
            fetch('../backend/المهام.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the deleted row from the table
                    const tableBody = document.getElementById('table-body');
                    const rows = tableBody.children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].querySelector('td:nth-child(3) a').href.includes(`id=${id}`)) {
                            tableBody.removeChild(rows[i]);
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
            const rows = document.getElementById('table-body').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>