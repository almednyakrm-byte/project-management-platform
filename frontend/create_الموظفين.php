<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Define the module slug
$mod_slug = 'الموظفين';

// Define the form fields
$fields = [
    'name' => 'الاسم',
    'email' => 'البريد الإلكتروني',
    'phone' => 'الهاتف',
    'address' => 'العنوان',
    'job_title' => 'المسمى الوظيفي',
    'department' => 'ال部门',
];

// Define the form errors
$errors = [];
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة موظف</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-indigo-500">
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <h1 class="text-3xl font-bold mb-4">إضافة موظف</h1>
        <form id="create-form" class="bg-indigo-500 p-4 rounded shadow-md">
            <?php foreach ($fields as $field => $label): ?>
                <div class="mb-4">
                    <label for="<?php echo $field; ?>" class="block text-sm font-medium mb-2"><?php echo $label; ?></label>
                    <input type="<?php echo $field == 'email' ? 'email' : 'text'; ?>" id="<?php echo $field; ?>" name="<?php echo $field; ?>" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <?php if (isset($errors[$field])): ?>
                        <p class="mt-2 text-sm text-red-600"><?php echo $errors[$field]; ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/الموظفين.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>