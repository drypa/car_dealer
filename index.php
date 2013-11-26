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
$query = "select c.*,et.type as engine_type,m.name as model,m.country from `cars` as c
                  join `engines` as et on c.engine_type_id = et.id
                  join `models` as m on c.model_id = m.id

                  ";
if (isset($_POST['filter'])) {
    $model = $_POST['modelFilter'];
    $after = $_POST['dateAfterFilter'];
    $before = $_POST['dateBeforeFilter'];
    $engine = $_POST['engineFilter'];
    $minPrice = $_POST['minPriceFilter'];
    $maxPrice = $_POST['maxPriceFilter'];

    if ($model || $after || $before || $engine > -1 || $minPrice || $maxPrice) {
        $query .= " where ";
        if($model){
             $query .= "m.name like '%$model%'";
        }
        if($after){
            if($model){
                $query .= " and ";
            }
            $query .= " c.created_date >='$after'";
        }
        if($before){
            $before = date("Y-m-d", strtotime($before));
            if($model || $after){
                $query .= " and ";
            }
            $query .= " c.created_date <='$before'";
        }
        if($engine>-1){
            if($model || $after || $after){
                $query .= " and ";
            }
            $query .= " et.id='$engine'";
        }
        if($minPrice){
            if($model || $after || $after || $engine>-1){
                $query .= " and ";
            }
            $query .= " c.price >= $minPrice";
        }
        if($maxPrice){
            if($model || $after || $after || $engine>-1 || $minPrice){
                $query .= " and ";
            }
            $query .= " c.price <= $maxPrice";
        }
    }

} else {
    if (isset($_POST['addModel'])) {
        $name = $_POST['model'];
        $country = $_POST['country'];
        $q = "insert into `models` (`name`,`country`) values ('$name','$country')";
        mysql_query($q);
    }else{
        if(isset($_POST['addGasoline'])){
            $name = $_POST['gasoline'];
            $q = "insert into `gasoline_types` (`name`) values ('$name')";
            mysql_query($q);
        }else{
            if(isset($_POST['addEngine'])){
                $name = $_POST['engine'];
                $q = "insert into `engines` (`type`)values('$name')";
                mysql_query($q);
                $id = mysql_insert_id();
                foreach ($_POST['gas'] as $key => $value) {
                    $q = "insert into `engine_gasoline` (`engines`,`gasoline_type`) values($id,$value)";
                    mysql_query($q);
                }
            }else{
                if(isset($_POST['addAuto'])){
                    $number = $_POST['engine_number'];
                    $engine_type = $_POST['engine_type'];
                    $model = $_POST['model'];
                    $price = $_POST['price'];
                    $color = $_POST['color'];
                    $created = $_POST['create_date'];
                    $q = "insert into `cars` (`engine_number`,`engine_type_id`,`model_id`,`price`,`created_date`,`color_id`)
                              values('$number',$engine_type,$model,'$price','$created',$color)";
                    mysql_query($q);
                }else{
                    if(isset($_POST['addColor'])){
                        $color = $_POST['color'];
                        $q = "insert into `colors` (`name`) values('$color')";
                        mysql_query($q);
                    }else{
                        if(isset($_POST['delColor'])){
                            $color = $_POST['color'];
                            $q = "delete from `colors` where `id`=$color";
                            mysql_query($q);
                        }
                    }
                }
            }
        }
    }
}
//Получаем список авто
$resultAuto = mysql_query($query, $connect);
if (!$resultAuto) {
    die(mysql_error());
}
$auto = array();
while ($row = mysql_fetch_array($resultAuto)) {
    array_push($auto, $row);
}
mysql_free_result($resultAuto);


//Получаем все марки
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


//Получаем типы двигателей
$engineQuery = "select * from `engines`";
$resultEngines = mysql_query($engineQuery, $connect);
if (!$resultEngines) {
    die(mysql_error());
}
$engines = array();
while ($row = mysql_fetch_array($resultEngines)) {
    array_push($engines, $row);
}
mysql_free_result($resultEngines);

