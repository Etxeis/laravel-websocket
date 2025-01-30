<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración de Canales</title>
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
        }
        h1 {
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="email"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }
        .btn-add {
            background-color: #4CAF50;
            color: white;
        }
        .btn-remove {
            background-color: #f44336;
            color: white;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Administración de Canales</h1>

        <!-- Formulario para agregar o eliminar suscripciones -->
        <div class="form-group">
            <label for="email">Email del Usuario:</label>
            <input type="email" id="email" placeholder="Ingresa el email del usuario">
        </div>
        <div class="form-group">
            <label for="channel">Nombre del Canal:</label>
            <input type="text" id="channel" placeholder="Ingresa el nombre del canal">
        </div>
        <div class="form-group">
            <button class="btn btn-add" onclick="addChannel()">Agregar al Canal</button>
            <button class="btn btn-remove" onclick="removeChannel()">Eliminar del Canal</button>
        </div>

        <!-- Mensajes de éxito o error -->
        <div id="message" class="message"></div>
    </div>

    <script>
        // Función para agregar un canal a un usuario
        async function addChannel() {
            const email = document.getElementById('email').value;
            const channel = document.getElementById('channel').value;

            if (!email || !channel) {
                showMessage('Por favor, completa todos los campos.', 'error');
                return;
            }

            try {
                const response = await fetch('/api/user/add-channel', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                    body: JSON.stringify({ email, channel }),
                });

                const data = await response.json();

                if (response.ok) {
                    showMessage(data.message, 'success');
                } else {
                    showMessage(data.error || 'Error al agregar el canal.', 'error');
                }
            } catch (error) {
                showMessage('Error en la conexión con el servidor.', 'error');
            }
        }

        // Función para eliminar un canal de un usuario
        async function removeChannel() {
            const email = document.getElementById('email').value;
            const channel = document.getElementById('channel').value;

            if (!email || !channel) {
                showMessage('Por favor, completa todos los campos.', 'error');
                return;
            }

            try {
                const response = await fetch('/api/user/remove-channel', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${localStorage.getItem('token')}`,
                    },
                    body: JSON.stringify({ email, channel }),
                });

                const data = await response.json();

                if (response.ok) {
                    showMessage(data.message, 'success');
                } else {
                    showMessage(data.error || 'Error al eliminar el canal.', 'error');
                }
            } catch (error) {
                showMessage('Error en la conexión con el servidor.', 'error');
            }
        }

        // Función para mostrar mensajes
        function showMessage(message, type) {
            const messageDiv = document.getElementById('message');
            messageDiv.textContent = message;
            messageDiv.className = `message ${type}`;
        }
    </script>
</body>
</html>
