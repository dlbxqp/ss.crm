<?php
mb_internal_encoding('UTF-8');


#< Security
function проверкаНаВходе($a){ //die('<pre>' . print_r($a, TRUE) . '</pre>');
 foreach($a AS $v){
  $v = htmlspecialchars( stripslashes( trim( rawurldecode($v) ) ) );
 }

 return $v;
}
проверкаНаВходе($_GET);
проверкаНаВходе($_POST);
#> Security

#< Cookies
function ип($нп, $сп){ //Испечь печеньку (название печеньки, содержание печеньки)
 if(isset($нп) AND isset($сп)){ setcookie($нп, $сп, time() + 10, '/'); }
}
#> Cookies


$_NoH = '//' . $_SERVER['HTTP_HOST']; //Название Host-а

$SQLError = 'Ошибка в запросе: ';