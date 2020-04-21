<?php
session_start();

$_POST = json_decode(file_get_contents('php://input'), true); //die('> <pre>' . print_r($_POST) . '</pre>');

require "{$_SERVER[DOCUMENT_ROOT]}/_includes/options.inc";
require "{$_SERVER[DOCUMENT_ROOT]}/_includes/db.2.inc";

#< Customers
 #< Select of Loyalty
$request = 'SELECT * FROM `0_loyalty`';
$respond = mysqli_query($пксбд, $request) OR die($SQLError . mysqli_error($пксбд));
if(mysqli_num_rows($respond) > 0){
 while($aV = mysqli_fetch_assoc($respond)){
  $loyalty .= "<option value='{$aV['Index']}'>{$aV['Name']}</option>";
 }
}
 #> Select of Loyalty
 #
 #< Select of Status
$request = 'SELECT * FROM `0_status`';
$respond = mysqli_query($пксбд, $request) OR die($SQLError . mysqli_error($пксбд));
if(mysqli_num_rows($respond) > 0){
 while($aV = mysqli_fetch_assoc($respond)){
  $status .= "<option value='{$aV['Index']}'>{$aV['Name']}</option>";
 }
}
 #> Select of Status
 #
 #< Select of Type
$request = 'SELECT * FROM `0_type`';
$respond = mysqli_query($пксбд, $request) OR die($SQLError . mysqli_error($пксбд));
if(mysqli_num_rows($respond) > 0){
 while($aV = mysqli_fetch_assoc($respond)){
  $type .= "<option value='{$aV['Index']}'>{$aV['Name']}</option>";
 }
}
 #> Select of Type
#> Customers

#< Contact persons
 #< Select of Authority
$request = 'SELECT * FROM `0_authority`';
$respond = mysqli_query($пксбд, $request) OR die($SQLError . mysqli_error($пксбд));
if(mysqli_num_rows($respond) > 0){
 while($aV = mysqli_fetch_assoc($respond)){
  $authority .= "<option value='{$aV['Index']}'>{$aV['Name']}</option>";
 }
}
 #> Select of Authority
#> Contact persons
?>
<style>
Div#forms{
  background-color: white;
  display: flex; /*align-items: flex-start;*/
  position: relative;
  max-width: 96rem
}

Form{ overflow-y: auto }


Form Div{
  padding: 0 1rem;
  margin-bottom: 1rem
}

Div#commentaries{
  background-color: rgba(0, 60, 60, 0.08);
  padding: 1rem
}

Div#commentaries > Div{
  background-color: white;
  border: 1px solid black; border-bottom: none;
  margin-bottom: 0;
  overflow-y: auto; resize: vertical;
  padding: 0;
  width: 100%; /*height: 50px*/
}
Div#commentaries > Div > Div{
  font-size: 1.2rem;
  padding: 1rem
}
Div#commentaries > Div > Div:nth-of-type(odd){
  background-color: rgba(0, 0, 0, 0.04)
}
Div#commentaries > Div > Div > Strong{
 display: block;
 font-size: 1rem;
 margin-bottom: 0.4rem;
 text-transform: uppercase
}
Div#commentaries > Div > Div > Strong > I{
  font-weight: 400;
  margin-left: 0.4rem
}
Div#commentaries > Div > Div > P{  }

Div#commentaries > Textarea{ border-top: 1px dashed black }


Label{
  display: inline-block;
  font-family: sans-serif; font-size: 1rem;
  margin-bottom: 0.4rem;
  text-transform: uppercase
}


Form:nth-of-type(1),
Form:nth-of-type(2) > Div:first-of-type{ padding: 1rem 0 }

Form:nth-of-type(1){
  margin-right: 1px;
  width: calc(100% - 36rem)
}

Form:nth-of-type(2){ position: relative }
Form:nth-of-type(2) > Div:first-of-type{
  background-color: rgba(60, 60, 0, 0.08);
  position: sticky; top: 0
}
Form:nth-of-type(2) > Div:last-of-type{
  text-align: right;
  position: absolute; right: 0; bottom: 0; left: 0
}
Form:nth-of-type(2) Div.buttons{ display: flex; justify-content: space-between }

.incorrect{
  border-color: red;
  color: red
}
</style>

<div id='forms'>
 <form>
  <input type='hidden' name='h_IoC' id='h_IoC'>
  <div>
   <label for='t_name'>Наименование</label>
   <input name='t_name' id='t_name' required>
  </div>
  <div>
   <label for='t_city'>Город</label>
   <input name='t_city' id='t_city' required>
  </div>
  <div>
   <label for='t_inn'>ИНН</label>
   <input name='t_inn' id='t_inn' required>
  </div>
  <div>
   <label for='ta_sites'>Cайт(-ы)</label>
   <textarea name='ta_sites' id='ta_sites'></textarea>
  </div>
  <div>
   <label for='ta_products'>Продукт(-ы) [id через пробел]</label>
   <textarea name='ta_products' id='ta_products'></textarea>
  </div>
  <div>
   <label for='s_type'>Тип</label>
   <select name='s_type' id='s_type' required><option disabled selected>Выбор обязателен</option><?=$type?></select>
  </div>
  <div>
   <label for='t_specialization'>Специализация</label>
   <input name='t_specialization' id='t_specialization' value='<?=$specialization?>'>
  </div>
  <div>
   <label for='s_loyalty'>Лояльность</label>
   <select name='s_loyalty' id='s_loyalty' required><option disabled selected>Выбор обязателен</option><?=$loyalty?></select>
  </div>
  <div>
   <label for='s_status'>Статус</label>
   <select name='s_status' id='s_status' required><option disabled selected>Выбор обязателен</option><?=$status?></select>
  </div>
  <div id='commentaries'>
   <label for='ta_commentaries'>Комментарии (Сохранить > Alt + Enter)</label>
   <div></div>
   <textarea name='ta_commentaries' id='ta_commentaries'></textarea>
  </div>
 </form>

 <form>
  <div>
   <div class='buttons'>
    <button type='button' id='b_prev' disabled><</button>
    <button type='button' id='b_new'>+</button>
    <button type='button' onClick='updateCP()'>✓</button>
    <button type='button' id='b_next' disabled>></button>
   </div>

   <input type='hidden' name='h_IoCP' id='h_IoCP'>
   <div>
    <label for=''>Фамимлия</label>
    <input type='text' name='t_last' id='t_last' class='reset'>
   </div>
   <div>
    <label for=''>Имя</label>
    <input type='text' name='t_first' id='t_first' class='reset'>
   </div>
   <div>
    <label for=''>Отчество</label>
    <input type='text' name='t_patronymic' id='t_patronymic' class='reset'>
   </div>
   <div>
    <label for=''>Должность</label>
    <input type='text' name='t_position' id='t_position' class='reset'>
   </div>
   <div>
    <label for=''>Принятие решения</label>
    <select name='s_authority' id='s_authority' required><option disabled selected>Выбор обязателен</option><?=$authority?></select>
   </div>
   <div>
    <label for=''>Телефон(-ы)</label>
    <textarea name='ta_phones' id='ta_phones'></textarea>
   </div>
   <div>
    <label for=''>eMail(s) </label>
    <textarea name='ta_eMails' id='ta_eMails'></textarea>
   </div>
  </div>

  <div>
   <button type='button' onClick='updateC()'>Сохранить</button>
  </div>
 </form>
</div>