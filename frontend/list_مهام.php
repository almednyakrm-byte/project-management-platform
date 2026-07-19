**list_مهام.php**

<?php
// Session validation
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مهام</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1f2937;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #ffffff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ffffff;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
        }
        .table th {
            background-color: #1f2937;
            color: #ffffff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ddd;
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
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold text-white ml-4">مركز إدارة المهام</span>
        <a href="profile.php" class="text-lg font-bold text-white ml-4">حسناً</a>
        <a href="logout.php" class="text-lg font-bold text-white ml-4">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4 mt-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold text-slate-900">قائمة المهام</h2>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مهام.php'">إضافة مهام جديدة</button>
        </div>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المهام</th>
                    <th>تاريخ الإضافة</th>
                    <th>تاريخ الانتهاء</th>
                    <th>حالة المهام</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <?php
                // Fetch records from backend
                $records = fetchRecords();
                foreach ($records as $record) {
                    ?>
                    <tr>
                        <td><?php echo $record['name']; ?></td>
                        <td><?php echo date('Y-m-d', strtotime($record['added_at'])); ?></td>
                        <td><?php echo date('Y-m-d', strtotime($record['due_at'])); ?></td>
                        <td><?php echo $record['status']; ?></td>
                        <td>
                            <a href="edit_مهام.php?id=<?php echo $record['id']; ?>" class="text-lg font-bold text-indigo-500 hover:text-indigo-700">تعديل</a>
                            <button class="text-lg font-bold text-red-500 hover:text-red-700" onclick="deleteRecord(<?php echo $record['id']; ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Fetch records from backend
        async function fetchRecords() {
            const response = await fetch('../backend/مهام.php', { method: 'GET' });
            const data = await response.json();
            return data.records;
        }

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search-input').value;
            fetchRecords().then(records => {
                const tableBody = document.getElementById('records-table');
                tableBody.innerHTML = '';
                records.forEach(record => {
                    if (record.name.toLowerCase().includes(searchInput.toLowerCase())) {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.name}</td>
                            <td>${date('Y-m-d', strtotime(record.added_at))}</td>
                            <td>${date('Y-m-d', strtotime(record.due_at))}</td>
                            <td>${record.status}</td>
                            <td>
                                <a href="edit_مهام.php?id=${record.id}" class="text-lg font-bold text-indigo-500 hover:text-indigo-700">تعديل</a>
                                <button class="text-lg font-bold text-red-500 hover:text-red-700" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    }
                });
            });
        }

        // Delete record
        async function deleteRecord(id) {
            const response = await fetch('../backend/مهام.php', { method: 'DELETE', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ id: id }) });
            if (response.ok) {
                alert('تم حذف المهام بنجاح');
                location.reload();
            } else {
                alert('حدث خطأ أثناء حذف المهام');
            }
        }
    </script>
</body>
</html>


**backend/مهام.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch records
$records = array();
$query = "SELECT * FROM tasks";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
}

// Delete record
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $query = "DELETE FROM tasks WHERE id = '$id'";
    $conn->query($query);
}

// Output records
echo json_encode(array('records' => $records));
$conn->close();
?>


Note: This code assumes that you have a database named `database` with a table named `tasks` containing columns `id`, `name`, `added_at`, `due_at`, and `status`. You should replace the database connection details and table structure with your actual database configuration.