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
        .message-box {
            margin-top: 20px;
        }
        .message-box textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            resize: vertical;
        }
        .message-box button {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .message-box button:hover {
            background-color: #0056b3;
        }
        .messages {
            margin-top: 20px;
            text-align: left;
        }
        .messages ul {
            list-style-type: none;
            padding: 0;
        }
        .messages li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
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

        <!-- Área para enviar mensajes -->
        <div class="message-box">
            <textarea id="messageInput" placeholder="Escribe tu mensaje aquí..."></textarea>
            <button id="sendMessageButton" class="send-btn">Enviar mensaje</button>
        </div>

        <!-- Área para mostrar mensajes recibidos -->
        <div class="messages">
            <h3>Mensajes:</h3>
            <ul id="messagesList"></ul>
        </div>

        <button class="logout-btn" onclick="logout()">Cerrar sesión</button>
    </div>

    <!-- Cargar Laravel Echo -->
    @vite(['resources/js/app.js']) <!-- Si estás usando Vite -->
    <!-- <script src="{{ asset('js/app.js') }}"></script> --> <!-- Si estás usando Laravel Mix -->

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

        // Función para enviar un mensaje al canal
        async function sendMessage() {
            // Verificar si hay un token en localStorage
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = "/login"; // Redirigir al login si no hay token
                return;
            }

            // Obtener el mensaje del textarea
            const message = document.getElementById('messageInput').value;

            // Verificar si el mensaje está vacío
            if (!message) {
                alert('Por favor, escribe un mensaje.');
                return;
            }

            try {
                // Enviar el mensaje al servidor
                const response = await fetch('http://localhost:8000/api/send-message', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + token, // Incluir el token en el encabezado
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ channel: 'ventas', message }), // Enviar el mensaje y el canal
                });

                // Procesar la respuesta del servidor
                const data = await response.json();

                if (response.ok) {
                    document.getElementById('messageInput').value = ''; // Limpiar el área de texto
                    console.log("Mensaje enviado");
                } else {
                    alert(data.error || 'Error al enviar el mensaje.'); // Mostrar un mensaje de error
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error en la conexión con el servidor.'); // Mostrar un mensaje de error en caso de excepción
            }
        }

        // Escuchar mensajes del canal 'ventas'
        // Esperar a que el DOM esté completamente cargado
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Verificando Laravel Echo:', window.Echo);

            if (window.Echo) {
                window.Echo.channel('ventas') // Canal público (sin `private`)
                    .listen('.MessageSent', (data) => { // Escuchar según broadcastAs()
                        console.log('Mensaje recibido:', data);

                        const messagesList = document.getElementById('messagesList');
                        const li = document.createElement('li');
                        li.textContent = `${data.user?.name || 'Usuario desconocido'}: ${data.message}`;
                        messagesList.appendChild(li);
                    })
                    .error((error) => {
                        console.error('Error al suscribirse al canal:', error);
                    }); // ← Se cierra correctamente el método `error()`

            } else {
                console.error('Laravel Echo no está configurado correctamente.');
            }

            document.getElementById('subscribeButton')?.addEventListener('click', subscribeToChannel);
            document.getElementById('sendMessageButton')?.addEventListener('click', sendMessage);
            
            loadUserData();
        });
    </script>
</body>
</html>
