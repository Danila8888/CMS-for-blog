<?php
if(isset($_POST['name']) AND isset($_POST['mail']) AND isset($_POST['message'])){
	include("includes/db.php");
	$msg = "";
	$name = $_POST['name'];
	$mail = $_POST['mail'];
	$message = $_POST['message'];
	$ip = $_SERVER['REMOTE_ADDR'];
	$date = date("Y-m-d");
	$error = array();
	if($name == "")
	{
		$error[] = "Введите свое имя";
	}
	if($mail == "")
	{
		$error[] = "Введите свой e-mail";
	}
	if($message == "")
	{
		$error[] = "Введите текст сообщения";
	}
	// if(!preg_match("/^[a-zа-яёA-ZА-Я0-9_\-\ ]+$/ui", $name))
	if(!preg_match("/^[a-zа-яё0-9_\-\.\@\,\!\*\% ]+$/ui", $name))
	{
		$error[] = "Введите корректное имя";
	}
	if(!preg_match("~^([a-zA-Z0-9_\-\.])+@([a-z0-9_\-\.])+\.([a-z0-9])+$~i", $mail))
	{
		$error[] = "Введите корректный e-mail";
	}
	if(!preg_match("/^[a-zа-яё0-9_\-\.\@\,\)\!\*\%\( ]+$/ui", $message))
	{
		$error[] = "Введите корректное сообщение";
	}

	if(empty($error))
	{
		$query = mysqli_query($db, "INSERT INTO `feedback` (`name`, `mail`, `message`, `ip`, `date`) VALUES ('$name', '$mail', '$message', '$ip', '$date')");
		$msg = "<h2>Сообщение успешно отправлено. Возможно, вам ответят.</h2>";
	}
	else
	{
		$msg = "";
		foreach($error as $one_error)
		{
			$msg .= "<span style='color: red;'>$one_error</span><br/>";
		}
	}
	echo json_encode(array('result' => $msg));
}
else
{
	echo "Access denied";
}
?>