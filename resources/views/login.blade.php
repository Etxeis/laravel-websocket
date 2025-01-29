<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
      background-color: #f4f4f9;
    }
    .container {
      max-width: 400px;
      margin: 50px auto;
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      display: block;
      margin-bottom: 5px;
    }
    input[type="email"],
    input[type="password"],
    button {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 16px;
    }
    button {
      background-color: #007bff;
      color: #fff;
      cursor: pointer;
      border: none;
    }
    button:hover {
      background-color: #0056b3;
    }
    .error {
      color: red;
      margin-top: 10px;
    }
    .success {
      color: green;
      margin-top: 10px;
    }
    .password-info {
      font-size: 14px;
      color: #555;
      margin-top: 10px;
      line-height: 1.5;
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Login</h2>
    <form id="loginForm">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required />
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required />
      </div>
      <button type="submit">Login</button>
      <div class="error" id="errorMessage"></div>
      <div class="success" id="successMessage"></div>
    </form>
  </div>
    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const errorMessage = document.getElementById('errorMessage');
            errorMessage.textContent = '';

            try {
                const response = await fetch('http://localhost:8000/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email, password }),
                });

                const result = await response.json();

                if (response.ok) {
                    // ✅ Guardar token en localStorage
                    localStorage.setItem('token', result.authorisation.token);

                    // ✅ Redirigir a home con el token
                    window.location.href = "/home";
                } else {
                    errorMessage.textContent = result.message || 'Login failed.';
                }
            } catch (error) {
                errorMessage.textContent = 'Unable to connect to the server.';
            }
        });
    </script>
</body>
</html>
