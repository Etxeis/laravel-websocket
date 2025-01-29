<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f9;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            margin-bottom: 20px;
        }
        p {
            font-size: 18px;
            color: #333;
        }
        .logout-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .logout-btn:hover {
            background-color: #cc0000;
        }
        .subscribe-btn {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .subscribe-btn:hover {
            background-color: #45a049;
        }
        .status-message {
            margin-top: 10px;
            font-size: 16px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido, <span id="nombre">Cargando...</span></h1>
        <p>Tu correo electrónico es: <span id="correo">Cargando...</span></p>

        <!-- Botón para suscribirse al canal 'ventas' -->
        <button id="subscribeButton" class="subscribe-btn">Suscribirse al canal Ventas</button>

        <!-- Mensaje de estado de la suscripción -->
        <div id="statusMessage" class="status-message"></div>

        <button class="logout-btn" onclick="logout()">Cerrar sesión</button>
    </div>

    <script>
        // Función para cargar los datos del usuario
        async function loadUserData() {
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = "/login"; // Si no hay token, redirigir a login
                return;
            }

            try {
                const response = await fetch('http://localhost:8000/api/user', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                    },
                });

                const result = await response.json();
                if (response.ok) {
                    document.getElementById('nombre').textContent = result.nombre;
                    document.getElementById('correo').textContent = result.correo;
                } else {
                    window.location.href = "/login";
                }
            } catch (error) {
                console.error('Error:', error);
                window.location.href = "/login";
            }
        }

        // Función para cerrar sesión
        async function logout() {
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = "/login";
                return;
            }

            try {
                const response = await fetch('http://localhost:8000/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                    },
                });

                if (response.ok) {
                    localStorage.removeItem('token'); // Eliminar el token de localStorage
                    window.location.href = "/login"; // Redirigir al login
                } else {
                    console.error('Error al cerrar sesión');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        // Función para suscribirse al canal 'ventas'
        async function subscribeToChannel() {
            const token = localStorage.getItem('token');
            if (!token) {
                document.getElementById('statusMessage').textContent = 'No se encontró un token válido.';
                return;
            }

            try {
                const response = await fetch('http://localhost:8000/api/subscribe/ventas', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Content-Type': 'application/json',
                    },
                });

                const data = await response.json();

                if (response.ok) {
                    document.getElementById('statusMessage').textContent = '¡Suscripción exitosa al canal ventas!';
                } else {
                    document.getElementById('statusMessage').textContent = data.error || 'Error al suscribirse.';
                }
            } catch (error) {
                document.getElementById('statusMessage').textContent = 'Error en la conexión con el servidor.';
            }
        }

        // Conexión WebSocket (sólo para pruebas)
        const socket = new WebSocket('ws://localhost:6001/app/your-app-key?protocol=7&client=browser&version=5.1');
        socket.onopen = () => console.log("Conectado al servidor WebSocket");
        socket.onmessage = (event) => console.log('Mensaje del servidor: ', event.data);
        socket.onerror = (error) => console.log('Error WebSocket: ', error);
        socket.onclose = () => console.log("Conexión WebSocket cerrada");

        // Manejo del evento de clic para suscribirse al canal
        document.getElementById('subscribeButton').addEventListener('click', subscribeToChannel);

        // Cargar los datos del usuario al cargar la página
        document.addEventListener('DOMContentLoaded', loadUserData);
    </script>
</body>
</html>
