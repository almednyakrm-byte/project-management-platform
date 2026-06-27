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
        .table-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 1rem;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 0.25rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-container table th, .table-container table td {
            border: 1px solid #ddd;
            padding: 0.5rem;
            text-align: left;
        }
        .table-container table th {
            background-color: #f0f0f0;
        }
        .search-bar {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 0.25rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 0.5rem;
            border: none;
            border-radius: 0.25rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .add-new-item {
            background-color: #1a1d23;
            color: #fff;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.25rem;
            cursor: pointer;
        }
        .add-new-item:hover {
            background-color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-lg font-bold">أفراد</span>
        <a href="logout.php">تسجيل الخروج</a>
        <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>اسم</th>
                    <th>تاريخ الميلاد</th>
                    <th>جنس</th>
                    <th>حالة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table rows will be populated here -->
            </tbody>
        </table>
    </div>
    <div class="search-bar">
        <input type="search" id="search-input" placeholder="بحث...">
        <button class="add-new-item" onclick="searchRecords()">بحث</button>
    </div>
    <button class="add-new-item" onclick="location.href='create_أفراد.php'">إضافة جديد</button>

    <script>
        // Fetch API to get list of records
        async function getRecords() {
            try {
                const response = await fetch('../backend/أفراد.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                const data = await response.json();
                populateTable(data);
            } catch (error) {
                console.error(error);
            }
        }

        // Populate table with records
        function populateTable(data) {
            const tableBody = document.getElementById('table-body');
            tableBody.innerHTML = '';
            data.forEach((record) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.اسم}</td>
                    <td>${record.تاريخ_الميلاد}</td>
                    <td>${record.جنس}</td>
                    <td>${record.حالة}</td>
                    <td>
                        <a href="edit_أفراد.php?id=${record.id}">تعديل</a>
                        <button class="delete-button" data-id="${record.id}">حذف</button>
                    </td>
                `;
                tableBody.appendChild(row);
            });
        }

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search-input').value;
            const tableBody = document.getElementById('table-body');
            const records = getRecordsFromBackend(searchInput);
            populateTable(records);
        }

        // Get records from backend based on search query
        async function getRecordsFromBackend(searchQuery) {
            try {
                const response = await fetch('../backend/أفراد.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    params: {
                        search: searchQuery
                    }
                });
                const data = await response.json();
                return data;
            } catch (error) {
                console.error(error);
                return [];
            }
        }

        // Delete record
        function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                fetch('../backend/أفراد.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(() => getRecords())
                .catch((error) => console.error(error));
            }
        }

        // Add event listeners to delete buttons
        const deleteButtons = document.querySelectorAll('.delete-button');
        deleteButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                deleteRecord(id);
            });
        });

        // Get records on page load
        getRecords();
    </script>
</body>
</html>

This code includes the following features:

1. Session validation: Redirects to login.php if the user is not authenticated.
2. Header navigation: Links to index.php, current user info, and logout.
3. Table showing list of records: Includes actions to edit and delete records.
4. 'Add New Item' button: Links to create_أفراد.php.
5. Search bar: Filters elements in real-time using AJAX.
6. AJAX Javascript: Fetches list records from '../backend/أفراد.php' (GET) and DELETE requests.

Note: This code assumes that the backend API is implemented in PHP and is located at '../backend/أفراد.php'. The API should return a JSON response containing the list of records.