<?php
header("Content-Type: text/html; charset=utf-8");
session_start();
# Подключаем конфиг
include 'conf.php';
if ((Enable == 'True') or ($_SERVER['REMOTE_ADDR'] == admin_ip))
{
#Заносим значения в перменные из GETa
$hash=htmlspecialchars($_COOKIE['hash']);
$del=htmlspecialchars($_GET["del"]);
$show=htmlspecialchars($_GET["show"]);
if ($_SERVER['REMOTE_ADDR'] == admin_ip)
{
if (isset($_POST['master_key_btn']))
{
	if (md5($_POST['master_key']) == md5(admin_key))
{
	setcookie('master_key', md5(admin_key), time() +300);
	sleep(4);
	echo ('<script language="javascript">alert("Ключ верный, куки установлены на 5 минут")</script>');
	echo("<script>location.href='".SITE_URL."admin.php'</script>");
}
}
if ($_COOKIE['master_key'] == md5(admin_key))
{
	if ((isset($_POST['export_date_time']) != NULL) or (isset($_POST['export_personal_info']) != NULL))
		{
		if (isset($_POST['export_date_time']) != NULL)
		{
		$DB_TBLName = "date_time_lag"; //MySQL Table Name   
		$filename = "lager_export_date_time_".date('d-m-y')."_".date('H:i:s', time());         //File Name
		}
		else
		{
		$DB_TBLName = "personal_info_lag"; //MySQL Table Name   
		$filename = "lager_export_personal_info_".date('d-m-y')."_".date('H:i:s', time());         //File Name
		}
   
//create MySQL connection   
$sql = "Select * from $DB_TBLName";
$Connect = @mysql_connect(DB_HOST, DB_LOGIN, DB_PASSWORD) or die("Couldn't connect to MySQL:<br>" . mysql_error() . "<br>" . mysql_errno());
//select database   
$Db = @mysql_select_db(DB_NAME, $Connect) or die("Couldn't select database:<br>" . mysql_error(). "<br>" . mysql_errno());   
//execute query 
$result = @mysql_query($sql,$Connect) or die("Couldn't execute query:<br>" . mysql_error(). "<br>" . mysql_errno());    
$file_ending = "xls";
//header info for browser
header("Content-Type: application/xls");    
header("Content-Disposition: attachment; filename=$filename.xls");  
header("Pragma: no-cache"); 
header("Expires: 0");
/*******Start of Formatting for Excel*******/   
//define separator (defines columns in excel & tabs in word)
$sep = "\t"; //tabbed character
//start of printing column names as names of MySQL fields
for ($i = 0; $i < mysql_num_fields($result); $i++) {
echo mysql_field_name($result,$i) . "\t";
}
print("\n");    
//end of printing column names  
//start while loop to get data
    while($row = mysql_fetch_row($result))
    {
        $schema_insert = "";
        for($j=0; $j<mysql_num_fields($result);$j++)
        {
            if(!isset($row[$j]))
                $schema_insert .= "NULL".$sep;
            elseif ($row[$j] != "")
                $schema_insert .= "$row[$j]".$sep;
            else
                $schema_insert .= "".$sep;
        }
        $schema_insert = str_replace($sep."$", "", $schema_insert);
        $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
        $schema_insert .= "\t";
        print(trim($schema_insert));
        print "\n";
		
	}
exit();
} 
	if (isset($_POST['rebuild_db']))
	{
		$sqlfile = 'registration_lag.sql';
			if (!file_exists($sqlfile));
			$open_file = fopen ($sqlfile, "r");
			$buf = fread($open_file, filesize($sqlfile));
			fclose ($open_file);
 			$a = 0;
			while ($b = strpos($buf,";",$a+1)){
			$i++;
			$a = substr($buf,$a+1,$b-$a);
			mysql_query($a);
			$a = $b;
			}
			echo ('<script language="javascript">alert("База данных успешно сформирована")</script>');
			}
if (isset($_POST['show_btn']))
		{
		echo("<script>location.href='".SITE_URL."admin.php?show=".$_POST['show_choise']."'</script>");
		}	
if (isset($_POST['delete_db']))
		{
		$query = "DROP TABLE IF EXISTS personal_info_lag, date_time_lag";
		/* Выполняем запрос. Если произойдет ошибка - вывести ее. */
		mysql_query($query) or die (mysql_error());
		echo ('<script language="javascript">alert("База данных успешно удалена")</script>');
		echo("<script>location.href='".SITE_URL."admin.php'</script>");
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
table {
font-family: \"Lucida Sans Unicode\", \"Lucida Grande\", Sans-Serif;
font-size: 14px;
border-radius: 10px;
border-spacing: 2;
}
table.fixed{width: 95%;}
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
td.fixed_date_time{width: 150px;}
td.fixed_info{width: 100px; text-align: left; }
td.fixed_delbtn{width: 30px;}
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
 
<h3>Муниципальное автономное общеобразовательное учреждение</h3>

<h3>Средняя общеобразовательная школа № 34</h3>

<h4>Запись в школьный лагерь - панель администратора</h4>
");
	echo "<table align=\"center\" border=\"2\" cellpadding=\"2\" cellspacing=\"2\">\n";
	echo "</tr><tr>\n";
	echo "<form action=\"\" method=\"post\" name=\"login_form\">\n";
    echo "<td >Подготовить базу:</td><td class=\"busy\"><input align=\"center\" type=\"submit\" name=\"rebuild_db\" value=\"Подготовить\" onclick=\"return confirm('Вы уверены что хотите переформировать базу данных, эта операция повридит ВСЮ существующую информацию!'); void 0;\"/></td>\n";
	echo "</tr><tr>\n";
	echo "<td >Удалить базу:</td><td class=\"busy\"><input align=\"center\" type=\"submit\" name=\"delete_db\" value=\"Удалить\" onclick=\"return confirm('Вы уверены что хотите удалить базу данных, эта операция повридит ВСЮ существующую информацию!'); void 0;\"/></td>\n";
	echo "</tr><tr>\n";
	echo "<td >Експорт базы: </td><td class=\"free\"><input align=\"center\" type=\"submit\" name=\"export_personal_info\" value=\"Личные данные\"/> <input align=\"center\" type=\"submit\" name=\"export_date_time\" value=\"Дату и время\"/></td>\n";
	echo "</tr><tr>\n";
	echo "<td align=\"center\"></td>\n";
	echo "</tr><tr>\n";
	echo "<td><select name=\"show_choise\">
	<option value=\"103\">01.03.2018 г</option>
	<option value=\"503\">05.03.2018 г</option>
	<option value=\"1203\">12.03.2018 г</option>
	<option value=\"1503\">15.03.2018 г</option>
	<option value=\"1903\">19.03.2018 г</option>
	<option value=\"2203\">22.03.2018 г</option>
	<option value=\"2603\">26.03.2018 г</option>
	<option value=\"2903\">29.03.2018 г</option>
	<option value=\"0204\">02.04.2018 г</option>
	<option value=\"0504\">05.04.2018 г</option>
	<option value=\"0904\">09.04.2018 г</option>
	<option value=\"1204\">12.04.2018 г</option>
	<option value=\"1604\">16.04.2018 г</option>
	<option value=\"1904\">19.04.2018 г</option>
	<option value=\"2304\">23.04.2018 г</option>
	<td></select><input type=\"submit\" name=\"show_btn\" value=\"Показать\"/></td>\n";
   	echo "</tr><tr>\n";
    echo "</table>";
	echo('<p></p>');
	echo('<p></p>');
	echo('<p></p>');
	if ($show > 0)
	{
	#Формирование списка зарегистрировавшихся участников
	$query = "SELECT * FROM date_time_lag WHERE date = '".$show."' AND id_uchasnika IS NOT NULL";
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
				$personal_info =  mysql_fetch_array(mysql_query("SELECT * FROM personal_info_lag WHERE id_uchasnika = '".$value['id_uchasnika']."'"));
				echo "<table class=\"fixed\" align=\"center\" border=\"2\" cellpadding=\"2\" cellspacing=\"2\">\n";
				echo "<td class=\"fixed_date_time\">Дата и время: <b>".$value['date_show']." ".$value['time_show']." </b></td><td> ID: <b> ".$value['id_uchasnika']." </b>  ФИО: <b>".$personal_info['fam']." ".$personal_info['name']." ".$personal_info['lastname']." </b>Штамп времени: <b> ".$personal_info['time_stamp']." </b> IP адрес: <b> ".$personal_info['ip_address']."</b></td><td class=\"fixed_delbtn\"><a href=".SITE_URL."admin.php"."?del=".$value['id_uchasnika'].">Удалить</a></td>\n";
				echo "</table>";
				echo('<p></p>');
				}
				mysql_free_result($result);
	}

if ($del > 0)
	{
	$query = "UPDATE date_time_lag SET id_uchasnika = NULL WHERE id_uchasnika='".$del."'";
	/* Выполняем запрос. Если произойдет ошибка - вывести ее. */
	mysql_query($query) or die (mysql_error());
	$query = "UPDATE personal_info_lag SET hash = 'deleted' WHERE id_uchasnika='".$del."'";
	/* Выполняем запрос. Если произойдет ошибка - вывести ее. */
	mysql_query($query) or die (mysql_error());			
	echo("<script>location.href='".SITE_URL."admin.php'</script>");
	}
}
else
{
	
		echo "<form action=\"\" method=\"post\" name=\"master_key\">\n";
		echo"<div align=\"center\">Пожалуйста пройдите авторизацию ключем, для начала/продолжения работы с админ панелью</div>";
		echo('<p></p>');
		echo "<table class=\"fixed\" align=\"center\" border=\"2\" cellpadding=\"2\" cellspacing=\"2\">\n";
		echo "<td class=\"fixed_date_time\">Введите ключ авторизации: </td><td><input type=\"password\" name=\"master_key\" /></td><td class=\"fixed_delbtn\"><input align=\"center\" type=\"submit\" name=\"master_key_btn\" value=\"Авторизоваться\"/> </td>\n";
		echo "</table>";
		echo('<p></p>');	
}
}
else
{
		echo"<div align=\"center\">Доступ к данной странице запрещен! Если Вы уполномочены на работу с данной страницей, обратитесь за помощью к администратору.</div>";
exit();
}
}
else
{
echo('<p align=center>Сайт недоступен, ведутся технические работы. Просим прощения за доставленные неудобства.<p>');	
}
?>