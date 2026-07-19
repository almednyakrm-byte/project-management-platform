<!-- register.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f7f7;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .title {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 10px;
        }
        .form-group input {
            width: 100%;
            height: 40px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group input:focus {
            border-color: #aaa;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
        .submit-button {
            background-color: #3498db;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-button:hover {
            background-color: #2980b9;
        }
        .error-message {
            color: #f00;
            font-size: 12px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">
            <h2>Register</h2>
        </div>
        <form id="register-form">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter your username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required>
                <div class="error-message" id="username-error"></div>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
                <div class="error-message" id="email-error"></div>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
                <div class="error-message" id="password-error"></div>
            </div>
            <button class="submit-button" type="submit">Register</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#register-form').submit(function(e) {
                e.preventDefault();
                var username = $('#username').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var error = false;

                if (!username.match(pattern)) {
                    $('#username-error').text('Invalid username. Only letters, numbers, and spaces are allowed.');
                    error = true;
                } else {
                    $('#username-error').text('');
                }

                if (!email.match(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/)) {
                    $('#email-error').text('Invalid email address.');
                    error = true;
                } else {
                    $('#email-error').text('');
                }

                if (password.length < 8) {
                    $('#password-error').text('Password must be at least 8 characters long.');
                    error = true;
                } else {
                    $('#password-error').text('');
                }

                if (!error) {
                    $.ajax({
                        type: 'POST',
                        url: '../backend/auth.php?action=register',
                        data: {
                            username: username,
                            email: email,
                            password: password
                        },
                        success: function(response) {
                            if (response == 'success') {
                                alert('Registration successful. Please login to continue.');
                                window.location.href = 'login.php';
                            } else {
                                alert('Registration failed. Please try again.');
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>


This code uses Tailwind CSS to create a premium-looking registration form. It includes validation rules for the username, email, and password fields, and uses AJAX to submit the form to the backend PHP script. The form fields are validated using regular expressions, and error messages are displayed below each field if the input is invalid.