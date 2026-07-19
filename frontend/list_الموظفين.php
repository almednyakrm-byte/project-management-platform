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
    <title>الموظفين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-indigo-500">
    <header class="bg-indigo-500 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <span class="text-lg font-bold">مرحبا، <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="text-lg font-bold">تسجيل الخروج</a>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">الموظفين</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                <a href="create_الموظفين.php">إضافة جديد</a>
            </button>
            <input type="search" id="search" class="bg-slate-900 text-indigo-500 border border-indigo-500 p-2 rounded" placeholder="بحث...">
        </div>
        <table id="table" class="w-full text-center border border-indigo-500">
            <thead class="bg-indigo-500 text-white">
                <tr>
                    <th>الاسم</th>
                    <th>البريد الإلكتروني</th>
                    <th>العمليات</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table data will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to get list of records
        fetch('../backend/الموظفين.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('table-body');
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.name}</td>
                        <td>${item.email}</td>
                        <td>
                            <a href="edit_الموظفين.php?id=${item.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                            <button class="text-indigo-500 hover:text-indigo-700" onclick="deleteItem(${item.id})">حذف</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            });

        // Delete item using AJAX
        function deleteItem(id) {
            fetch('../backend/الموظفين.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error deleting item');
                }
            });
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.getElementById('table-body').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const name = row.children[0].textContent.toLowerCase();
                const email = row.children[1].textContent.toLowerCase();
                if (name.includes(searchValue) || email.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>