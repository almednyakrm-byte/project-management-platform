**create_مشاريع.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include Tailwind CSS
?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<?php
// Include navigation
include 'navigation.php';
?>

<div class="container mx-auto p-4 mt-6">
    <h1 class="text-3xl font-bold text-slate-900">إضافة مشروع جديد</h1>
    <form id="create-project-form" class="bg-white p-4 rounded shadow-md">
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="name" class="block text-sm font-medium text-slate-900">اسم المشروع</label>
            <input type="text" id="name" name="name" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="description" class="block text-sm font-medium text-slate-900">وصف المشروع</label>
            <textarea id="description" name="description" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"></textarea>
        </div>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="start_date" class="block text-sm font-medium text-slate-900">تاريخ بداية المشروع</label>
            <input type="date" id="start_date" name="start_date" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <label for="end_date" class="block text-sm font-medium text-slate-900">تاريخ نهاية المشروع</label>
            <input type="date" id="end_date" name="end_date" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
        </div>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة المشروع</button>
    </form>
</div>

<?php
// Include footer
include 'footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-project-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/مشاريع.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_مشاريع.php';
                    } else {
                        alert('Error adding project');
                    }
                }
            });
        });
    });
</script>


**Note:** Make sure to replace `header.php`, `navigation.php`, and `footer.php` with your actual header, navigation, and footer files. Also, ensure that the `../backend/مشاريع.php` file exists and is correctly configured to handle the form data.