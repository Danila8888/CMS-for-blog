<html>
<head>
	<title>Администраторская панель</title>
	<meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
	<link href="../css/admin.css" rel="stylesheet" type="text/css" />
</head>
</html>
<?php
$cookie = $_COOKIE['admincookies'];
include ("../includes/db.php"); 
$query = mysqli_query($db, "SELECT cookie, admin FROM users WHERE cookie = '$cookie'");
$myrow = mysqli_fetch_array($query);
if ($myrow['cookie'] != 0 AND $myrow['admin'] == 1)
{
	echo "<ul id=\"navbar\">
			<li><a href=\"control.php?action=0\">Главная</a></li>
			<li><a href=\"control.php?art=1&add=1&edit=0\">Записи</a>
				<ul>
					<li><a href=\"control.php?art=1&add=1&edit=0\">Добавить запись</a></li>
					<li><a href=\"control.php?art=1&edit=1&add=0\">Редактировать записи</a></li>
				</ul></li>
			<li><a href=\"control.php?menu=1\">Редактировать меню</a></li>
			<li><a href=\"control.php?news=1&add=1&edit=0\">Новости</a>
				<ul>
					<li><a href=\"control.php?news=1&add=1&edit=0\">Добавить Новость</a></li>
					<li><a href=\"control.php?news=1&edit=1&add=0\">Редактировать Новости</a></li>
				</ul>
			</li>
			<li><a href=\"control.php?feedback=1\">Сообщения</a></li>
			<li><a href=\"control.php?exit=1\">Выйти</a></li>
		</ul>";
	if(isset($_GET['exit']) AND ($_GET['exit'] == 1))
	{
		setcookie("admincookies", 0, time()+60*60*24*365*10);
		header('Location: index.php');
	}
	if(isset($_GET['action']) AND ($_GET['action']) == 0)
	{
		$articles = mysqli_fetch_array(mysqli_query($db, "SELECT COUNT(*) FROM articles"));
		$news = mysqli_fetch_array(mysqli_query($db, "SELECT COUNT(*) FROM news"));
		$users = mysqli_fetch_array(mysqli_query($db, "SELECT COUNT(*) FROM users"));
		$last_user = mysqli_fetch_array(mysqli_query($db, "SELECT login FROM users ORDER BY id DESC LIMIT 1;"));
		//print($articles[0]);
		echo "<center><img src=\"../img/logo.png\" width=\"300px\" height=\"70px\" />
		<h2>СТАТИСТИКА БЛОГА</h2>
		<p>Сейчас на сайте опубликовано - <b>$articles[0] записей</b>;</p>
		<p>Так-же на сайте опубликанано - <b>$news[0] новостей</b>;</p>
		<p>Количество зарегистрированных пользователей - <b>$users[0]</b>;</p>
		<p>Последний зарегистрированный пользователь - <b>$last_user[0]</b>;</p>
		</center>";
	}
	if(isset($_GET['art']))
	{
		//echo "<center><h2><a href=\"control.php?art=1&add=1&edit=0\">Добавить запись</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"control.php?art=1&edit=1&add=0\">Редактировать</a></h2></center>";
		if($_GET['art'] == 1 AND $_GET['add'] == 1 AND $_GET['edit'] == 0)
		{
			echo "<form name=\"add\" action=\"control.php?art=1&add=1&edit=0\" method=\"post\" enctype=multipart/form-data>
					<center><h2>Добавить новую запись в блог</h2></center>
					<center><p>Загрузить изображение для записи</p><input type=\"file\" name=\"uploadfile\"></center>
					<center><label><br />Название:</label><br />
					<textarea name=\"name\" cols=\"90%\" rows=\"1\"></textarea></center>
					<center><label><br />Запись:</label><br />
					<textarea name=\"text\" cols=\"90%\" rows=\"10\"></textarea></center>
					<center><input type=\"submit\" name=\"submit\" value=\"Опубликовать\" /></center>
				  </form>";
			if(isset($_POST['submit']))
			{
				$name = $_POST['name'];
				$text = $_POST['text'];
				$date = date("Y-m-d");

				$uploaddir = 'prev/';
				$uploadfile = $uploaddir.basename($_FILES['uploadfile']['name']);
				copy($_FILES['uploadfile']['tmp_name'], $uploadfile);
				
				$query = mysqli_query($db, "INSERT INTO `articles` (`name`, `text`, `path`, `date`) VALUES ('$name', '$text', '$uploadfile', '$date')");
			}
		}
		if($_GET['art'] == 1 AND $_GET['edit'] == 1 AND $_GET['add'] == 0)
		{
			$query = mysqli_query($db, "SELECT * FROM articles ORDER by id DESC");
			$i = 0;
			while ($row = $query->fetch_assoc())
			{
				$id = $row['id'];
				$name = $row['name'];
				$text = $row['text'];
				$date = $row['date'];
				$i++;
				echo "<center><h2>Статья номер $id от $date</h2>
				<form name=\"add\" action=\"control.php?art=1&edit=1&add=0\" method=\"post\" enctype=multipart/form-data>
				<input type=\"hidden\" name=\"count\" value=$i>
				<br /><b>Название: &nbsp;</b>
				<textarea name=\"edit_name\" cols=\"100%\" rows=\"1\">$name</textarea>
				<br /><b>Текст: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
				<textarea name=\"edit_text\" cols=\"100%\" rows=\"10\">$text</textarea>
				<br /><br /><b>Изменить изображение: </b><input type=\"file\" name=\"uploadfile\">
				<br /><br /><input type=\"submit\" name=\"submit_edit\" value=\"Изменить\"/>
				</form></center><br /><br />";
			}
			if(isset($_POST['submit_edit']))
			{
				$query = mysqli_query($db, "SELECT * FROM articles");
				$current_count = $_POST['count'];
				$edit_name = $_POST['edit_name'];
				$edit_text = $_POST['edit_text'];
				$uploaddir = 'prev/';
				$uploadfile = $uploaddir.basename($_FILES['uploadfile']['name']);
				if($uploadfile == $uploaddir)
				{
					$querye = mysqli_query($db, "UPDATE `articles` SET `name` = '$edit_name', `text` = '$edit_text' WHERE id = '$current_count'");
				}
				if($uploadfile != $uploaddir)
				{
					copy($_FILES['uploadfile']['tmp_name'], $uploadfile);
					$querye = mysqli_query($db, "UPDATE `articles` SET `name` = '$edit_name', `text` = '$edit_text', `path` = '$uploadfile' WHERE id = '$current_count'");
				}
			}
		}
	}
	if(isset($_GET['feedback']) AND $_GET['feedback'] == 1)
	{
		$query = mysqli_query($db, "SELECT * FROM feedback ORDER BY id DESC");
		$i = 0;
		while ($row = $query->fetch_assoc())
		{
			$id = $row['id'];
			$name = $row['name'];
			$mail = $row['mail'];
			$ip = $row['ip'];
			$message = $row['message'];
			$date = $row['date'];
			$i++;
			echo "<center><h2>Сообщение номер $id</h2>
			<input type=\"hidden\" name=\"count\" value=$i>
			<br />Пользователь: <i>$name</i> <br />
			Почта: <i>$mail</i> <br />
			IP: <i>$ip</i> <br />
			Дата: <i>$date</i> <br />
			Сообщение: <i>$message</i> <br />
			</center><br /><br />";
		}
	}
	if(isset($_GET['menu']) AND $_GET['menu'] == 1)
	{
		$query = mysqli_query($db, "SELECT * FROM menu");
		$i = 0;
		while ($row = $query->fetch_assoc())
		{
			$id = $row['id'];
			$name = $row['name'];
			$url = $row['url'];
			$i++;
			echo "<center><h2>Редактирование пункта номер $id</h2>
			<form name=\"menu\" action=\"control.php?menu=1\" method=\"post\" >
			<input type=\"hidden\" name=\"count\" value=$i>
			<br /><b>Пункт: </b>
			<textarea name=\"edit_menu\" cols=\"100%\" rows=\"1\">$name</textarea>
			<br /><b>URL: &nbsp;&nbsp;&nbsp;</b>
			<textarea name=\"edit_url\" cols=\"100%\" rows=\"1\">$url</textarea>
			<br /><br /><input type=\"submit\" name=\"submit_edit\" value=\"Изменить\"/>
			</form></center><br /><br />";
		}
		if(isset($_POST['submit_edit']))
		{
			$query = mysqli_query($db, "SELECT * FROM menu");
			$current_count = $_POST['count'];
			$edit_name = $_POST['edit_menu'];
			$edit_url = $_POST['edit_url'];
			$querye = mysqli_query($db, "UPDATE `menu` SET `name` = '$edit_name', `url` = '$edit_url' WHERE id = '$current_count'");
		}
	}
	if(isset($_GET['news']))
	{
		if($_GET['news'] == 1 AND $_GET['add'] == 1 AND $_GET['edit'] == 0)
		{
			echo "<form name=\"add\" action=\"control.php?news=1&add=1&edit=0\" method=\"post\" >
					<center><h2>Добавить новую новость</h2></center>
					<center><label><br />Название:</label><br />
					<textarea name=\"name\" cols=\"90%\" rows=\"1\"></textarea></center>
					<center><label><br />Текст:</label><br />
					<textarea name=\"text\" cols=\"90%\" rows=\"10\"></textarea></center>
					<center><input type=\"submit\" name=\"submit\" value=\"Опубликовать\" /></center>
				  </form>";
			if(isset($_POST['submit']))
			{
				$name = $_POST['name'];
				$text = $_POST['text'];
				$date = date("Y-m-d");
				$query = mysqli_query($db, "INSERT INTO `news` (`name`, `text`, `date`) VALUES ('$name', '$text', '$date')");
			}
			
		}
		if($_GET['news'] == 1 AND $_GET['edit'] == 1 AND $_GET['add'] == 0)
		{
			$query = mysqli_query($db, "SELECT * FROM news");
			$i = 0;
			while ($row = $query->fetch_assoc())
			{
				$id = $row['id'];
				$name = $row['name'];
				$text = $row['text'];
				$date = $row['date'];
				$i++;
				echo "<center><h2>Новость номер $id от $date</h2>
				<form name=\"add\" action=\"control.php?news=1&edit=1&add=0\" method=\"post\" >
				<input type=\"hidden\" name=\"count\" value=$i>
				<br /><b>Название: &nbsp;</b>
				<textarea name=\"edit_name\" cols=\"100%\" rows=\"1\">$name</textarea>
				<br /><b>Текст: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
				<textarea name=\"edit_text\" cols=\"100%\" rows=\"10\">$text</textarea>
				<br /><br /><input type=\"submit\" name=\"submit_edit\" value=\"Изменить\"/>
				</form></center><br /><br />";
			}
			if(isset($_POST['submit_edit']))
			{
				$query = mysqli_query($db, "SELECT * FROM news");
				$current_count = $_POST['count'];
				$edit_name = $_POST['edit_name'];
				$edit_text = $_POST['edit_text'];
				$querye = mysqli_query($db, "UPDATE `news` SET `name` = '$edit_name', `text` = '$edit_text' WHERE id = '$current_count'");
			}
		}
	}
}
else
{
	setcookie("admincookies", 0, time()+60*60*24*365*10);
    echo("You are not administrator <br /> Please, <a href=\"index.php\">Update your session</a>");
}
?>