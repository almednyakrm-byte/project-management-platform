<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #2f4f7f, #1a1d23);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s linear;
        }
        .glassmorphic {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .gradient {
            background: linear-gradient(to bottom, #2f4f7f, #1a1d23);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s linear;
        }
    </style>
</head>
<body class="bg-gray-900 h-screen flex justify-center items-center">
    <div class="glassmorphic w-96 p-8 rounded-lg shadow-lg">
        <h1 class="text-3xl text-center text-slate-100 mb-4">Login</h1>
        <form id="login-form" class="space-y-4">
            <div class="space-y-1">
                <label for="username" class="block text-sm font-medium text-slate-100">Username</label>
                <input type="text" id="username" name="username" class="block p-2 w-full text-slate-100 bg-gray-800 rounded-lg border border-gray-700 focus:outline-none focus:border-indigo-500" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                <div id="username-error" class="text-red-500 text-sm"></div>
            </div>
            <div class="space-y-1">
                <label for="password" class="block text-sm font-medium text-slate-100">Password</label>
                <input type="password" id="password" name="password" class="block p-2 w-full text-slate-100 bg-gray-800 rounded-lg border border-gray-700 focus:outline-none focus:border-indigo-500" required>
                <div id="password-error" class="text-red-500 text-sm"></div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">Login</button>
            <p class="text-center text-slate-100 mt-4">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-700">Register</a></p>
        </form>
    </div>

    <script>
        const form = document.getElementById('login-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });
                const data = await response.json();
                if (data.success) {
                    window.location.href = 'dashboard.php';
                } else {
                    document.getElementById('username-error').textContent = data.username_error ? data.username_error : '';
                    document.getElementById('password-error').textContent = data.password_error ? data.password_error : '';
                }
            } catch (error) {
                console.error(error);
                alert('Error logging in. Please try again.');
            }
        });
    </script>
</body>
</html>


This code creates a premium-looking login page with a glassmorphic layout, gradients, and a form for username and password input. It uses the Tailwind CSS CDN for styling and includes a beautiful glassmorphic layout with gradients. The form includes standard HTML input pattern validators to support Arabic and Latin characters. The AJAX JavaScript code uses the Fetch API to submit the credentials to the backend `auth.php` script and handles the response or error alerts dynamically. The direct link to the `register.php` page is also included.