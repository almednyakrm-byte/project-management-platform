**create_أنشطة.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-lg font-bold mb-4">إضافة أنشطة جديدة</h2>
        <form id="create-activity-form" class="space-y-4">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">عنوان</label>
                <input type="text" id="title" name="title" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">وصف</label>
                <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required></textarea>
            </div>
            <div>
                <label for="date" class="block text-sm font-medium text-gray-700">تاريخ</label>
                <input type="date" id="date" name="date" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div>
                <label for="time" class="block text-sm font-medium text-gray-700">وقت</label>
                <input type="time" id="time" name="time" class="block w-full px-4 py-2 text-sm text-gray-700 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">حفظ</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#create-activity-form').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/أنشطة.php',
                data: formData,
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_أنشطة.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**backend/أنشطة.php**

<?php
// Include database connection
include 'db_connection.php';

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate form data
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $date = filter_var($_POST['date'], FILTER_SANITIZE_STRING);
    $time = filter_var($_POST['time'], FILTER_SANITIZE_STRING);

    // Insert data into database
    $query = "INSERT INTO أنشطة (title, description, date, time) VALUES ('$title', '$description', '$date', '$time')";
    $result = mysqli_query($conn, $query);

    // Check if data is inserted successfully
    if ($result) {
        echo 'success';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}

// Close database connection
mysqli_close($conn);
?>