//Получаем типы топлива
$gasolineQuery = "select * from `gasoline_types`";
$resultGas = mysql_query($gasolineQuery, $connect);
if (!$resultGas) {
    die(mysql_error());
}
$gasoline = array();
while ($row = mysql_fetch_array($resultGas)) {
    array_push($gasoline, $row);
}
mysql_free_result($resultGas);

//Получаем цвета
$colorsQuery = "select * from `colors`";
$resultColors = mysql_query($colorsQuery, $connect);
if (!$resultColors) {
    die(mysql_error());
}
$colors = array();
while ($row = mysql_fetch_array($resultColors)) {
    array_push($colors, $row);
}
mysql_free_result($resultColors);

?>
<br/>

<div>
    Фильтр <a href="index.php">Обновить</a>
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
                <option value="-1">Не определен</option>
                <?php
                foreach ($engines as $engine) {
                    $id = $engine['id'];
                    $value = $engine['type'];
                    echo("<option value='$id'>$value</option> ");
                }
                ?>
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
        <th>Номер двигателя</th>
        <th>Цена</th>
    </tr>
    </thead>
    <tbody>

    <?php
    foreach($auto as $a){
        $model = $a['model'];
        $engineNumber = $a['engine_number'];
        $created = $a['created_date'];
        $engineType = $a['engine_type'];
        $price = $a['price'];
        echo('<tr>');

        echo("<td>$model</td>");
        echo("<td>$created</td>");
        echo("<td>$engineType</td>");
        echo("<td>$engineNumber</td>");
        echo("<td>$price руб</td>");

        echo('</tr>');
    }
    ?>
    </tbody>
</table>
<br/>

<div>
    Внести авто в базу
    <br/>
    <form action="index.php" method="post">
    <label> Номер двигателя
        <input name='engine_number' type='text'/>
    </label>
    <br/>
    <label> Тип двигателя
        <select name='engine_type'>
            <?php
            foreach ($engines as $engine) {
                $id = $engine['id'];
                $value = $engine['type'];
                echo("<option value='$id'>$value</option> ");
            }
            ?>
        </select>
    </label>
    <br/>
    <label> Модель
        <select name='model'>
            <?php
            foreach ($models as $model) {
                $id = $model['id'];
                $value = $model['name'] . ' (' . $model['country'] . ')';
                echo("<option value='$id'>$value</option> ");
            }
            ?>
        </select>
    </label>
        <br />
    <label> Цвет
        <select name='color'>
            <?php
            foreach ($colors as $c) {
                $id = $c['id'];
                $value = $c['name'];
                echo("<option value='$id'>$value</option> ");
            }
            ?>
        </select>
    </label>
        <br/>
        <label> Дата производства
            <input name='create_date' type='text'/>
        </label>
    <br/>
    <label> Цена
        <input name='price' type='text'/>рублей
    </label>
        <input name='addAuto' type="submit" value="Добавить"/>
    <br/>
    </form>
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
<div>
    Добавить тип топлива
    <form action="index.php" method="post">
        <label> Тип топлива
            <input name='gasoline' type='text'/>
        </label>
        <input name='addGasoline' type="submit" value="Добавить"/>
    </form>
</div>
<div>
    Добавить тип двигателя
    <form action="index.php" method="post">
        <label> Тип двигателя
            <input name='engine' type='text'/>
        </label>
        <label> Топливо
            <?php
            foreach($gasoline as $gas){
                $name=$gas['name'];
                $id=$gas['id'];
                echo "<label><input type='checkbox' name='gas[]' value='$id'/>$name</label>";
            }
            ?>
        </label>
        <input name='addEngine' type="submit" value="Добавить"/>
    </form>
</div>
<div style="border: 1px solid #000">
    Цвета кузова
    <form action="index.php" method="post">
        <label> Цвет кузова
            <input name='color' type='text'/>
        </label>
        <input name='addColor' type="submit" value="Добавить"/>
    </form>

    <form action="index.php" method="post">
        <label> Цвет кузова
            <select name='color'>
                <?php
                foreach ($colors as $c) {
                    $id = $c['id'];
                    $value = $c['name'];
                    echo("<option value='$id'>$value</option> ");
                }
                ?>
            </select>
        </label>
        <input name='delColor' type="submit" value="Удалить"/>
    </form>
</div>
</body>
</html>