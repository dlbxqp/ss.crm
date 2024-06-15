<?php
session_start();

//< Замок
if(empty($_SESSION['3aмoк'])){
 unset($_SESSION['3aмoк']);
 header("Location: //{$_SERVER['HTTP_HOST']}/");
 exit();
}
//> Замок

require "{$_SERVER['DOCUMENT_ROOT']}/_includes/options.inc";
require "{$_SERVER['DOCUMENT_ROOT']}/_includes/db.2.inc";


function getContactPersones($IoC){
    $result = '';
    $request = <<<HD
SELECT
 `Name`,
 `cpDate`

FROM `contact_persones`

WHERE `IoC` = '{$IoC}'
HD;
    $respond = mysqli_query($GLOBALS['пксбд'], $request) or die($GLOBALS['SQLError'] . mysqli_error($GLOBALS['пксбд']));
    while($aV = mysqli_fetch_assoc($respond)){
        $result .= '<div data-date="' . $aV['cpDate'] . '">' . $aV['Name'] . "</div>\r\n";
    }

    return $result;
}


$request = <<<HD
SELECT
 `customers`.`Index`,
 LEFT(`0_loyalty`.`Name`, 1) AS `Л`,
 `customers`.`Name` AS `Наименование организации`,
 `customers`.`City` AS `Город`,
/*
 `customers`.`INN` AS `ИНН`,
 `customers`.`Sites` AS `Сайт (-ы)`,
*/
 `customers`.`Specialization` AS `Направление деятельности`,
/*
 `customers`.`Comments` AS ``,
 `customers`.`Products` AS ``,
*/
 `customers`.`Index` AS `Контактное лицо`,
 `0_status`.`Name` AS `Статус`,
 `0_type`.`Name` AS `Тип`

FROM `customers`
LEFT JOIN `0_loyalty` ON `0_loyalty`.`Index` = `customers`.`IoL`
LEFT JOIN `0_status` ON `0_status`.`Index` = `customers`.`IoS`
LEFT JOIN `0_type` ON `0_type`.`Index` = `customers`.`IoT`

WHERE `customers`.`Accessibility` = 1

ORDER BY `customers`.`Index` DESC
HD;
$respond = mysqli_query($GLOBALS['пксбд'], $request) OR die($GLOBALS['SQLError'] . mysqli_error($GLOBALS['пксбд']));
while($aV = mysqli_fetch_assoc($respond)){
 foreach($aV AS $k => $v){
  (!isset($tr['thead'])) && ($th .= "<th>{$k}</th>");
  ($k == 'Контактное лицо') && ($v = getContactPersones($v));
  $td .= '<td>' . $v . '</td>';
 }
 (!isset($tr['thead'])) && ($tr['thead'] .= "<tr>$th</tr>"); unset($th);
 $tr['tbody'] .= "<tr>$td</tr>"; unset($td);
}
$thead = $tr['thead'];
$tbody = $tr['tbody'];
unset($tr)
?>



<style>
Table Tr > Td{ border: none; border-bottom: 1px dotted white }
Table Tr:hover > Td{ border-bottom-color: black }

Table Tr > *:nth-child(1),
Table Tr > *:nth-child(2){ display: none }

Table Td > Div:not(:last-of-type){
    margin-bottom: 8px;
}


Div#add{
 background-color: rgb(60, 60, 60);
 border-radius: 1rem 0 0 0;
 color: white;
 font-size: 2rem; font-weight: bolder;
 line-height: 1;
 opacity: 0.2;
 padding: 0.6rem 1rem;
 position: fixed; right: 1rem; bottom: 1rem
}
Div#add:hover{ opacity: 1 }
</style>

<table>
 <thead><?=$thead?></thead>
 <tbody><?=$tbody?></tbody>
</table>
<div id='add' onClick='addCustomer()'>+</div>
