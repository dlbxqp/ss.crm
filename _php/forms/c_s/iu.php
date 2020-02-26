<?php
session_start();

//< Замок
if(empty($_SESSION['3aмoк'])){
 unset($_SESSION['3aмoк']);
 header("Location: //{$_SERVER['HTTP_HOST']}/");
 exit();
}
$мрз = explode('|', $_SESSION['3aмoк']); //Массив разбора замка
$_loginOfCurrentUser = $мрз[1];
//> Замок

$_POST = json_decode(file_get_contents('php://input'), true);

require "{$_SERVER['DOCUMENT_ROOT']}/_includes/options.inc";
require "{$_SERVER['DOCUMENT_ROOT']}/_includes/db.2.inc";
require "{$_SERVER['DOCUMENT_ROOT']}/_includes/validatingOfFields.inc";



#< Добавление
if(isset($_POST['Comment'])){
 $time = date('ymdHis');
 //< Проверка существования
 $request = "SELECT `Time` FROM `0_commentaries` WHERE `Time` = '{$time}'";
 $respond = mysqli_query($пксбд, $request) OR die($SQLError . mysqli_error($пксбд));
 if(mysqli_num_rows($respond) > 0){ (int)$time += rand(1, 4); }
 //> Проверка существования
 $request = "INSERT INTO `0_commentaries` (`Time`, `Content`, `LoA`, `IoC`) VALUES ('{$time}', '{$_POST['Comment']}', '{$_loginOfCurrentUser}', '{$_POST['IoC']}')";
 mysqli_query($пксбд, $request) OR die($SQLError . mysqli_error($пксбд));
}
#> Добавление

#< Получение
$aCommentaries = Array();
$request = "SELECT `Time`, `Content`, `LoA` FROM `0_commentaries` WHERE `IoC` = '{$_POST['IoC']}' ORDER BY `Time` DESC";
$respond = mysqli_query($пксбд, $request) OR die($SQLError . mysqli_error($пксбд));
while($aV = mysqli_fetch_assoc($respond)){
 $aCommentaries[] = Array($aV['Time'], $aV['Content'], $aV['LoA']);
}
#> Получение



die(json_encode($aCommentaries));