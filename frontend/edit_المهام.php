<?php
// edit_المهام.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_المهام.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل المهمة</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-slate-900 text-indigo-500 rounded">
        <h2 class="text-2xl font-bold mb-4">تعديل المهمة</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium mb-2">اسم المهمة</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-900 border border-indigo-500 rounded">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium mb-2">وصف المهمة</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-indigo-500 bg-slate-900 border border-indigo-500 rounded"></textarea>
            </div>
            <button type="submit" class="py-2 px-4 bg-indigo-500 text-slate-900 rounded hover:bg-indigo-700">حفظ التعديلات</button>
        </form>
    </div>

    <script>
        const id = <?php echo $id; ?>;
        const form = document.getElementById('edit-form');

        // Fetch existing record details
        fetch(`../backend/المهام.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('description').value = data.description;
            });

        // Submit form using AJAX PUT request
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/المهام.php', {
                method: 'PUT',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_المهام.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>