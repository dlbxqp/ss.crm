<?php
session_start();

$_POST = json_decode(file_get_contents('php://input'), true); //die('> <pre>' . print_r($_POST) . '</pre>');

require "{$_SERVER[DOCUMENT_ROOT]}/_includes/options.inc";
require "{$_SERVER[DOCUMENT_ROOT]}/_includes/db.1.inc";



$request = 'SELECT `Name` FROM `manufacturers` ORDER BY `Name` ASC';
$respond = mysqli_query($пксбд, $request) OR die($SQLError . mysqli_error($пксбд));
if(mysqli_num_rows($respond) > 0){
 while($aV = mysqli_fetch_assoc($respond)){
  $manufacturers .= "<option value='{$aV['Name']}'>{$aV['Name']}</option>";
 }
}



die(json_encode($manufacturers));