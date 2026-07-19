<?php
// Session validation
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Include database connection
include '../backend/db.php';

// Check if id is valid
$query = "SELECT * FROM الموظفين WHERE id = '$id'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
    header('Location: list_الموظفين.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الموظفين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 pt-6 md:p-6 lg:p-8 bg-white rounded shadow-md">
        <h2 class="text-3xl text-indigo-500 font-bold mb-4">تعديل الموظفين</h2>
        <form id="edit-form">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-900">الاسم</label>
                    <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-900">البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-900">الهاتف</label>
                    <input type="text" id="phone" name="phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="department" class="block text-sm font-medium text-slate-900">ال部门</label>
                    <select id="department" name="department" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">-- اختر Department --</option>
                        <!-- options will be populated dynamically -->
                    </select>
                </div>
            </div>
            <button type="submit" class="mt-4 py-2 px-4 bg-indigo-500 text-white rounded hover:bg-indigo-700 focus:ring-indigo-500">حفظ</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fetch existing record details
            $.ajax({
                type: 'GET',
                url: '../backend/الموظفين.php?id=<?php echo $id; ?>',
                dataType: 'json',
                success: function(data) {
                    // Populate form fields
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                    $('#department').val(data.department);
                }
            });

            // Submit form using AJAX
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/الموظفين.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = 'list_الموظفين.php';
                    }
                });
            });
        });
    </script>
</body>
</html>