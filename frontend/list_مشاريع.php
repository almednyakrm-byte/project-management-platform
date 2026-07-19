**list_مشاريع.php**

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
    <title>مشاريع</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d3748;
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
            background-color: #2d3748;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500 font-bold">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-red-500 hover:text-red-700">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-slate-900 mb-4">مشاريع</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_مشاريع.php'">اضافة مشروع جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المشروع</th>
                    <th>تاريخ بداية المشروع</th>
                    <th>تاريخ نهاية المشروع</th>
                    <th>حالة المشروع</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    echo '<tr>';
                    echo '<td>' . $record['name'] . '</td>';
                    echo '<td>' . $record['start_date'] . '</td>';
                    echo '<td>' . $record['end_date'] . '</td>';
                    echo '<td>' . $record['status'] . '</td>';
                    echo '<td>';
                    echo '<a href="edit_مشاريع.php?id=' . $record['id'] . '" class="text-indigo-500 hover:text-indigo-700">تعديل</a>';
                    echo '<button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(' . $record['id'] . ')">حذف</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetch('../backend/مشاريع.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.name}</td>
                            <td>${record.start_date}</td>
                            <td>${record.end_date}</td>
                            <td>${record.status}</td>
                            <td>
                                <a href="edit_مشاريع.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا المشروع؟')) {
                fetch('../backend/مشاريع.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف المشروع بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف المشروع');
                    }
                });
            }
        }

        function fetchRecords() {
            return fetch('../backend/مشاريع.php')
                .then(response => response.json())
                .then(data => data.records);
        }
    </script>
</body>
</html>


**backend/مشاريع.php**

<?php
// Fetch records from database
$records = array();
$records[] = array(
    'id' => 1,
    'name' => 'مشروع اول',
    'start_date' => '2022-01-01',
    'end_date' => '2022-12-31',
    'status' => 'مفعل'
);
$records[] = array(
    'id' => 2,
    'name' => 'مشروع ثاني',
    'start_date' => '2023-01-01',
    'end_date' => '2023-12-31',
    'status' => 'مفعل'
);

// Search functionality
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $records = array_filter($records, function($record) use ($search) {
        return strpos($record['name'], $search) !== false;
    });
}

// Delete record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_POST['id'];
    // Delete record from database
    // ...
    echo json_encode(array('success' => true));
}

// Output records
echo json_encode(array('records' => $records));
?>