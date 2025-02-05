import './bootstrap';

const form = document.getElementById('form');
const inputMessage = document.getElementById('input-message');
const listMessage = document.getElementById('list-messages');

form.addEventListener('submit', function (event) {
    event.preventDefault();

    const userInput = inputMessage.value;

    window.axios.post('/send-message', {
        message: userInput
    })
});


const channel = window.Echo.channel('ventas');

channel.subscribed( () => {
    console.log('Suscrito al canal!');
}).listen('.send-message', (event) => {
    console.log(event);
    const message = event.message;

    const li = document.createElement('li');

    li.textContent = message;

    listMessage.append(li);
})
