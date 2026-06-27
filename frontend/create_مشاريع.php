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

// Include navigation
include 'navigation.php';

?>

<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8 xl:p-12">
        <h2 class="text-slate-900 font-bold text-lg mb-4">إضافة مشروع جديد</h2>
        <form id="create-project-form">
            <div class="mb-4">
                <label for="project_name" class="text-slate-900 font-bold text-sm mb-2">اسم المشروع:</label>
                <input type="text" id="project_name" name="project_name" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="project_description" class="text-slate-900 font-bold text-sm mb-2">وصف المشروع:</label>
                <textarea id="project_description" name="project_description" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required></textarea>
            </div>
            <div class="mb-4">
                <label for="project_start_date" class="text-slate-900 font-bold text-sm mb-2">تاريخ بداية المشروع:</label>
                <input type="date" id="project_start_date" name="project_start_date" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            <div class="mb-4">
                <label for="project_end_date" class="text-slate-900 font-bold text-sm mb-2">تاريخ نهاية المشروع:</label>
                <input type="date" id="project_end_date" name="project_end_date" class="w-full p-2 text-slate-900 border border-slate-300 rounded-lg focus:outline-none focus:border-indigo-500" required>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">إضافة المشروع</button>
        </form>
    </div>
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
                    if (response == 'success') {
                        window.location.href = 'list_مشاريع.php';
                    } else {
                        alert('Error adding project');
                    }
                }
            });
        });
    });
</script>


**backend/مشاريع.php**

<?php
// Check if form data is submitted
if (isset($_POST['project_name']) && isset($_POST['project_description']) && isset($_POST['project_start_date']) && isset($_POST['project_end_date'])) {
    // Connect to database
    $conn = mysqli_connect('localhost', 'username', 'password', 'database');
    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    // Insert data into database
    $project_name = mysqli_real_escape_string($conn, $_POST['project_name']);
    $project_description = mysqli_real_escape_string($conn, $_POST['project_description']);
    $project_start_date = mysqli_real_escape_string($conn, $_POST['project_start_date']);
    $project_end_date = mysqli_real_escape_string($conn, $_POST['project_end_date']);

    $sql = "INSERT INTO مشاريع (project_name, project_description, project_start_date, project_end_date) VALUES ('$project_name', '$project_description', '$project_start_date', '$project_end_date')";
    if (mysqli_query($conn, $sql)) {
        echo 'success';
    } else {
        echo 'Error adding project: ' . mysqli_error($conn);
    }

    // Close connection
    mysqli_close($conn);
} else {
    echo 'Error adding project';
}
?>