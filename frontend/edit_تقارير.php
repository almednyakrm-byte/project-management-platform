**edit_تقارير.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$existingRecord = json_decode(file_get_contents('../backend/تقارير.php?id=' . $id), true);

// Check if record exists
if (empty($existingRecord)) {
    echo 'Record not found';
    exit;
}

// Set page title and breadcrumbs
$pageTitle = 'Edit تقارير';
$breadcrumbs = ['Home', 'تقارير', 'Edit تقارير'];

// Include header and breadcrumbs
include 'header.php';
?>

<!-- Main content -->
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h1 class="text-3xl font-bold leading-tight text-gray-900"><?= $pageTitle ?></h1>
            <div class="flex justify-between items-center mb-4">
                <a href="list_تقارير.php" class="text-blue-600 hover:text-blue-900">Back to list</a>
            </div>
            <form id="edit-form" class="w-full max-w-md">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                    <input type="text" id="title" name="title" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" value="<?= $existingRecord['title'] ?>">
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent"><?= $existingRecord['description'] ?></textarea>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    // Fetch existing record details via GET
    fetch('../backend/تقارير.php?id=' + <?= $id ?>)
        .then(response => response.json())
        .then(data => {
            document.getElementById('title').value = data.title;
            document.getElementById('description').value = data.description;
        })
        .catch(error => console.error(error));

    // Submit form via AJAX PUT request
    document.getElementById('edit-form').addEventListener('submit', event => {
        event.preventDefault();
        const formData = new FormData(event.target);
        fetch('../backend/تقارير.php', {
            method: 'PUT',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_تقارير.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
    });
</script>

<!-- Include footer -->
<?php include 'footer.php'; ?>


**backend/تقارير.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID not set']);
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Check if ID is numeric
if (!is_numeric($id)) {
    echo json_encode(['error' => 'ID is not numeric']);
    exit;
}

// Fetch existing record details from database
$record = getRecord($id);

// Check if record exists
if (empty($record)) {
    echo json_encode(['error' => 'Record not found']);
    exit;
}

// Update record via PUT request
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents('php://input'), $data);
    updateRecord($id, $data);
    echo json_encode(['success' => true]);
    exit;
}

// Get record details
function getRecord($id) {
    // Implement database query to get record details
    // For example:
    $db = new PDO('sqlite:database.db');
    $stmt = $db->prepare('SELECT * FROM تقارير WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch();
}

// Update record
function updateRecord($id, $data) {
    // Implement database query to update record
    // For example:
    $db = new PDO('sqlite:database.db');
    $stmt = $db->prepare('UPDATE تقارير SET title = :title, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $data['title']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->execute();
}
?>