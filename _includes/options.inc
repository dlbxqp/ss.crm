<?php
mb_internal_encoding('UTF-8');


#< Security
function f($a){ //die('<pre>' . print_r($a, TRUE) . '</pre>');
 foreach($a AS $k => $v){
  $a[$k] = //htmlspecialchars(
   stripslashes(
    trim(
     rawurldecode(
      $v
     )
    )
   //)
  );
 }

 return $a;
}
$_GET = f($_GET);
$_POST = f($_POST);
#> Security

#< Cookies
function ип($нп, $сп){ //Испечь печеньку (название печеньки, содержание печеньки)
 if(isset($нп) AND isset($сп)){ setcookie($нп, $сп, time() + 10, '/'); }
}
#> Cookies


$_NoH = '//' . $_SERVER['HTTP_HOST']; //Название Host-а

$GLOBALS['SQLError'] = 'Ошибка в запросе: ';
