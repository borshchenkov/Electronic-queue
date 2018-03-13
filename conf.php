<?php
#Error_Reporting(E_ALL & ~E_NOTICE);
# настройки
define ('admin_key', 'W321w');
define ('admin_ip', '192.168.8.223');
define ('Enable', 'True');
define ('DB_HOST', 'localhost');
define ('SITE_URL', 'http://school34.k-ur.ru/test/');
//Константу SITE_URL заполнять по шаблону 'http://mysite.com/' обязательно.
define ('DB_LOGIN', 'school34');
define ('DB_PASSWORD', 'rHfy15');
define ('DB_NAME', 'school34');
mysql_connect(DB_HOST, DB_LOGIN, DB_PASSWORD) or die ("MySQL Error: " . mysql_error());
mysql_query("set names utf8") or die ("<br>Invalid query: " . mysql_error());
mysql_select_db(DB_NAME) or die ("<br>Invalid query: " . mysql_error());
# массив ошибок
$error[0] = ' Неправильно введен код с картинки (капча)';
$error[1] = ' Дата/время занято/не существует. Пожалуйста выберете <a href='.SITE_URL.'>другое</a>.';
$error[3] = ' Заполните поля Имя/Фамилия/Отчество ';
?>