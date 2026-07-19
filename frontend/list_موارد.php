**list_موارد.php**

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
    <title>موارد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #0d6efd;
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
            text-align: center;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span class="text-lg font-bold">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">موارد</h1>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_موارد.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search" placeholder="بحث...">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم</th>
                    <th>اسم</th>
                    <th>حالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record['id']; ?></td>
                        <td><?php echo $record['name']; ?></td>
                        <td><?php echo $record['status']; ?></td>
                        <td>
                            <a href="edit_موارد.php?id=<?php echo $record['id']; ?>" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchRecords() {
            const search = document.getElementById('search').value;
            fetch('../backend/موارد.php?search=' + search)
                .then(response => response.json())
                .then(data => {
                    const records = document.getElementById('records');
                    records.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.id}</td>
                            <td>${record.name}</td>
                            <td>${record.status}</td>
                            <td>
                                <a href="edit_موارد.php?id=${record.id}" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        records.appendChild(row);
                    });
                });
        }

        function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                fetch('../backend/موارد.php', {
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
                })
                .catch(error => console.error('Error:', error));
            }
        }

        function fetchRecords() {
            return fetch('../backend/موارد.php')
                .then(response => response.json())
                .then(data => data.records);
        }
    </script>
</body>
</html>

**backend/موارد.php**

<?php
// Database connection
$conn = mysqli_connect('localhost', 'username', 'password', 'database');

// Search query
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM records WHERE name LIKE '%$search%'";
} else {
    $query = "SELECT * FROM records";
}

// Fetch records
$result = mysqli_query($conn, $query);
$records = array();
while ($row = mysqli_fetch_assoc($result)) {
    $records[] = $row;
}

// Output records
header('Content-Type: application/json');
echo json_encode(array('records' => $records));

// Delete record
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $id = $_POST['id'];
    $query = "DELETE FROM records WHERE id = '$id'";
    mysqli_query($conn, $query);
    echo json_encode(array('success' => true));
}

// Close database connection
mysqli_close($conn);
?>