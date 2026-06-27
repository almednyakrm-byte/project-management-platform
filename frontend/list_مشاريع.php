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
            background-color: #1f2937;
            padding: 1rem;
            text-align: center;
        }
        .header .logo {
            font-size: 1.5rem;
            color: #ffffff;
        }
        .header .nav-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header .nav-links li {
            margin-right: 20px;
        }
        .header .nav-links a {
            color: #ffffff;
            text-decoration: none;
        }
        .header .user-info {
            display: flex;
            align-items: center;
        }
        .header .user-info img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            margin-right: 10px;
        }
        .header .logout {
            background-color: #1f2937;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            color: #ffffff;
            cursor: pointer;
        }
        .header .logout:hover {
            background-color: #2c3e50;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .table th {
            background-color: #f0f0f0;
        }
        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .actions .edit {
            background-color: #1f2937;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            color: #ffffff;
            cursor: pointer;
        }
        .actions .edit:hover {
            background-color: #2c3e50;
        }
        .actions .delete {
            background-color: #1f2937;
            border: none;
            padding: 10px 20px;
            font-size: 1rem;
            color: #ffffff;
            cursor: pointer;
        }
        .actions .delete:hover {
            background-color: #2c3e50;
        }
        .search-bar {
            width: 50%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">مشاريع</div>
        <div class="nav-links">
            <ul>
                <li><a href="index.php">الصفحة الرئيسية</a></li>
                <li><a href="logout.php">تسجيل الخروج</a></li>
            </ul>
        </div>
        <div class="user-info">
            <img src="profile.jpg" alt="User Image">
            <span><?php echo $_SESSION['username']; ?></span>
        </div>
        <button class="logout">تسجيل الخروج</button>
    </div>
    <div class="container mx-auto p-4">
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_مشاريع.php'">إضافة مشروع جديد</button>
        <input type="search" class="search-bar" placeholder="بحث" id="search-input">
        <table class="table">
            <thead>
                <tr>
                    <th>اسم المشروع</th>
                    <th>وصف المشروع</th>
                    <th>تاريخ الإنشاء</th>
                    <th>تاريخ النهاية</th>
                    <th>حالة المشروع</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <?php
                // Fetch data from backend
                $response = file_get_contents('../backend/مشاريع.php');
                $data = json_decode($response, true);
                foreach ($data as $project) {
                    echo '<tr>';
                    echo '<td>' . $project['name'] . '</td>';
                    echo '<td>' . $project['description'] . '</td>';
                    echo '<td>' . $project['created_at'] . '</td>';
                    echo '<td>' . $project['ended_at'] . '</td>';
                    echo '<td>' . $project['status'] . '</td>';
                    echo '<td class="actions">';
                    echo '<button class="edit bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="location.href=\'edit_مشاريع.php?id=' . $project['id'] . '\'">تعديل</button>';
                    echo '<button class="delete bg-slate-900 hover:bg-slate-700 text-white font-bold py-2 px-4 rounded" onclick="deleteProject(' . $project['id'] . ')">حذف</button>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Search bar functionality
        const searchInput = document.getElementById('search-input');
        searchInput.addEventListener('input', function() {
            const searchQuery = searchInput.value.toLowerCase();
            const tableRows = document.querySelectorAll('#table-body tr');
            tableRows.forEach(row => {
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchQuery)) {
                    row.style.display = 'table-row';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Delete project functionality
        function deleteProject(id) {
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
                    alert('مشروع حذف بنجاح');
                    location.reload();
                } else {
                    alert('خطأ في الحذف');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>

Note: This code assumes that the backend API is already implemented and returns a JSON response with the list of projects. The `deleteProject` function sends a DELETE request to the backend API to delete the project with the specified ID.