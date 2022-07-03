<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Document</title>
	<link rel="stylesheet" href="style.css">
	<script
	  src="https://code.jquery.com/jquery-3.6.0.min.js"
	  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
	  crossorigin="anonymous"></script>
	<script src="https://vk.com/js/api/openapi.js?169" type="text/javascript"></script>
	<script type="application/javascript" src="script.js"></script>
</head>
<body>

<?/*if($_SESSION["VK_TOKEN"]){*/?><!--
<div style='color: #3c710a'>Вы авторизованы</div>
<?/*}else{*/?>
<form action="/local/ajax/vkApi.php" id="form-vk" method="POST">
	<input type="text" name="NAME" placeholder="email-vk">
	<input type="password" name="PASSWORD" placeholder="password-vk">
	<input type="text" name="method" value="auth" hidden placeholder="password-vk">
	<input type="submit" value="Отправить">
</form>
--><?/*}*/?>
<br>
<form action="ajax.php" id="form-vk-user">
    <input type="text" name="USER_ID" placeholder="user-id">
	<input type="text" name="method" value="groups" hidden placeholder="password-vk">
    <br>
    <input id="auth" type="submit" value="Получить группы">
</form>
<div id="content">

</div>
</body>
</html>