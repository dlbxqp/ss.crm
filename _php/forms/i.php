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
  position: relative
}

Form{
 overflow-y: auto;
 /*position: sticky; top: 0*/
}


Form Div{ padding: 0 1rem }
Form Div{ margin-bottom: 1rem }

Label{
  font-family: sans-serif; font-size: 1rem;
  text-transform: uppercase
}


Form:nth-of-type(1),
Form:nth-of-type(2) > Div:first-of-type{ padding: 1rem 0 }

Form:nth-of-type(2){ position: relative }
Form:nth-of-type(2) > Div:first-of-type{ background-color: rgba(60, 60, 0, 0.08) }
Form:nth-of-type(2) > Div:last-of-type{
  text-align: right;
  position: absolute; right: 0; bottom: 0; left: 0
}
Form:nth-of-type(2) Div.buttons{ display: flex; justify-content: space-between }
</style>

<div id='forms'>
 <form>
  <input type='hidden' name='h_IoC' id='h_IoC'>
  <div>
   <label for=''>Наименование</label>
   <input type='text' name='t_name' id='t_name' required>
  </div>
  <div>
   <label for=''>Город</label>
   <input type='text' name='t_city' id='t_city' required>
  </div>
  <div>
   <label for=''>ИНН</label>
   <input type='number' name='n_inn' id='n_inn' required>
  </div>
  <div>
   <label for=''>Cайт(-ы)</label>
   <textarea name='ta_sites' id='ta_sites'></textarea>
  </div>
  <div>
   <label for=''>Продукт(-ы) [id через пробел]</label>
   <textarea name='ta_products' id='ta_products'></textarea>
  </div>
  <div>
   <label for=''>Тип</label>
   <select name='s_type' id='s_type' required><option disabled selected>Выбор обязателен</option><?=$type?></select>
  </div>
  <div>
   <label for=''>Специализация</label>
   <input type='text' name='t_specialization' id='t_specialization' value='<?=$specialization?>'>
  </div>
  <div>
   <label for=''>Лояльность</label>
   <select name='s_loyalty' id='s_loyalty' required><option disabled selected>Выбор обязателен</option><?=$loyalty?></select>
  </div>
  <div>
   <label for=''>Статус</label>
   <select name='s_status' id='s_status' required><option disabled selected>Выбор обязателен</option><?=$status?></select>
  </div>
  <div>
   <label for=''>Комментарий</label>
   <textarea name='ta_comments' id='ta_comments'><?=$comments?></textarea>
  </div>
 </form>

 <form>
  <div>
   <div class='buttons'>
    <button type='button' id='b_prev' disabled><</button>
    <button type='button' id='b_new'>+</button>
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
   <button type='button' onClick='updateForms()'>Сохранить</button>
  </div>
 </form>
</div>