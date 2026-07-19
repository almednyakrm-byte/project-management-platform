**list_تقارير.php**

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
    <title>تقارير</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
    </style>
</head>
<body>
    <header class="bg-gray-800 text-white py-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="mr-2 text-lg font-bold"><?= $_SESSION['username']; ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='logout.php'">تسجيل الخروج</button>
            </div>
        </div>
    </header>
    <main class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">تقارير</h1>
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="document.location='create_تقارير.php'">إضافة جديد</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" class="w-full p-2 mr-2 text-lg" placeholder="بحث" id="search">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 p-2">عنوان</th>
                    <th class="border border-gray-400 p-2">تاريخ الإضافة</th>
                    <th class="border border-gray-400 p-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = json_decode(file_get_contents('../backend/تقارير.php'), true);
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td class="border border-gray-400 p-2"><?= $record['title']; ?></td>
                        <td class="border border-gray-400 p-2"><?= $record['date']; ?></td>
                        <td class="border border-gray-400 p-2">
                            <a href="edit_تقارير.php?id=<?= $record['id']; ?>" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?= $record['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </main>
    <script>
        // Search bar functionality
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetch('../backend/تقارير.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="border border-gray-400 p-2">${record.title}</td>
                            <td class="border border-gray-400 p-2">${record.date}</td>
                            <td class="border border-gray-400 p-2">
                                <a href="edit_تقارير.php?id=${record.id}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        // Delete record functionality
        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/تقارير.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف السجل بنجاح');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                });
            }
        }
    </script>
</body>
</html>

**backend/تقارير.php**

<?php
// Fetch records from database
$records = array();
// Replace with your database connection and query
// $conn = mysqli_connect('localhost', 'username', 'password', 'database');
// $query = mysqli_query($conn, 'SELECT * FROM table_name');
// while ($row = mysqli_fetch_assoc($query)) {
//     $records[] = $row;
// }
// mysqli_close($conn);

// Return records as JSON
header('Content-Type: application/json');
echo json_encode($records);
?>

Note: Replace the database connection and query in the `backend/تقارير.php` file with your actual database credentials and query.