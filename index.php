<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        .results, .results td, .results th {
            border-collapse: collapse;
            border: 1px solid #000;
        }
    </style>
</head>

<body style="background-color: #cccccc">
<h1>Автосалон</h1>
<?php
error_reporting(-1);
$connect = mysql_connect('localhost', 'root', '123456');
if (!$connect) {
    die ('Не удалось подключиться к базе данных: ' . mysql_error());
}
mysql_select_db('cars', $connect);
mysql_set_charset('utf8');

$list = Array();
if (isset($_POST['filter'])) {
    $model = $_POST['modelFilter'];
    $after = $_POST['dateAfterFilter'];
    $before = $_POST['dateBeforeFilter'];
    $engine = $_POST['engineFilter'];
    $minPrice = $_POST['minPriceFilter'];
    $maxPrice = $_POST['maxPriceFilter'];
    $query = '';
    if ($model && $after && $before && $engine > 0 && $minPrice && $maxPrice) {
        $query = "select c.*,et.gasoline_type,et.type,m.* from `cars` as c
                  join `entine_types` as et on c.engine_type_id = et.id
                  join `models` as m on c.model_id = m.id
                  where m.name like '%$model%'
                  ";
    } else {

    }
} else {
    if (isset($_POST['addModel'])) {
        $name = $_POST['model'];
        $country = $_POST['country'];
        $query = "insert into `models` (`name`,`country`) values ('$name','$country')";
        mysql_query($query);
    }
}

$modelQuery = "select * from `models`";
$resultModels = mysql_query($modelQuery, $connect);
if (!$resultModels) {
    die(mysql_error());
}
$models = array();
while ($row = mysql_fetch_array($resultModels)) {
    array_push($models, $row);
}
mysql_free_result($resultModels);

?>
<br/>

<div>
    Фильтр
    <form action="index.php" method="post">
        <br/>
        <label> Модель
            <input type="text" name='modelFilter'/>
        </label>
        <br/>
        <label> Дата производства
            <input type="text" name='dateAfterFilter'/> -
            <input type="text" name='dateBeforeFilter'/>
        </label>
        <br/>
        <label> Тип двигателя
            <select name='engineFilter'>
                <option value="0">Не определен</option>
                <option value="1">1</option>
            </select>
        </label>
        <br/>
        <label> Цена
            <input type="text" name='minPriceFilter'/> -
            <input type="text" name='maxPriceFilter'/>
        </label>
        <br/>

        <input name='filter' type="submit" value="Найти"/>
    </form>
</div>
Автомобили
<table class='results' cellspacing='0' cellpadding='0'>
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
        <td></td>
    </tr>
    </tbody>
</table>
<br/>

<div>
    Внести авто в базу
    <label> Номер двигателя
        <input name='engine_number' type='text'/>
    </label>
    <br/>
    <label> Тип двигателя
        <select name='engine_type'>

        </select>
    </label>
    <br/>
    <label> Модель
        <select name='model'>
            <?php
            foreach ($models as $model) {
                $id = $model['id'];
                $value = $model['name'] . '(' . $model['country'] . ')';
                echo("<option value='$id'>$value</option> ");
            }
            ?>
        </select>
    </label>
    <br/>
    <label> Цена
        <input name='price' type='text'/>рублей
    </label>
    <br/>
</div>
<div>
    Добавить марку авто
    <form action="index.php" method="post">
        <label> Марка
            <input name='model' type='text'/>
        </label>
        <label> Страна производства
            <input name='country' type='text'/>
        </label>
        <input name='addModel' type="submit" value="Добавить"/>
    </form>
</div>
</body>
</html>