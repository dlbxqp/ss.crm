<?php
session_start();

$_POST = json_decode(file_get_contents('php://input'), true);

//< Замок
if(empty($_SESSION['3aмoк'])){
 unset($_SESSION['3aмoк']);
 header("Location: //{$_SERVER['HTTP_HOST']}/");
 exit();
}
$мрз = explode('|', $_SESSION['3aмoк']); //Массив разбора замка
$_groupOfCurrentUser = (int)$мрз[2];
//> Замок

require "{$_SERVER['DOCUMENT_ROOT']}/_includes/options.inc";
require "{$_SERVER['DOCUMENT_ROOT']}/_includes/db.2.inc";



if(isset($_POST['IoCP'])){
 $request = "SELECT `IoC` FROM `contact_persones` WHERE `Index` = '{$_POST['IoCP']}' ORDER BY `Index` DESC LIMIT 1";
 $respond = mysqli_query($пксбд, $request) OR die($SQLError . mysqli_error($пксбд));
 $aA = mysqli_fetch_assoc($respond);
 $_POST['IoC'] = $aA['IoC'];
}
$request = <<<HD
SELECT
 `Index`,
 `Last`,
 `First`,
 `Patronymic`,
 `Position`,
 `Phones`,
 `eMails`,
 `IoA`

FROM `contact_persones`

WHERE `IoC` = '{$_POST['IoC']}'

ORDER BY `Index` ASC
HD;
$respond = mysqli_query($пксбд, $request) OR die($SQLError . mysqli_error($пксбд));
if(mysqli_num_rows($respond) === 1){
 $aA = mysqli_fetch_assoc($respond);
 $aA['IoCPs'] = $aA['Index'];
} else{
 while($aV = mysqli_fetch_assoc($respond)){
  if(!isset($_POST['IoCP']) OR $aV['Index'] == $_POST['IoCP']){ $aA = $aV; }
  $B .= $aV['Index'] . ' ';
 }
 $aA['IoCPs'] = trim($B);
}
$aA['Group of current user'] = $_groupOfCurrentUser;



die(json_encode($aA));