<?php
session_start();

if(stripos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === FALSE){
 header("Location: //{$_SERVER['HTTP_HOST']}/");
 exit();
}

require "{$_SERVER[DOCUMENT_ROOT]}/_includes/options.inc";
if(empty($_POST['l']) OR empty($_POST['p'])){
 ип('Alert', 'Проверте раскладку клавиатуры и не включена ли клавиша CapsLock, а затем повторите попытку');
} else{
 require "{$_SERVER[DOCUMENT_ROOT]}/_includes/db.1.inc";

 $p = md5($_POST['p']);
 $request = <<<HD
SELECT
 `Login`,
 `Accessibility`

FROM `users`
 
WHERE `Login` = '{$_POST['l']}'
AND `Password` = '{$p}'
 
ORDER BY 'Login' ASC LIMIT 1

HD;
 if($respond = mysqli_query($пксбд, $request)){
  if(mysqli_num_rows($respond) == 1){
   while($aV = mysqli_fetch_assoc($respond)){
    $l = $aV['Login'];
    $Д = $aV['Accessibility'] * 1;
   }

   if($Д != 1){ ип('Alert', 'Вам ограничен доступ. Свяжитесь с разработчиком'); }
   else{ $_SESSION['3aмoк'] = date('ymd') . '|' . $l; }
  } else{ ип('Alert', 'Пользователей с таким логином и паролем не найдено в системе'); }
 } else{ die(printf("Ошибка чтения из таблицы БД `users`: %s\r\n", mysqli_error($пксбд))); }

 mysqli_close($пксбд);
}

header("Location: //{$_SERVER['HTTP_HOST']}/");