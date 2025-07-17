$(document).ready(function(){

	var first=1;

var messages__container = document.getElementById('messages'); 
//Контейнер сообщений — скрипт будет добавлять в него сообщения

var interval = null; //Переменная с интервалом подгрузки сообщений

var sendForm = document.getElementById('chat-form'); //Форма отправки
var messageInput = document.getElementById('message-text');

function send_request(act) {//Основная функция
	//Переменные, которые будут отправляться
	var var1 = null;
	var var2 = null;
	 if(act == 'send') {
//Если нужно отправить сообщение, то получаем текст из поля ввода
		var1 = messageInput.value;
	}
	
	$.post('chat.php',{ //Отправляем переменные
		act: act,
		var1: var1,
		var2: var2
		
	},function (data) { 
		//Заносим в контейнер ответ от сервера
		messages__container.innerHTML = data;
		if(act == 'send') {
			//Если нужно было отправить сообщение, очищаем поле ввода
			messageInput.value = '';

			$('#chatbox').scrollTop($('#chatbox')[0].scrollHeight);
		}
		if (first==1)
		{
			$('#chatbox').scrollTop($('#chatbox')[0].scrollHeight);
			first=0;
		}
		
	});
}
function update() {
	send_request('load');
}
interval = setInterval(update,500);
sendForm.onsubmit = function () {
	send_request('send');
	return false; //Возвращаем ложь, чтобы остановить классическую отправку формы
};
$('#chatbox').scrollTop($('#chatbox')[0].scrollHeight);

});