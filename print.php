<?php
header("Content-Type: text/html; charset=utf-8");
# Подключаем конфиг
include 'conf.php';
if ((Enable == 'True') or ($_SERVER['REMOTE_ADDR'] == admin_ip))
{
#Заносим значения в перменные из GETa
$hash=htmlspecialchars($_GET["hash"]);
$personal_info = mysql_fetch_array(mysql_query("SELECT * FROM personal_info_lag WHERE hash='".$hash."'"));
$id = $personal_info['id_uchasnika'];
$date_time =  mysql_fetch_array(mysql_query("SELECT * FROM date_time_lag WHERE id_uchasnika='".$id."'"));
if ($id > 0 and $hash == $personal_info['hash'])
{
$img="./img/talon.jpg";
$pic = ImageCreateFromjpeg($img); //открываем рисунок в формате JPEG
$color=ImageColorAllocate($pic, 240, 0, 0); //получаем идентификатор цвета
/* определяем место размещения текста по вертикали и горизонтали */
$h = 474; //высота
$w = 170; //ширина
/* выводим текст на изображение */
ImageTTFtext($pic, 50, 0, $w, $h, $color, "propisi", $date_time['date_show']);
ImageTTFtext($pic, 50, 0, $w+370, $h+4, $color, "propisi", $date_time['time_show']);
ImageTTFtext($pic, 50, 0, $w+152, $h+86, $color, "propisi", $personal_info['fam']);
ImageTTFtext($pic, 50, 0, $w+68, $h+150, $color, "propisi", $personal_info['name']);
ImageTTFtext($pic, 50, 0, $w+156, $h+216, $color, "propisi", $personal_info['lastname']);
Imagejpeg($pic,"./img/".$hash.".jpg"); //сохраняем рисунок в формате JPEG
ImageDestroy($pic); //освобождаем память и закрываем изображение
if(isset($_POST['close']))
{
	echo("<script>location.href='index.php'</script>");
}					
echo ("
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title></title>
 <style type=\"text/css\">
<!--
body { font: 12px Georgia; color: #666; }
h3 { font-size: 16px; text-align: center; }
h4 {font-size: 16px; text-align: center;}
h0 {font-size: 16px; text-align: left;}
table {
font-family: \"Lucida Sans Unicode\", \"Lucida Grande\", Sans-Serif;
font-size: 14px;
border-radius: 10px;
border-spacing: 2;
text-align: center;
}
th {
background: #BCEBDD;
color: white;
text-shadow: 0 1px 1px #2D2020;
padding: 10px 20px;
}
th, td {
border-style: solid;
border-width: 0 1px 1px 0;
border-color: white;
text-align: center;
}
th:first-child, td:first-child {
}
th:first-child {
border-top-left-radius: 10px;
}
th:last-child {
border-top-right-radius: 10px;
border-right: none;
}
td {
padding: 10px 20px;
background: #F8E391;
}
td.red{font: 12px Georgia; color: #FF0000; font-weight: 600;}
td.bold{font: 12px Georgia; font-weight: 600;}
td.busy{background: red;}
td.free{background: green;}
tr:last-child td:first-child {
border-radius: 0 0 0 10px;
}
tr:last-child td:last-child {
border-radius: 0 0 10px 0;
}
tr td:last-child {
border-right: none;
}
textarea { width: 250px; height: 100px; border: solid 1px #CCC; color: #000000; }
.buttons { width: auto; border: double 1px #666; background: #D6D6D6; color: #000; align: center }
#num { width: 20px; margin-left: 5px; }
-->
</style>
 
</head>
 
<body>
 <a href=\"https://ru.wikipedia.org/wiki/%D0%91%D0%B5%D1%82%D0%B0-%D1%82%D0%B5%D1%81%D1%82%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D0%B5\" target=\"_blank\"><img <img align=\"left\" src=\"./img/beta_test.jpg\"></a><a href=\"./img/tutorial.gif\" target=\"_blank\"><img align=\"right\" src=\"./img/tutorial.png\"></a>
<h3>Муниципальное автономное общеобразовательное учреждение</h3>

<h3>Средняя общеобразовательная школа № 34</h3>

<h4>Запись в 1й класс</h4>
");
	echo "<table align=\"center\" border=\"2\" cellpadding=\"2\" cellspacing=\"2\">\n";
	echo "</tr><tr>\n";
	echo "<form action=\"\" method=\"post\" name=\"login_form\">\n";
    echo "<td class=\"red\" colspan=\"7\" align=\"center\"></td>\n";
	echo "</tr><tr>\n";
	echo "<td colspan=\"7\" align=\"center\"><img width=\"300\" height=\"300\" src=\"./img/".$hash.".jpg\"></td>\n";
	echo "</tr><tr>\n";
	echo "<td class=\"busy\"><input align=\"center\" type=\"submit\" name=\"close\" value=\"Закрыть\" onclick=\"return confirm('Вы уверены что хотите закрыть? После закрытия данные будут недоступны!'); void 0;\"/></td><td ><input type=\"submit\" name=\"print\" value=\"Печать\" onclick=\"javascript:window.print(); void 0;\"/></td>\n";
   	echo "</tr><tr>\n";
    echo "</table>";
	echo "<p></p>";
	echo "<table align=\"center\" border=\"2\" cellpadding=\"2\" cellspacing=\"2\">\n";
	echo "<td><font size=\"3\">Время, указанное в талоне, является временем приема пакета документов:</font>";
	echo "<p align=\"left\"><font size=\"3\">- заполненного бланка заявления,</font></p>";
	echo "<p></p>";
	echo "<p align=\"left\"><font size=\"3\">- заполненного согласия на обработку персональных данных,</font></p>";
	echo "<p></p>";
	echo "<p align=\"left\"><font size=\"3\">- оригиналов и копий предъявляемых документов.</font></p>";
	echo "</tr><tr>\n";
    echo "</table>";
}
else
{
		echo"<p align=\"center\"><font size=\"5\">Ошибка хэша</p>";
		echo"<p align=\"center\">Настоятельно рекомендуем <b>НЕ регистрироваться</b> повторно, а уточнить</p>";
		echo"<p align=\"center\">была ли попытка регистрации успешной обратившись по телефону: <b>8 3439 301957</b></p>";
		echo"<p align=\"center\">или адресу электронной почты: <b><a href=\"mailto:school34-ku@rambler.ru\">school34-ku@rambler.ru</a></b></p>";
		echo"<p align=\"center\">Проблема может быть вызвана: ограничениями браузера, изменение хэша в ручную, ошибки системы регистрации</a></b></p></font>";
}
}
else
{
echo('<p align=center>Сайт недоступен, ведутся технические работы. Просим прощения за доставленные неудобства.<p>');	
}

?>