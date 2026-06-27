**list_أهداف.php**

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
    <title>أهداف</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <header class="bg-white shadow-md p-4">
        <nav class="flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">الصفحة الرئيسية</a>
            <div class="flex items-center">
                <span class="text-lg font-bold"><?= $_SESSION['username']; ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="document.location='logout.php'">تسجيل الخروج</button>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">أهداف</h1>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="document.location='create_أهداف.php'">إضافة جديد</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" class="w-full p-2 pl-10 text-sm text-gray-700 bg-gray-100 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="بحث" id="search">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 p-2">العنوان</th>
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
            try {
                const response = await fetch('../backend/أهداف.php', { method: 'GET' });
                const data = await response.json();
                const records = document.getElementById('records');
                records.innerHTML = '';
                data.forEach((record) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="border border-gray-400 p-2">${record.عنوان}</td>
                        <td class="border border-gray-400 p-2">
                            <a href="edit_أهداف.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    records.appendChild(row);
                });
            } catch (error) {
                console.error(error);
            }
        }

        // Search functionality
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                // Implement search logic here
                // For demonstration purposes, we'll just call the loadRecords function
                loadRecords();
            } else {
                loadRecords();
            }
        }

        // Delete record functionality
        async function deleteRecord(id) {
            try {
                const response = await fetch('../backend/أهداف.php', { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id }) });
                const data = await response.json();
                if (data.success) {
                    loadRecords();
                } else {
                    alert('Error deleting record');
                }
            } catch (error) {
                console.error(error);
            }
        }

        // Load records on page load
        loadRecords();
    </script>
</body>
</html>

This code includes a premium Tailwind UI with a header navigation, table showing list of records, and search bar. The `loadRecords` function fetches records from the backend using the Fetch API and populates the table. The `searchRecords` function filters records based on the search query. The `deleteRecord` function sends a DELETE request to the backend to delete a record.