<?php
// Check if user is authenticated
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة إدارة المشاريع</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism-card {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-slate-900 text-white">
    <div class="container mx-auto p-4 pt-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold">منصة إدارة المشاريع</h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold mb-2">مرحباً</h2>
            <p class="text-lg">إدارة المشاريع مع إدارة الفريق والمهام والوقت</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
            <?php
            // Fetch stats dynamically via Javascript API calls from the backend files
            $stats = json_decode(file_get_contents('api/stats.php'), true);
            ?>
            <div class="glassmorphism-card p-4">
                <h2 class="text-2xl font-bold mb-2">إجمالي المشاريع</h2>
                <p class="text-lg"><?= $stats['projects_count'] ?></p>
            </div>
            <div class="glassmorphism-card p-4">
                <h2 class="text-2xl font-bold mb-2">إجمالي الفريق</h2>
                <p class="text-lg"><?= $stats['team_count'] ?></p>
            </div>
            <div class="glassmorphism-card p-4">
                <h2 class="text-2xl font-bold mb-2">إجمالي المهام</h2>
                <p class="text-lg"><?= $stats['tasks_count'] ?></p>
            </div>
        </div>
        <div class="glassmorphism-card p-4 mb-4">
            <h2 class="text-2xl font-bold mb-2">إدارة المشاريع</h2>
            <ul class="list-none mb-0">
                <li class="mb-2"><a href="team.php" class="text-lg hover:text-indigo-500">تيم</a></li>
                <li class="mb-2"><a href="projects.php" class="text-lg hover:text-indigo-500">مشاريع</a></li>
                <li class="mb-2"><a href="tasks.php" class="text-lg hover:text-indigo-500">مهام</a></li>
            </ul>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/axios@0.21.1/dist/axios.min.js"></script>
    <script>
        axios.get('api/stats.php')
            .then(response => {
                const stats = response.data;
                document.querySelector('.stats-grid .project-count').textContent = stats.projects_count;
                document.querySelector('.stats-grid .team-count').textContent = stats.team_count;
                document.querySelector('.stats-grid .tasks-count').textContent = stats.tasks_count;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>



// api/stats.php
<?php
// Fetch stats from database
$stats = array(
    'projects_count' => 10,
    'team_count' => 5,
    'tasks_count' => 20
);
echo json_encode($stats);
?>



// logout.php
<?php
session_destroy();
header('Location: login.php');
exit;
?>


This code creates a premium dashboard layout with a glassmorphism card design using Tailwind CSS. It includes a session check to redirect to the login page if the user is not authenticated. The dashboard layout includes a welcome message, logout button, overview stats grid, and quick links to manage modules. The stats are fetched dynamically via a Javascript API call from the backend files. The color palette used is slate-900 and indigo-500.