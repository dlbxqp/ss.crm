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



$aA['Index'] = $_POST['IoC'];
$request = <<<HD
SELECT
 `Name`,
 `City`,
 `INN`,
 `Sites`,
 `Specialization`,
 `Comments`,
 `Products`,
 
 `IoL`,
 `IoS`,
 `IoT`
 
FROM `customers`

WHERE `Index` = '{$aA['Index']}'
/*AND `Accessibility` = 1*/
 
ORDER BY `Index` DESC LIMIT 1
HD;
$respond = mysqli_query($пксбд, $request) OR die($SQLError . mysqli_error($пксбд));
#
$aA = array_merge($aA, mysqli_fetch_assoc($respond));



die(json_encode($aA));