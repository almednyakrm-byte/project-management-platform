**list_تعاونيات.php**

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
    <title>تعاونيات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            color: #fff;
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
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            font-size: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500">مرحباً <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-red-500">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">تعاونيات</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_تعاونيات.php'">إضافة جديد</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" class="search-bar" placeholder="بحث" id="search-input">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم التعاونية</th>
                    <th>العنوان</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <?php
                // Fetch records from backend
                $records = json_decode(file_get_contents('../backend/تعاونيات.php'), true);
                foreach ($records as $record) {
                    echo '<tr>';
                    echo '<td>' . $record['name'] . '</td>';
                    echo '<td>' . $record['address'] . '</td>';
                    echo '<td>';
                    echo '<a href="edit_تعاونيات.php?id=' . $record['id'] . '" class="text-indigo-500 hover:text-indigo-700">تعديل</a>';
                    echo '<button class="text-red-500 hover:text-red-700" onclick="deleteRecord(' . $record['id'] . ')">حذف</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const searchInput = document.getElementById('search-input').value;
            fetch('../backend/تعاونيات.php?search=' + searchInput)
                .then(response => response.json())
                .then(data => {
                    const recordsTable = document.getElementById('records-table');
                    recordsTable.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.name}</td>
                            <td>${record.address}</td>
                            <td>
                                <a href="edit_تعاونيات.php?id=${record.id}" class="text-indigo-500 hover:text-indigo-700">تعديل</a>
                                <button class="text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsTable.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل تريد حذف التعاونية؟')) {
                fetch('../backend/تعاونيات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف التعاونية بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف التعاونية');
                    }
                });
            }
        }
    </script>
</body>
</html>

**backend/تعاونيات.php**

<?php
// Fetch records from database
$records = array();
// Simulating data for demonstration purposes
$records[] = array('id' => 1, 'name' => 'تعاونية 1', 'address' => 'عنوان 1');
$records[] = array('id' => 2, 'name' => 'تعاونية 2', 'address' => 'عنوان 2');
$records[] = array('id' => 3, 'name' => 'تعاونية 3', 'address' => 'عنوان 3');

// Search functionality
if (isset($_GET['search'])) {
    $searchTerm = $_GET['search'];
    $records = array_filter($records, function($record) use ($searchTerm) {
        return strpos($record['name'], $searchTerm) !== false || strpos($record['address'], $searchTerm) !== false;
    });
}

// Delete record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_POST['id'];
    // Simulating deletion for demonstration purposes
    $records = array_filter($records, function($record) use ($id) {
        return $record['id'] !== $id;
    });
    echo json_encode(array('success' => true));
    exit;
}

// Output records in JSON format
echo json_encode($records);