<!-- register.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 h-screen flex justify-center items-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-1/2">
        <h1 class="text-3xl text-indigo-500 font-bold mb-4">Register</h1>
        <form id="register-form" class="space-y-4">
            <div>
                <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                <input type="text" id="username" name="username" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                <p id="username-error" class="text-red-500 hidden"></p>
            </div>
            <div>
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Email" required>
                <p id="email-error" class="text-red-500 hidden"></p>
            </div>
            <div>
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Password" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                <p id="password-error" class="text-red-500 hidden"></p>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Register</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('register-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            const response = await fetch('../backend/auth.php?action=register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    username,
                    email,
                    password
                })
            });

            const data = await response.json();

            if (data.success) {
                alert('Registration successful!');
                window.location.href = 'login.php';
            } else {
                if (data.username_error) {
                    document.getElementById('username-error').textContent = data.username_error;
                    document.getElementById('username-error').classList.remove('hidden');
                } else {
                    document.getElementById('username-error').classList.add('hidden');
                }

                if (data.email_error) {
                    document.getElementById('email-error').textContent = data.email_error;
                    document.getElementById('email-error').classList.remove('hidden');
                } else {
                    document.getElementById('email-error').classList.add('hidden');
                }

                if (data.password_error) {
                    document.getElementById('password-error').textContent = data.password_error;
                    document.getElementById('password-error').classList.remove('hidden');
                } else {
                    document.getElementById('password-error').classList.add('hidden');
                }
            }
        });
    </script>
</body>
</html>


This code uses Tailwind CSS to create a premium-looking registration form. It includes validation rules and patterns for the input fields, and submits the form via AJAX to the `auth.php` file. The response from the server is then used to display any error messages to the user.