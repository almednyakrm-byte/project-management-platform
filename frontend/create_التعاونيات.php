**create_التعاونيات.php**

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

// Include navigation
include 'navigation.php';

// Include form
include 'create_التعاونيات_form.php';

// Include footer
include 'footer.php';
?>


**create_التعاونيات_form.php**

<?php
// Include form header
include 'form_header.php';
?>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">إضافة تعاونية جديدة</h2>
    <form id="create_التعاونيات_form" class="space-y-4">
        <div class="grid grid-cols-1 gap-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">اسم التعاونية</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 placeholder-gray-400 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600" placeholder="اسم التعاونية">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">وصف التعاونية</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-700 placeholder-gray-400 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600" placeholder="وصف التعاونية"></textarea>
            </div>
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700">عنوان التعاونية</label>
                <input type="text" id="address" name="address" class="block w-full p-2 pl-10 text-sm text-gray-700 placeholder-gray-400 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600" placeholder="عنوان التعاونية">
            </div>
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 pl-10 text-sm text-gray-700 placeholder-gray-400 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600" placeholder="رقم الهاتف">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="block w-full p-2 pl-10 text-sm text-gray-700 placeholder-gray-400 border border-gray-300 rounded-lg focus:ring-emerald-600 focus:border-emerald-600" placeholder="البريد الإلكتروني">
            </div>
        </div>
        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg">إضافة تعاونية جديدة</button>
    </form>
</div>

<?php
// Include form footer
include 'form_footer.php';
?>


**form_header.php**

<div class="bg-teal-500 text-white p-4 rounded-lg mb-4">
    <h2 class="text-lg font-bold">إضافة تعاونية جديدة</h2>
</div>


**form_footer.php**

<script>
    $(document).ready(function() {
        $('#create_التعاونيات_form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/التعاونيات.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_التعاونيات.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>


**header.php** and **footer.php** and **navigation.php** are assumed to be existing files that include the necessary HTML and PHP code for the header, footer, and navigation of the website.

**backend/التعاونيات.php** is assumed to be a PHP file that handles the form submission and adds a new record to the database.