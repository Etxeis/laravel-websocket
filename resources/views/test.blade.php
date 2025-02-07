<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form id="form">
        <label for="input-message">Messages</label>
        <input id="input-message" type="text">
        <button type="submit">Enviar</button>
    </form>

    <ul id="list-messages">
        <!-- Los mensajes se agregarán aquí dinámicamente -->
    </ul>

    @vite(['resources/js/app.js']) <!-- Si estás usando Vite -->

    <script>
        document.getElementById('form').addEventListener('submit', function(event) {
            event.preventDefault(); // Evita que el formulario se envíe de la manera tradicional

            const message = document.getElementById('input-message').value;

            // Envía el mensaje a través de una solicitud AJAX
            fetch('/send-message?message=' + encodeURIComponent(message), {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(response => {
                if (response.ok) {
                    console.log('Mensaje enviado');
                    document.getElementById('input-message').value = ''; // Limpia el campo de entrada
                }
            });
        });
    </script>
</body>
</html>
