<?
$a_HTTPHost = explode('.', $_SERVER['HTTP_HOST']);
if(end($a_HTTPHost) == 'crm'){
 $пксбд = mysqli_connect( //Подключаемся к серверу баз данных
  'localhost', 'root', '', 'crm', 3306
 );
} else{
 $пксбд = mysqli_connect( //Подключаемся к серверу баз данных
  '127.0.0.1', '046822200_CRM', 'e6:JVy4wxU69', 'dlbxqp_crm', 3306
 );
}

if(mysqli_connect_errno()){
 printf("Ошибка подключения к БД: %s\n", mysqli_connect_error());
 exit();
}

if(!mysqli_set_charset($пксбд, 'UTF8')){ echo("Ошибка при загрузке набора символов UTF-8: %s\n" . mysqli_error($пксбд)); }