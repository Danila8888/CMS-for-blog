<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html; charset=utf-8"
http-equiv="Content-Type" />
<title>Личный блог - Данила Лазарев</title>
<link rel="stylesheet" type="text/css" href="css/style.css" >
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald:400,300" type="text/css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"  type="text/javascript"></script>
    <script>
        $(document).ready(function(){
            $('#btn_submit').click(function(){
                var name    = $('#name').val();
                var email   = $('#mail').val();
                var message = $('#message').val();
                $.ajax({
                    url: "send.php",
                    type: "post",
                    dataType: "json",
                    data: {
                        "name":    name,
                        "mail":   email,
                        "message": message
                    },
                    success: function(data){
                        $('.messages').html(data.result);
                    }
                });
            });
        });
    </script>
</head>
<?php
include ("includes/db.php"); 
$query = mysqli_query($db, "SELECT name, url FROM menu");
while ($row = $query->fetch_assoc())
{
	$menu_name[] = $row['name'];
	$menu_url[] = $row['url'];
}

$query = mysqli_query($db, "SELECT id, name FROM news ORDER BY id DESC");
while ($row = $query->fetch_assoc())
{
	$news_id[] = $row['id'];
	$news[] = $row['name'];
}

$query = mysqli_query($db, "SELECT * FROM news ORDER BY id DESC LIMIT 1");
while ($row = $query->fetch_assoc())
{
	$news_name = $row['name'];
	$news_date = $row['date'];
	$news_textt = mb_substr($row['text'], 0, 125, 'UTF-8');
	$news_idd = $row['id'];
}
?>
<body>
<div id="wrapper">
    <header>
        <a href="/kt"><img src="img/logo.png" alt="Данила Лазарев" width = "300px" height = "69px" /></a>
        <form name = "search" id = "search" action="" method="get">
            <input type="text" name="" placeholder="Поиск по сайту" class = "input" />
            <input type="submit" name="" value="" class="submit" />
        </form>
    </header>
	<nav>
        <ul id = "menu">
            <li><a href="<?php echo$menu_url[0];?>"><?php echo$menu_name[0];?></a></li>
            <li><a href="<?php echo$menu_url[1];?>"><?php echo$menu_name[1];?></a></li>
            <li><a href="<?php echo$menu_url[2];?>"><?php echo$menu_name[2];?></a></li>
            <li><a href="<?php echo$menu_url[3];?>"><?php echo$menu_name[3];?></a></li>
        </ul>
    </nav>
    <section>    
        <aside>
            <ul id = "aside-menu">
			    <center><b>Последние новости<br /><br /></b></center>
                <li><a href="news.php?id=<?php echo$news_id[0];?>"><?php echo$news[0];?></a></li>
                <li><a href="news.php?id=<?php echo$news_id[1];?>"><?php echo$news[1];?></a></li>
                <li><a href="news.php?id=<?php echo$news_id[2];?>"><?php echo$news[2];?></a></li>
                <li><a href="news.php?id=<?php echo$news_id[3];?>"><?php echo$news[3];?></a></li>
            <ul>
        </aside>
    </section>
	

   <center><div id="feedback">
   <div class="messages"></div>
      <label>Имя:</label>
      <input maxlength="30" type="text" name="name" id="name" />
      <label>E-mail:</label>
      <input maxlength="50" type="text" name="mail" id="mail"/>
      <label>Сообщение:</label>
      <textarea rows="7" cols="50" name="message" id="message"></textarea><br />
      <input type="submit" value="Отправить" id="btn_submit"/>
   </div></center>
 

</div>
<footer>
    <div id="footer">
        <div id="twitter">
              <h3>Последняя новость</h3>
                <time datetime="<?php echo$news_date;?>"><a href="news.php?id=<?php echo$news_idd;?>"><?php echo$news_date;?></a></time>
                <p>
					<?php echo$news_textt;?>......
                </p>
        </div> 
        <div id="sitemap">
            <h3>Карта блога</h3>
            <div>
                <a href="<?php echo$menu_url[0];?>"><?php echo$menu_name[0];?></a>
                <a href="<?php echo$menu_url[1];?>"><?php echo$menu_name[1];?></a>
            </div>
            <div>
                <a href="<?php echo$menu_url[2];?>"><?php echo$menu_name[2];?></a>
                <a href="<?php echo$menu_url[3];?>"><?php echo$menu_name[3];?></a>
            </div>
        </div>
        <div id="social">
                <h3>Социальные сети</h3>
                <a href="#"><img src="img/twitter.png" width="30px" height="30px" /></a>
                <a href="http://vk.com/id91842195" target="_blank"><img src="img/vk.png" width="30px" height="30px" /></a>
                <a href="mailto:lazarev.fsfd@ya.ru"><img src="img/mail.png" width="30px" height="30px" /></a>

        </div>
        <div id="footer-logo">
            <a href="#"><img src="img/logo.png" width="150px" height="40px" alt="Лазарев Данила"></a>
            <p>Copyright © 2017 Личный блог - Лазарев Данила</p>
        </div>
    </div>
</footer>
</body>

</html>