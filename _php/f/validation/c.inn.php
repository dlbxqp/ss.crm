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


$request = "SELECT `Index` FROM `customers` WHERE `INN` = '{$_POST['INN']}' AND `Index` != '{$_POST['IoC']}' ORDER BY `Index` DESC";
$respond = mysqli_query($пксбд, $request) OR die($SQLError . mysqli_error($пксбд));
if(mysqli_num_rows($respond) > 0){ die(FALSE); } else{ die(TRUE); }