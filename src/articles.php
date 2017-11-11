<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html; charset=utf-8"
http-equiv="Content-Type" />
<title>Личный блог - Данила Лазарев</title>
<link rel="stylesheet" type="text/css" href="css/articles.css" >
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald:400,300" type="text/css">
</head>
<?php
include ("includes/db.php"); 
$query = mysqli_query($db, "SELECT name, url FROM menu");
while ($row = $query->fetch_assoc())
{
	$menu_name[] = $row['name'];
	$menu_url[] = $row['url'];
}

$query = mysqli_query($db, "SELECT * FROM news ORDER BY id DESC LIMIT 1");
while ($row = $query->fetch_assoc())
{
	$news_name = $row['name'];
	$news_date = $row['date'];
	$news_textt = mb_substr($row['text'], 0, 125, 'UTF-8');
	$news_idd = $row['id'];
}

if(isset($_GET['id']))
{
	$output = "";
	$query = mysqli_query($db, "Select * FROM articles WHERE id =" . (int)$_REQUEST['id']);
	while ($row = $query->fetch_assoc())
	{
		$article_id = $row['id'];
		$article_name = $row['name'];
		$article_text = $row['text'];
		$article_path = $row['path'];
		$output = "<div id=\"heading\">
						<h1><a href = \"article.php?id=$article_id\">$article_name</a></h1>
					</div>
					<figure>
					<p>
						<img src = \"$article_path\" width=\"400px\" height=\"400px\"/>$article_text 
					</p>
					</figure>";
	}
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

	<?php 
		if(isset($_GET['id']))
		{
			if($output == "")
				print("<br /><br /><br /><br /><br /><br /><br /><center><h2>Warning! Неизвестен ID Записи</h2></center><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />");
			else
				echo $output;
		}
		else
		{
			print("<br /><br /><br /><br /><br /><br /><br /><center><h2>Warning! Неизвестен ID Записи</h2></center><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />");
		}
	?>
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