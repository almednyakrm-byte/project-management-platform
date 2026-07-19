<!-- login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #1a1d23, #2c2f36);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
        }
        
        .glassmorphic {
            background: linear-gradient(180deg, #1a1d23, #2c2f36);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        
        .glassmorphic::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, #1a1d23, #2c2f36);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            z-index: -1;
        }
        
        .gradient {
            background: linear-gradient(to bottom, #1a1d23, #2c2f36);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s;
        }
    </style>
</head>
<body class="bg-gray-900 h-screen flex justify-center items-center">
    <div class="glassmorphic max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
        <h2 class="text-3xl font-bold text-slate-900 mb-4">Login</h2>
        <form id="login-form" class="space-y-4">
            <div>
                <label for="username" class="block text-sm font-medium text-slate-900">Username</label>
                <input type="text" id="username" name="username" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                <div id="username-error" class="text-red-500 hidden"></div>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-slate-900">Password</label>
                <input type="password" id="password" name="password" class="block w-full p-2 mt-1 text-sm text-gray-900 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" required>
                <div id="password-error" class="text-red-500 hidden"></div>
            </div>
            <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Login</button>
            <p class="text-sm text-gray-500">Don't have an account? <a href="register.php" class="text-indigo-500 hover:text-indigo-700">Register</a></p>
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
                    document.getElementById('username-error').textContent = data.error.username;
                    document.getElementById('password-error').textContent = data.error.password;
                    document.getElementById('username-error').classList.remove('hidden');
                    document.getElementById('password-error').classList.remove('hidden');
                }
            } catch (error) {
                console.error(error);
                alert('Error logging in. Please try again.');
            }
        });
    </script>
</body>
</html>


This code creates a premium-looking login page with a glassmorphic layout, gradients, and a form for username and password input. It uses the Tailwind CSS CDN for styling and includes a beautiful glassmorphic layout with gradients. The form includes standard HTML input pattern validators to support Arabic and Latin characters. The AJAX JavaScript code uses the Fetch API to submit the credentials to the `../backend/auth.php?action=login` endpoint and handles the response or error alerts dynamically. The direct link to the `register.php` page is also included.