<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>

<body style="background-color: #cccccc">
<h1>Автосалон</h1>
<?php
error_reporting(-1);
$connect= mysql_connect('localhost','root','123456');
if(!$connect){
    die ('Не удалось подключиться к базе данных: ' .mysql_error());
}
mysql_select_db('cars',$connect);
mysql_set_charset('utf8');



?>
<br />
<div>
    Фильтр
    <br />
    <label> Модель
        <input type="text" name='modelFilter'/>
    </label>
    <br />
    <label> Дата производства
        <input type="text" name='dateAfterFilter' /> -
        <input type="text" name='dateBeforeFilter' />
    </label>
    <br />
    <label> Тип двигателя
        <select name='engineFilter' >
            <option value="0">Не определен</option>
        </select>
    </label>
    <br />
    <label> Цена
        <input type="text" name='minPriceFilter' /> -
        <input type="text" name='maxPriceFilter' />
    </label>
</div>
Автомобили
<table style="border: 1px solid #000000">
    <thead>
    <tr>
        <th>Модель</th>
        <th>Дата производства</th>
        <th>Тип двигателя</th>
        <th>Используемое топливо</th>
        <th>Номер двигателя</th>
        <th>Цена</th>
        <th>Купить</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    </tbody>
</table>
</body>
</html>