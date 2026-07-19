**list_أفراد.php**

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
    <title>أفراد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            padding: 1rem;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
        }
        .search-bar input {
            width: 100%;
            height: 2rem;
            padding: 0.5rem;
            font-size: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar button {
            background-color: #1a1d23;
            color: #fff;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-teal-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-emerald-600 mb-4">أفراد</h1>
        <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_أفراد.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم</th>
                    <th>عنوان</th>
                    <th>تاريخ الميلاد</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = json_decode(file_get_contents('../backend/أفراد.php'), true);
                foreach ($records as $record) {
                    echo '<tr>';
                    echo '<td>' . $record['اسم'] . '</td>';
                    echo '<td>' . $record['عنوان'] . '</td>';
                    echo '<td>' . $record['تاريخ الميلاد'] . '</td>';
                    echo '<td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(' . $record['id'] . ')">حذف</button></td>';
                    echo '<td><a href="edit_أفراد.php?id=' . $record['id'] . '" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</a></td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetch('../backend/أفراد.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record['اسم']}</td>
                            <td>${record['عنوان']}</td>
                            <td>${record['تاريخ الميلاد']}</td>
                            <td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record['id']})">حذف</button></td>
                            <td><a href="edit_أفراد.php?id=${record['id']}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">تعديل</a></td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/أفراد.php', {
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
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف السجل');
                    }
                });
            }
        }
    </script>
</body>
</html>

**backend/أفراد.php**

<?php
// Fetch records from database
$records = array();
// Replace with your database connection and query
$records = array(
    array('id' => 1, 'اسم' => 'اسم 1', 'عنوان' => 'عنوان 1', 'تاريخ الميلاد' => 'تاريخ الميلاد 1'),
    array('id' => 2, 'اسم' => 'اسم 2', 'عنوان' => 'عنوان 2', 'تاريخ الميلاد' => 'تاريخ الميلاد 2'),
    array('id' => 3, 'اسم' => 'اسم 3', 'عنوان' => 'عنوان 3', 'تاريخ الميلاد' => 'تاريخ الميلاد 3')
);
echo json_encode($records);

Note: This code assumes you have a backend script (`backend/أفراد.php`) that fetches records from a database and returns them in JSON format. You should replace the hardcoded records with your actual database query. Additionally, you should implement the necessary security measures to prevent SQL injection and other security vulnerabilities.