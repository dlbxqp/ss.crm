<?php
session_start();

//< Замок
if(empty($_SESSION['3aмoк'])){
 unset($_SESSION['3aмoк']);
 header("Location: //{$_SERVER['HTTP_HOST']}/");
 exit();
}
//> Замок

$_POST = json_decode(file_get_contents('php://input'), true);

require "{$_SERVER['DOCUMENT_ROOT']}/_includes/options.inc";
require "{$_SERVER['DOCUMENT_ROOT']}/_includes/db.2.inc";
require "{$_SERVER['DOCUMENT_ROOT']}/_includes/validatingOfFields.inc";



$indexOfCustomer = $_POST['h_IoC'];
$name = validateText($_POST['t_name']);
$city = validateText($_POST['t_city']);
$inn = (int)$_POST['t_inn'];
$sites= validateAll($_POST['ta_sites']);
$specialization = validateText($_POST['t_specialization']);
//$products = validateAll($_POST['ta_products']);
$manufacturers = validateAll($_POST['t_manufacturers']); //implode('|', $_POST['t_manufacturers']);
$loyalty = (int)$_POST['s_loyalty'];
$status = (int)$_POST['s_status'];
$type = (int)$_POST['s_type'];
$manager = (int)$_POST['s_manager'];


#< New
if($indexOfCustomer == ''){
 $request = "SELECT `Index` FROM `customers` WHERE `Name` = '' ORDER BY `Index` DESC LIMIT 1";
 $respond = mysqli_query($пксбд, $request) OR die($SQLError . mysqli_error($пксбд));
 if(mysqli_num_rows($respond) < 1){
  $indexOfCustomer = base_convert(date('ymdHis'), 10, 16);
  $request = "INSERT INTO `customers` (`Index`, `Accessibility`, `Name`) VALUES ('{$indexOfCustomer}', 1, '')";
  mysqli_query($пксбд, $request) OR die($SQLError . mysqli_error($пксбд));
 } else{
  $aA = mysqli_fetch_array($respond);
  $indexOfCustomer = $aA[0];
  unset($aA);
 }
}
#> New


$request = <<<HD
UPDATE `customers`

SET
 `Name` = '{$name}',
 `City` = '{$city}',
 `INN` = {$inn},
 `Sites` = '{$sites}',
 `Specialization` = '{$specialization}',
 `Products` = '{$manufacturers}',
 `IoL` = '{$loyalty}',
 `IoS` = '{$status}',
 `IoT` = '{$type}',
 `IoU` = '{$manager}'

WHERE `Index` = '{$indexOfCustomer}'
HD;
mysqli_query($пксбд, $request) OR die($SQLError . mysqli_error($пксбд));



exit(json_encode($indexOfCustomer));