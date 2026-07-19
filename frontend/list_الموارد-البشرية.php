**list_الموارد-البشرية.php**

<?php
// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الموارد البشرية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .bg-emerald-600 {
            background-color: #0d6efd;
        }
        .text-teal-500 {
            color: #0fc2c9;
        }
    </style>
</head>
<body class="bg-gray-100">
    <header class="bg-emerald-600 py-4">
        <div class="container mx-auto px-4">
            <nav class="flex justify-between">
                <a href="index.php" class="text-teal-500 hover:text-white">الرئيسية</a>
                <div class="flex items-center">
                    <span class="text-teal-500 hover:text-white">مرحباً <?= $_SESSION['username'] ?></span>
                    <a href="logout.php" class="ml-4 text-white hover:text-gray-300">تسجيل الخروج</a>
                </div>
            </nav>
        </div>
    </header>
    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl text-gray-800">الموارد البشرية</h1>
        <div class="flex justify-between mb-4">
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white py-2 px-4 rounded" onclick="location.href='create_الموارد-البشرية.php'">إضافة جديد</button>
            <input type="search" class="w-full py-2 pl-10 text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-600" placeholder="بحث" id="search" onkeyup="searchRecords()">
        </div>
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr>
                    <th class="px-4 py-2 bg-gray-100 text-gray-600">اسم</th>
                    <th class="px-4 py-2 bg-gray-100 text-gray-600">وظيفة</th>
                    <th class="px-4 py-2 bg-gray-100 text-gray-600">إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be fetched here -->
            </tbody>
        </table>
    </main>
    <script>
        // Fetch records from backend
        fetch('../backend/الموارد-البشرية.php')
            .then(response => response.json())
            .then(data => {
                const records = document.getElementById('records');
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${record.name}</td>
                        <td class="px-4 py-2">${record.job}</td>
                        <td class="px-4 py-2">
                            <a href="edit_الموارد-البشرية.php?id=${record.id}" class="text-emerald-600 hover:text-emerald-700">تعديل</a>
                            <button class="ml-4 text-red-600 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    records.appendChild(row);
                });
            })
            .catch(error => console.error(error));

        // Search functionality
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.toLowerCase();
            const records = document.getElementById('records');
            const rows = records.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell.textContent.toLowerCase().includes(searchQuery)) {
                        match = true;
                        break;
                    }
                }
                if (match) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        // Delete record functionality
        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/الموارد-البشرية.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const records = document.getElementById('records');
                        const rows = records.getElementsByTagName('tr');
                        for (let i = 0; i < rows.length; i++) {
                            const row = rows[i];
                            const cells = row.getElementsByTagName('td');
                            if (cells[0].textContent === data.name) {
                                records.removeChild(row);
                                break;
                            }
                        }
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

This code includes a premium Tailwind UI design with a specific color palette matching the theme. It also includes session validation, a search bar, and AJAX functionality for fetching and deleting records.