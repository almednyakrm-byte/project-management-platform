**list_أنشطة.php**

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
    <title>أنشطة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-gray-800 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="document.location='logout.php'">تسجيل الخروج</button>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">قائمة أنشطة</h1>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="document.location='create_أنشطة.php'">إضافة عنصر جديد</button>
        <div class="flex justify-between mb-4">
            <input type="search" id="search" class="w-full p-2 text-lg" placeholder="بحث...">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 p-2">العنوان</th>
                    <th class="border border-gray-400 p-2">التاريخ</th>
                    <th class="border border-gray-400 p-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </main>
    <script>
        // Fetch API to load records
        async function loadRecords() {
            const response = await fetch('../backend/أنشطة.php');
            const data = await response.json();
            const records = document.getElementById('records');
            records.innerHTML = '';
            data.forEach((record) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="border border-gray-400 p-2">${record.title}</td>
                    <td class="border border-gray-400 p-2">${record.date}</td>
                    <td class="border border-gray-400 p-2">
                        <a href="edit_أنشطة.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                records.appendChild(row);
            });
        }

        // Search functionality
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/أنشطة.php?search=' + searchQuery)
                    .then(response => response.json())
                    .then(data => {
                        const records = document.getElementById('records');
                        records.innerHTML = '';
                        data.forEach((record) => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td class="border border-gray-400 p-2">${record.title}</td>
                                <td class="border border-gray-400 p-2">${record.date}</td>
                                <td class="border border-gray-400 p-2">
                                    <a href="edit_أنشطة.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                                </td>
                            `;
                            records.appendChild(row);
                        });
                    });
            } else {
                loadRecords();
            }
        }

        // Delete record functionality
        async function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا العنصر؟')) {
                const response = await fetch('../backend/أنشطة.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id })
                });
                if (response.ok) {
                    loadRecords();
                } else {
                    alert('حدث خطأ أثناء حذف العنصر');
                }
            }
        }

        // Load records on page load
        loadRecords();
    </script>
</body>
</html>

This code includes a premium Tailwind UI with a header navigation, a table showing the list of records, and a search bar. The `loadRecords` function fetches the list of records from the backend using the Fetch API and populates the table. The `searchRecords` function filters the records in real-time based on the search query. The `deleteRecord` function sends a DELETE request to the backend to delete a record.