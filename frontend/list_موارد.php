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
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            padding: 1rem;
            border: 1px solid #ddd;
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
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-indigo-500">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php" class="text-red-500">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-slate-900 mb-4">موارد</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_موارد.php'">إضافة جديد</button>
        <div class="search-bar mt-4">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>اسم</th>
                    <th>وصف</th>
                    <th>حذف</th>
                    <th>تعديل</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch API to load records
        async function loadRecords() {
            const response = await fetch('../backend/موارد.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            const data = await response.json();
            const recordsTable = document.getElementById('records-table');
            recordsTable.innerHTML = '';
            data.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.اسم}</td>
                    <td>${record.وصف}</td>
                    <td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button></td>
                    <td><button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_موارد.php?id=${record.id}'">تعديل</button></td>
                `;
                recordsTable.appendChild(row);
            });
        }

        // Search function
        function searchRecords() {
            const searchInput = document.getElementById('search-input').value;
            const recordsTable = document.getElementById('records-table');
            recordsTable.innerHTML = '';
            fetch('../backend/موارد.php', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                },
                params: {
                    search: searchInput
                }
            })
            .then(response => response.json())
            .then(data => {
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.اسم}</td>
                        <td>${record.وصف}</td>
                        <td><button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button></td>
                        <td><button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='edit_موارد.php?id=${record.id}'">تعديل</button></td>
                    `;
                    recordsTable.appendChild(row);
                });
            });
        }

        // Delete record function
        async function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                const response = await fetch('../backend/موارد.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                });
                if (response.ok) {
                    loadRecords();
                } else {
                    alert('حدث خطأ أثناء الحذف');
                }
            }
        }

        // Load records on page load
        loadRecords();
    </script>
</body>
</html>

**backend/موارد.php**

<?php
// Database connection
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Search function
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM records WHERE اسم LIKE '%$search%' OR وصف LIKE '%$search%'";
} else {
    $query = "SELECT * FROM records";
}

// Fetch records
$result = $conn->query($query);
$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Output records
header('Content-Type: application/json');
echo json_encode($data);

// Delete record
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM records WHERE id = '$id'";
    $conn->query($query);
    echo json_encode(array('success' => true));
}

// Close database connection
$conn->close();
?>