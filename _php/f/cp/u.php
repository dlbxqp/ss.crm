<?php
session_start();

$_POST = json_decode(file_get_contents('php://input'), true);

//< Замок
if(empty($_SESSION['3aмoк'])){
 unset($_SESSION['3aмoк']);
 header("Location: //{$_SERVER['HTTP_HOST']}/");
 exit();
}
//> Замок

require "{$_SERVER['DOCUMENT_ROOT']}/_includes/options.inc";
require "{$_SERVER['DOCUMENT_ROOT']}/_includes/db.2.inc";
require "{$_SERVER['DOCUMENT_ROOT']}/_includes/validatingOfFields.inc";



$indexOfContactPerson = $_POST['h_IoCP'];
$indexOfCustomer = $_POST['h_IoC'];
$name = validateText($_POST['t_cp']);
$patronymic = validateText($_POST['t_patronymic']);
$position = validateText($_POST['t_position']);
$phones = /*validateNumbers(*/$_POST['ta_phones']/*)*/;
$eMails = validateText($_POST['ta_eMails']);
$cpDate = $_POST['d_cpDate'];
$authority = (int)$_POST['s_authority'];



#< New
if($indexOfContactPerson == ''){
 $request = "SELECT `Index` FROM `contact_persones` WHERE `IoC` = '{$indexOfCustomer}' AND `Name` = '' ORDER BY `Index` DESC LIMIT 1";
 $respond = mysqli_query($пксбд, $request) OR die($SQLError . mysqli_error($пксбд));
 if(mysqli_num_rows($respond) < 1){
  $indexOfContactPerson = base_convert(date('ymdHis'), 10, 16);
  $request = "INSERT INTO `contact_persones` (`Index`, `IoC`) VALUES ('{$indexOfContactPerson}', '{$indexOfCustomer}')";
  mysqli_query($пксбд, $request) OR die($SQLError . mysqli_error($пксбд));
 } else{ $indexOfContactPerson = $indexOfContactPerson[0] = mysqli_fetch_array($respond); }
}
#> New


$request = <<<HD
UPDATE `contact_persones`

SET
 `Name` = '{$name}',
 `Position` = '{$position}',
 `Phones` = '{$phones}',
 `eMails` = '{$eMails}',
 `cpDate` = '{$cpDate}',
 `IoA` = '{$authority}',
 `IoC` = '{$indexOfCustomer}'

WHERE `Index` = '{$indexOfContactPerson}'
HD;
mysqli_query($пксбд, $request) OR die($SQLError . mysqli_error($пксбд));



exit(json_encode($indexOfContactPerson));