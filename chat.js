const socket = new WebSocket('ws://http://178.34.98.23/:3000'); 
var sendForm = document.getElementById('chat-form'); //Форма отправки
var messageInput = document.getElementById('message-text');
var messages__container = document.getElementById('messages'); 
   socket.onopen = function(event) {
       console.log('WebSocket connected');
       socket.send($("[name=nomer]").val()+"_"+$("[name=nomer_send]").val());
   };

   socket.onmessage = function(event) {
       console.log('Received message: ' + event.data);
        messages__container.innerHTML = event.data;
   };

   socket.onclose = function(event) {
       console.log('WebSocket disconnected');
   };

   socket.onerror = function(error) {
       console.error('WebSocket error:', error);
   };

   sendForm.onsubmit = function () {
    socket.send(messageInput.value+"_"+$("[name=nomer_send]").val());
    messageInput.value = '';
    return false; //Возвращаем ложь, чтобы остановить классическую отправку формы
};