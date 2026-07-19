**create_أفراد.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">إضافة أفراد جديد</h2>
        <form id="create-form" class="space-y-4">
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="name">اسم</label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="name" type="text" required>
                </div>
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="email">بريد إلكتروني</label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="email" type="email" required>
                </div>
            </div>
            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="phone">رقم هاتف</label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="phone" type="tel" required>
                </div>
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="address">عنوان</label>
                    <input class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="address" type="text" required>
                </div>
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">إضافة</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/أفراد.php',
                data: formData,
                success: function(data) {
                    if (data == 'success') {
                        window.location.href = 'list_أفراد.php';
                    } else {
                        alert('Error: ' + data);
                    }
                }
            });
        });
    });
</script>

<?php
include 'footer.php';
?>


**backend/أفراد.php**

<?php
// Check if form data is submitted
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['address'])) {
    // Insert data into database
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Database connection
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query
    $sql = "INSERT INTO أفراد (name, email, phone, address) VALUES ('$name', '$email', '$phone', '$address')";

    // Execute query
    if ($conn->query($sql) === TRUE) {
        echo 'success';
    } else {
        echo 'Error: ' . $sql . '<br>' . $conn->error;
    }

    // Close connection
    $conn->close();
}
?>