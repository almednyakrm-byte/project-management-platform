<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Module slug
$mod_slug = 'reports';

// Page title
$page_title = 'Create Report';

// Include header
include 'header.php';
?>

<!-- Content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
    <div class="flex justify-center">
        <div class="w-full xl:w-8/12 p-8 bg-white rounded shadow-md">
            <h2 class="text-3xl text-blue-500 font-bold mb-4">Create Report</h2>
            <form id="create-report-form">
                <div class="mb-4">
                    <label for="report_title" class="block text-gray-200 text-sm font-bold mb-2">Report Title</label>
                    <input type="text" id="report_title" name="report_title" class="bg-gray-200 appearance-none border rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="report_description" class="block text-gray-200 text-sm font-bold mb-2">Report Description</label>
                    <textarea id="report_description" name="report_description" class="bg-gray-200 appearance-none border rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="report_date" class="block text-gray-200 text-sm font-bold mb-2">Report Date</label>
                    <input type="date" id="report_date" name="report_date" class="bg-gray-200 appearance-none border rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="report_status" class="block text-gray-200 text-sm font-bold mb-2">Report Status</label>
                    <select id="report_status" name="report_status" class="bg-gray-200 appearance-none border rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-blue-500" required>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create Report</button>
            </form>
        </div>
    </div>
</div>

<!-- AJAX JavaScript -->
<script>
    $(document).ready(function() {
        $('#create-report-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/reports.php',
                data: formData,
                success: function(data) {
                    window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>