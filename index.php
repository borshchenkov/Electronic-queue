<?php
session_start();
header("Content-Type: text/html; charset=utf-8");
# Подключаем конфиг
include 'conf.php';
if ((Enable == 'True') or ($_SERVER['REMOTE_ADDR'] == admin_ip))
{
#Заносим значения в перменные из GETa
$date=htmlspecialchars($_GET["date"]);
$time=htmlspecialchars($_GET["time"]);
settype($date,'integer');
settype($time,'integer');
#Подгружаем все свободные даты
$date_time = mysql_fetch_array(mysql_query("SELECT * FROM date_time_lag WHERE id_uchasnika IS NULL"));
$url= SITE_URL."?date=".$date."&time=".$time;
#//Присвоение переменной $mess значения "ошибки"
$errors = $_COOKIE['errors'];
setcookie('errors', '', time() +3600);
sleep(2);
$mess = $error[$errors];
$date_time_chosen = mysql_fetch_array(mysql_query("SELECT * FROM date_time_lag WHERE id_uchasnika IS NULL AND date='".$date."' AND time='".$time."'"));
echo mysql_error();
#Если нажата кнопка регистрации
if(isset($_POST['submit']))
{
	
	if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $_POST['keystring'])
	{
			if(isset($_POST['personal_data_agree']))
			{
			if(isset($_POST['reglament_agree']))
			{
			if (empty($date_time_chosen[date]))
				{
				setcookie("errors", '1', time() +3600);
				sleep(2);
				echo("<script>location.href='$url'</script>");
				exit;
				}
					if (empty($_POST['fam']) || empty($_POST['name']) || empty($_POST['lname']))
					{
					setcookie("errors", '3', time() +3600);
					sleep(2);
					echo("<script>location.href='$url'</script>");
					}
					else
					{
					$hash = md5(md5(34*($time*$date)));
					$query = "INSERT INTO personal_info_lag SET fam='".mysql_real_escape_string($_POST['fam'])."', name='".mysql_real_escape_string($_POST['name'])."', lastname='".mysql_real_escape_string($_POST['lname'])."', pers_info_agree='Согласен', reglament_agree='Согласен', ip_address='".$_SERVER['REMOTE_ADDR']."', hash='".$hash."'";
					/* Выполняем запрос. Если произойдет ошибка - вывести ее. */
					mysql_query($query) or die (mysql_error());
					$id=mysql_insert_id();
					$query = "UPDATE date_time_lag SET id_uchasnika='".mysql_insert_id()."' WHERE date='".$date."' AND time='".$time."'";
					/* Выполняем запрос. Если произойдет ошибка - вывести ее. */
					}
	}
	else
	{
	echo "<script>alert('К сожалению без ознакомления и согласия с регламентом вы не сможете продолжить.');</script>";
	}
	}
	else
	{
	echo "<script>alert('К сожалению без ознакомления и согласия с правилами обработки персональных данных вы не сможете продолжить.');</script>";	
	}
}
else
{
setcookie("errors", '0', time() +3600);
sleep(2);
echo("<script>location.href='$url'</script>");
}	
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
h4 {font-size: 16px; text-align: center; font-color: red;}
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
text-align: left;
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
td.free{font: 12px Georgia; color: green; font-weight: 600;}
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
#num { width: 20px; margin-left: 5px; float: left; }
-->
</style>
 
</head>
 
<body>
 <a href=\"https://ru.wikipedia.org/wiki/%D0%91%D0%B5%D1%82%D0%B0-%D1%82%D0%B5%D1%81%D1%82%D0%B8%D1%80%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D0%B5\" target=\"_blank\"><img <img align=\"left\" src=\"./img/beta_test.jpg\"></a><a href=\"./img/tutorial.gif\" target=\"_blank\"><img align=\"right\" src=\"./img/tutorial.png\"></a>
<h3>Муниципальное автономное общеобразовательное учреждение</h3>

<h3>Средняя общеобразовательная школа № 34</h3>

<h4>Запись в школьный лагерь</h4>




");
if($date <= 0)
{
	echo "<table align=\"center\" border=\"2\" cellpadding=\"2\" cellspacing=\"2\">\n";
	echo "</tr><tr>\n";
	echo "<td class=\"red\" colspan=\"2\">Март  2018 </td><td class=\"red\" colspan=\"5\"></td>\n";
	echo "</tr><tr>\n";
	echo "<td class=\"bold\">Пн</td><td class=\"bold\">Вт</td><td class=\"bold\">Ср</td><td class=\"bold\">Чт</td><td class=\"bold\">Пт</td><td class=\"red\">Сб</td><td class=\"red\">Вс</td>\n";
	echo "</tr><tr>\n";
	echo "<td></td><td></td><td></td><td  class=\"free\"><a href=\"".SITE_URL."index.php?date=103\">1</a></td><td>2</td><td class=\"red\">3</td><td class=\"red\">4</td>\n";
	echo "</tr><tr>\n";
	echo "<td class=\"free\"><a href=\"".SITE_URL."index.php?date=503\">5</a></td><td>6</td><td class=\"red\">7</td><td class=\"red\">8</td><td>9</td><td class=\"red\">10</td><td class=\"red\">11</td>\n";
	echo "</tr><tr>\n";
	echo "<td class=\"free\"><a href=\"".SITE_URL."index.php?date=1203\">12</a></td><td>13</td><td>14</td><td class=\"free\"><a href=\"".SITE_URL."index.php?date=1503\">15</a></td><td>16</td><td class=\"red\">17</td><td class=\"red\">18</td>\n";
	echo "</tr><tr>\n";
	echo "<td class=\"free\"><a href=\"".SITE_URL."index.php?date=1903\">19</a></td><td>20</td><td>21</td><td class=\"free\"><a href=\"".SITE_URL."index.php?date=2203\">22</a></td><td>23</td><td class=\"red\">24</td><td class=\"red\">25</td>\n";
	echo "</tr><tr>\n";
	echo "<td class=\"free\"><a href=\"".SITE_URL."index.php?date=2603\">26</a></td><td>27</td><td>28</td><td class=\"free\"><a href=\"".SITE_URL."index.php?date=2903\">29</a></td><td>30</td><td  class=\"red\">31</td><td></td>\n";
	echo "</tr><tr>\n";
    echo "</table>";
	echo "<p></p>";
	echo "<table align=\"center\" border=\"2\" cellpadding=\"2\" cellspacing=\"2\">\n";
	echo "</tr><tr>\n";
	echo "<td class=\"red\" colspan=\"2\">Апрель  2018 </td><td class=\"red\" colspan=\"5\"></td>\n";
	echo "</tr><tr>\n";
	echo "<td class=\"bold\">Пн</td><td class=\"bold\">Вт</td><td class=\"bold\">Ср</td><td class=\"bold\">Чт</td><td class=\"bold\">Пт</td><td class=\"red\">Сб</td><td class=\"red\">Вс</td>\n";
	echo "</tr><tr>\n";
	echo "<td></td><td></td><td></td><td></td><td></td><td class=\"red\"></td><td class=\"red\">1</td>\n";
	echo "</tr><tr>\n";
	echo "<td class=\"free\">2</td><td>3</td><td>4</td><td class=\"free\">5</td><td>6</td><td class=\"red\">7</td><td class=\"red\">8</td>\n";
	echo "</tr><tr>\n";
	echo "<td class=\"free\">9</td><td>10</td><td>11</td><td class=\"free\">12</td><td>13</td><td class=\"red\">14</td><td class=\"red\">15</td>\n";
	echo "</tr><tr>\n";
	echo "<td class=\"free\">16</td><td>17</td><td>18</td><td class=\"free\">19</td><td>20</td><td class=\"red\">21</td><td class=\"red\">22</td>\n";
	echo "</tr><tr>\n";
	echo "<td class=\"free\">23</td><td>24</td><td>25</td><td>26</td><td>27</td><td class=\"red\">28</td><td class=\"red\">29</td>\n";
	echo "</tr><tr>\n";
	echo "<td>30</td><td></td><td></td><td></td><td></td><td class=\"red\"></td><td class=\"red\"></td>\n";
	echo "</tr><tr>\n";
    echo "</table>";
}
else
{
	if ($date > 0 and $time <= 0)
	{
	echo "<table align=\"center\" border=\"2\" cellpadding=\"2\" cellspacing=\"2\">\n";
	echo "</tr><tr>\n";
		#Формирование списка доступного времени
		$query = "SELECT * FROM date_time_lag WHERE date = '".$date."'";
		$result = mysql_query($query);
			if (!$result)
			{
			echo "Ошибка при запросе";
			exit(mysql_error());
			}
 			$myarray = array(); // создаем пустой массив, страховка
			$n = mysql_num_rows($result); // Узнаем количество элементов в выборке
 			for($i = 0; $i < $n; $i++)
				{
				$myarray[] = mysql_fetch_array($result);
				}
 				foreach($myarray as $value)
				{
				if (empty($value['id_uchasnika']))
				{
				echo "<td>Выбранная дата: <b>".$value['date_show']."</b></td><td>Доступное время: <b>".$value['time_show']."</b></td><td><a href=".SITE_URL."index.php"."?date=".$value['date']."&time=".$value['time'].">Выбрать</a></td>\n";
				}
				else
				{
				echo "<td>Выбранная дата: <b>".$value['date_show']."</b></td><td>Доступное время: <b>".$value['time_show']."</b></td><td><font color=red >Время занято</font></td>\n";
				}
				echo "</tr><tr>\n";
				}
				mysql_free_result($result);
				echo "</table>";
				echo "<p></p>";
				echo "<center><b><a href=".SITE_URL."index.php".">Назад</a></b></center>";
}
else
{
	if ($date > 0 and $time > 0)
	{
	echo "<table align=\"center\" border=\"2\" cellpadding=\"2\" cellspacing=\"2\">\n";
	echo "</tr><tr>\n";
	echo "<form action=\"\" method=\"POST\" name=\"registraton\">\n";
	echo "<td class=\"red\" colspan=\"7\" align=\"center\">Форма регистрации заявки</td>\n";
	echo "</tr><tr>\n";
	echo "<td>Выбранное Вами время:</td><td><b>".$date_time_chosen['date_show']."    ".$date_time_chosen['time_show']."</b></td>\n";
    echo "</tr><tr>\n";
	echo "<td>Фамилия ребенка:</td><td><input placeholder=\"Введите Фамилию ребенка\" type=\"text\" name=\"fam\" /></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Имя ребёнка:</td><td><input placeholder=\"Введите Имя ребенка\" type=\"text\" name=\"name\" /></td>\n";
	echo "</tr><tr>\n";
	echo "<td>Отчество ребенка:</td><td><input placeholder=\"Введите Отчество ребенка\" type=\"text\" name=\"lname\" /></td>\n";
	echo "</tr><tr>\n";
	echo "<td>Согласен с обработкой <p><a href='".SITE_URL."sogl_pers.pdf' target=\"_blank\"> персональных данных</a></td><td><input placeholder=\" \" type=\"checkbox\" name=\"personal_data_agree\" /></td>\n";
    echo "</tr><tr>\n";
	echo "<td>С <a href='".SITE_URL."alg_rab.pdf' target=\"_blank\">алгоритмом работы</a> <p>электронного средства <p>записи ознакомлен(а).</td><td><input placeholder=\" \" type=\"checkbox\" name=\"reglament_agree\" /></td>\n";
    echo "</tr><tr>\n";
	echo "<td></td><td><img src=\"./captcha?".session_name()."=".session_id()."\"></td>\n";
	echo "</tr><tr>\n";
	echo "<td></td><td><input placeholder=\" Введите текст с картинки\" type=\"text\" name=\"keystring\" /></td>\n";
    echo "</tr><tr>\n";
	echo "<td class=\"red\"> ".$mess."</td><td class=\"free\"><input type=\"submit\" name=\"submit\" align=\"center\" value=\"Зарегистрировать\" /></td>\n";
   	echo "</tr><tr>\n";
    echo "</table>";
	}
}
}
}
else
{
echo('<table align="center" border="1">
<td align="center"><font color="red"><b>Сайт недоступен.<b></font></td> 
<tr></tr>
<td><p align="center">Ведутся технические работы, либо работа сайта приостановлена по иным причинам. <p align="center">Просим прощения за доставленные неудобства.</p> Если у Вас есть вопросы либо предложения по работе ресурса пишите нам на <a href="mailto:school34-ku@rambler.ru">адрес электронной почты</td>
</table>
');	
}
unset($_SESSION['captcha_keystring']);
?>