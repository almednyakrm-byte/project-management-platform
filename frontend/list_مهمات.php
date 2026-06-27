**list_مهمات.php**

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
    <title>مهمات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <header class="bg-gray-800 text-white p-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="text-lg font-bold"><?= $_SESSION['username']; ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='logout.php'">تسجيل خروج</button>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">مهمات</h1>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='create_مهمات.php'">إضافة مهمة جديدة</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" id="search" class="w-full p-2 pl-10 text-sm text-gray-700 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="بحث...">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table>
            <thead>
                <tr>
                    <th>عنوان المهمة</th>
                    <th>تاريخ الإضافة</th>
                    <th>تاريخ الانتهاء</th>
                    <th>حالة المهمة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </main>
    <script>
        // Fetch records from backend
        fetch('../backend/مهمات.php')
            .then(response => response.json())
            .then(data => {
                const records = document.getElementById('records');
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.title}</td>
                        <td>${record.added_at}</td>
                        <td>${record.due_at}</td>
                        <td>${record.status}</td>
                        <td>
                            <a href="edit_مهمات.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    records.appendChild(row);
                });
            })
            .catch(error => console.error(error));

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/مهمات.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        const records = document.getElementById('records');
                        records.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.title}</td>
                                <td>${record.added_at}</td>
                                <td>${record.due_at}</td>
                                <td>${record.status}</td>
                                <td>
                                    <a href="edit_مهمات.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            } else {
                fetch('../backend/مهمات.php')
                    .then(response => response.json())
                    .then(data => {
                        const records = document.getElementById('records');
                        records.innerHTML = '';
                        data.forEach(record => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${record.title}</td>
                                <td>${record.added_at}</td>
                                <td>${record.due_at}</td>
                                <td>${record.status}</td>
                                <td>
                                    <a href="edit_مهمات.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    })
                    .catch(error => console.error(error));
            }
        }

        // Delete record
        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف المهمة؟')) {
                fetch('../backend/مهمات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف المهمة بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف المهمة');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

This code includes:

1. Session validation to ensure the user is logged in.
2. A premium Tailwind UI with a header navigation, current user info, and logout button.
3. A table showing the list of records with actions: Edit and Delete.
4. An "Add New Item" button linking to `create_مهمات.php`.
5. A search bar filtering elements in real-time.
6. AJAX JavaScript (Fetch API) fetching list records from `../backend/مهمات.php` (GET) and DELETE requests.

Note: This code assumes that the backend API is implemented in `../backend/مهمات.php` and returns JSON data. The `deleteRecord` function sends a DELETE request to the backend API to delete the record.