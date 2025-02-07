import './bootstrap';

const form = document.getElementById('form');
const inputMessage = document.getElementById('input-message');
const listMessage = document.getElementById('list-messages');

form.addEventListener('submit', function (event) {
    event.preventDefault();

    const userInput = inputMessage.value;

    // Enviar el mensaje usando Axios
    window.axios.post('/send-message', {
        message: userInput
    })
    .then(response => {
        console.log(response.data); // Mensaje enviado correctamente
        inputMessage.value = ''; // Limpiar el campo de entrada
    })
    .catch(error => {
        console.error('Error al enviar el mensaje:', error);
    });
});

// Escuchar mensajes del canal 'ventas'
window.Echo.channel('ventas')
    .listen('MessageSent', (e) => {
        const messageList = document.getElementById('list-messages');
        const newMessage = document.createElement('li');
        newMessage.textContent = e.message;
        messageList.appendChild(newMessage);
    });
