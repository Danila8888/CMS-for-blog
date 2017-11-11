<html>
<head>
	<title>Администраторская панель</title>
	<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
	<link href="../css/admin.css" rel="stylesheet" type="text/css" />
</head>
<center><form action="index.php" method="POST">
<img src="../img/logo.png" width="300px" height="70px" />
<h2>Выполните вход</h2>
<div class="row">
	<label for = "login">Пользователь </label>
	<input type="text" class="text" name="login" id="login" />
</div>
<div class="row">
	<label for = "password">Пароль</label>
	<input type="password" class="text" name="password" id="password" />
</div>
<div class ="row">
	<input type="submit" name="submit" id="btn-submit" value="Войти"/>
</div>
</form></center>
</html>
<?php
include ("../includes/db.php");
function cookie_generate($length = 16)
{
    $chars = 'qwertyuiopasdfghjklzxcvbnmMNBVCXZLKJHGFDSAPOIUYTREWQ0932145678';
    $numChars = strlen($chars);
    $string = '';
    for ($i = 0; $i < $length; $i++) 
    {
        $string .= substr($chars, rand(1, $numChars) - 1, 1);
    }
    return md5($string);
}
setcookie("admincookies", 0, time()+60*60*24*365*10);
if(isset($_POST['submit']))
{
	$login_salt = $_POST['login'] + "DSA232ijd.,z";
    $login = md5($login_salt);
	$password_salt = $_POST['password'] + "DSA232ijd.,z";
    $password = MD5($password_salt);
    
    if(preg_match("/^[a-zа-яё\d]{1}[a-zа-яё\d\s]*[a-zа-яё\d]{1}$/i", $login) == FALSE)
    {
        echo "<font color=#ff4d00><center>Введите корректный логин и пароль</center></font>";
        exit();
    }
    
    $query = mysqli_query($db, "SELECT * FROM users WHERE login = '$login' AND password = '$password' \n ");
    if ($query != TRUE) 
    {
        echo "<font color=#ff4d00><center>Неверный логин или пароль</center></font>"; 
		exit();
    }
    $myrow = mysqli_fetch_array($query);
    if ($myrow['login'] == $login AND $myrow['password'] == $password AND $myrow['admin'] == 1)
    { 
        $cookie = cookie_generate();
        $queryc = "UPDATE users SET cookie = '$cookie' WHERE login = '$login'";
		$query = mysqli_query($db, $queryc);
        setcookie("admincookies", $cookie, time()+1800);
        header('Location: control.php?action=0');
    } 
    else
    {
        echo "<font color=#ff4d00><center>Неверный логин или пароль</center></font>";
    }
}
?>