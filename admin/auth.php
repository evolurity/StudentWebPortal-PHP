<html>
<head>
    <link rel="stylesheet" href="auth.css">
</head>
    <body>
<?php  
$mylogin = 'admin';
$mypass = 'admin';
if(isset($_POST['btn_auth']))
{
if (($_POST['login'] == $mylogin) && ($_POST['password'] == $mypass))
{
echo 'Авторизация прошла успешно';
?>
<br>
<a href="articles_admin.php">Войти в панель администрирования</a>
<?php
}
else
{
echo 'Неверные данные';
}
}
else
{
?>
<div class:"mydiv"> <form method="post">
Логин: <input type="text" name="login" /> 
Пароль: <input type="password" name="password" /> 
<input type="submit" value=Войти name="btn_auth" /> 
</form> </div>
<?php
}
?>
</body>
</html>
