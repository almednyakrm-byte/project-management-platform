**edit_الموارد-البشرية.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Fetch existing record details via GET
$url = '../backend/الموارد-البشرية.php?id=' . $id;
$response = file_get_contents($url);
$data = json_decode($response, true);

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الموارد البشرية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.10/dist/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@0.21.1/dist/axios.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
        }
        .form-group input, .form-group select {
            width: 100%;
            height: 40px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #3e8e41;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">تعديل الموارد البشرية</h2>
        <form id="edit-resource-form">
            <div class="form-group">
                <label for="name">اسم الموارد البشرية:</label>
                <input type="text" id="name" name="name" value="<?php echo $data['name']; ?>">
            </div>
            <div class="form-group">
                <label for="description">وصف الموارد البشرية:</label>
                <textarea id="description" name="description"><?php echo $data['description']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="category">فئة الموارد البشرية:</label>
                <select id="category" name="category">
                    <option value="<?php echo $data['category']; ?>"><?php echo $data['category']; ?></option>
                    <!-- Add more options here -->
                </select>
            </div>
            <div class="form-group">
                <input type="submit" value="تعديل">
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Fetch existing record details via GET
            axios.get('../backend/الموارد-البشرية.php?id=<?php echo $id; ?>')
                .then(response => {
                    const data = response.data;
                    $('#name').val(data.name);
                    $('#description').val(data.description);
                    $('#category').val(data.category);
                })
                .catch(error => {
                    console.error(error);
                });

            // Handle form submission
            $('#edit-resource-form').submit(function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                axios.put('../backend/الموارد-البشرية.php', formData)
                    .then(response => {
                        if (response.data.success) {
                            Swal.fire({
                                title: 'تم التعديل بنجاح!',
                                text: 'تم تعديل الموارد البشرية بنجاح.',
                                icon: 'success',
                                confirmButtonText: 'حسناً'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = 'list_الموارد-البشرية.php';
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'خطأ!',
                                text: 'حدث خطأ أثناء التعديل.',
                                icon: 'error',
                                confirmButtonText: 'حسناً'
                            });
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });
        });
    </script>
</body>
</html>

**Note:** This code assumes that you have a backend PHP script (`../backend/الموارد-البشرية.php`) that handles the GET and PUT requests. The backend script should return the existing record details in JSON format when the GET request is made, and update the record when the PUT request is made.