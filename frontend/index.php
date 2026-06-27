<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة إدارة المشاريع والتعاونيات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
        }
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="flex justify-between items-center p-4 bg-slate-900">
        <h1 class="text-3xl text-white">منصة إدارة المشاريع والتعاونيات</h1>
        <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل الخروج</button>
    </div>
    <div class="flex justify-center items-center p-4 bg-slate-900">
        <h1 class="text-2xl text-white">مرحباً <?= $_SESSION['username'] ?></h1>
    </div>
    <div class="flex justify-center items-center p-4 bg-slate-900">
        <div class="glassmorphism-card w-1/2 p-4">
            <h1 class="text-2xl text-white">إحصائيات المشروع</h1>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h2 class="text-lg text-gray-600">مشاريع</h2>
                    <p id="project-count" class="text-3xl text-gray-900"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h2 class="text-lg text-gray-600">تعاونيات</h2>
                    <p id="cooperation-count" class="text-3xl text-gray-900"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h2 class="text-lg text-gray-600">موارد</h2>
                    <p id="resource-count" class="text-3xl text-gray-900"></p>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-center items-center p-4 bg-slate-900">
        <div class="glassmorphism-card w-1/2 p-4">
            <h1 class="text-2xl text-white">روابط سريعة</h1>
            <ul class="list-none p-4">
                <li class="py-2">
                    <a href="projects.php" class="text-gray-600 hover:text-gray-900">مشاريع</a>
                </li>
                <li class="py-2">
                    <a href="cooperations.php" class="text-gray-600 hover:text-gray-900">تعاونيات</a>
                </li>
                <li class="py-2">
                    <a href="resources.php" class="text-gray-600 hover:text-gray-900">موارد</a>
                </li>
            </ul>
        </div>
    </div>

    <script>
        fetch('/api/stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('project-count').innerText = data.projectCount;
                document.getElementById('cooperation-count').innerText = data.cooperationCount;
                document.getElementById('resource-count').innerText = data.resourceCount;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code uses Tailwind CSS for styling and fetches stats dynamically via a JavaScript API call from the backend files. The color palette is set to slate-900 and indigo-500 as per your requirements. The dashboard layout includes a welcome message, logout button, overview stats grid, and quick links to manage modules.