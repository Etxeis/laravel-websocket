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

    </ul>

    @vite(['resources/js/app.js']) <!-- Si estás usando Vite -->

</body>
</html>
