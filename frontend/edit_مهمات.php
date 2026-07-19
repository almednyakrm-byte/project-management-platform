**edit_مهمات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/مهمات.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

// Set page title and mod slug
$pageTitle = 'Edit مهمات';
$modSlug = 'مهمات';

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<!-- Main content -->
<main class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h1 class="text-3xl font-bold mb-4"><?= $pageTitle ?></h1>

    <!-- Form -->
    <form id="edit-form" class="bg-white rounded shadow-md p-4">
        <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Title:</label>
            <input type="text" id="title" name="title" class="block w-full p-2 border border-gray-300 rounded-md" value="<?= $existingRecord['title'] ?>">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
            <textarea id="description" name="description" class="block w-full p-2 border border-gray-300 rounded-md"><?= $existingRecord['description'] ?></textarea>
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
    </form>
</main>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Populate form fields
        $.ajax({
            type: 'GET',
            url: '../backend/مهمات.php?id=' + <?= $id ?>,
            success: function(data) {
                var existingRecord = JSON.parse(data);
                $('#title').val(existingRecord.title);
                $('#description').val(existingRecord.description);
            }
        });

        // Submit form via AJAX
        $('#edit-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'PUT',
                url: '../backend/مهمات.php',
                data: formData,
                success: function() {
                    window.location.href = 'list_' + '<?= $modSlug ?>' + '.php';
                }
            });
        });
    });
</script>

<!-- Footer -->
<?php include 'footer.php'; ?>


**header.php**

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <!-- Navigation -->
    <?php include 'navigation.php'; ?>


**navigation.php**

<nav class="bg-gray-800 text-white p-4">
    <ul class="flex justify-between">
        <li><a href="list_<?= $modSlug ?>.php" class="text-gray-300 hover:text-white">Back to List</a></li>
        <li><a href="logout.php" class="text-gray-300 hover:text-white">Logout</a></li>
    </ul>
</nav>


**footer.php**

<footer class="bg-gray-800 text-white p-4">
    &copy; <?= date('Y') ?> <?= $modSlug ?>
</footer>


**backend/مهمات.php**

<?php
// Check if id is set
if (!isset($_GET['id'])) {
    echo 'Invalid request';
    exit;
}

// Connect to database
$conn = new PDO('dsn', 'username', 'password');

// Fetch existing record details
$stmt = $conn->prepare('SELECT * FROM مهمات WHERE id = :id');
$stmt->bindParam(':id', $_GET['id']);
$stmt->execute();
$existingRecord = $stmt->fetch(PDO::FETCH_ASSOC);

// Return existing record details as JSON
echo json_encode($existingRecord);

// Close database connection
$conn = null;


Note: This code assumes you have a database connection set up in the `backend/مهمات.php` file. You'll need to replace the placeholders with your actual database credentials and table name. Additionally, this code uses the `PDO` extension for database interactions, which is a good practice.