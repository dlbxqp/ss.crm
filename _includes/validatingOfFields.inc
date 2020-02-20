<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/_fw/simpleHTMLDOM/simple_html_dom.php');


function validateAll($s){
 if(get_magic_quotes_gpc()){ $s = stripslashes($s); }
 mysqli_real_escape_string($GLOBALS['пксбд'], $s);

 $s = preg_replace("#[\r\n]{2,}#", "\r\n", $s);
 $s = str_replace("\t", ' ', $s);
 $s = preg_replace('#[ ]{2,}#', ' ', $s);

 return (strlen($s) > 0) ? trim($s) : NULL;
}


function validateText($s){
 //$s = htmlspecialchars($s);
 //$s = preg_replace('#[A-Za-z]{3,6}:\/\/#','',  $s); //Удаление протоколов
 //$s = str_replace('/', '-', $s);

 return validateAll($s);
}

function validateNumbers($s){
 $s = preg_replace('#[^\d\s]#ui','', $s);

 return $s;
}

function validateHTML($s){
 $мз = Array( //Массив замены
  '</p>' => '</p>\n',
  '<p>\n</p>' => ' ',
  '<p>&nbsp;</p>' => ' ',
  ' />' => '>',
  ' </' => '</',
  "'" => '"'
 );
 $s = strtr($s, $мз);

 return validateAll($s);
}