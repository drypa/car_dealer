<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        .results, .results td, .results th {
            border-collapse: collapse;
            border: 1px solid #000;
        }

        .with-border {
            border: 1px solid #000;

        }
    </style>
</head>

<body style="background-color: #cccccc">
<h1>Автосалон</h1>
<?php
error_reporting(1);
$connect = mysql_connect('localhost', 'root', '123456');
if (!$connect) {
    die ('Не удалось подключиться к базе данных: ' . mysql_error());
}
mysql_select_db('cars', $connect);
mysql_set_charset('utf8');

$list = Array();
$query = "select c.*,et.type as engine_type,m.name as model,m.country, col.name as color from `cars` as c
                  join `engines` as et on c.engine_type_id = et.id
                  join `models` as m on c.model_id = m.id
                  join `colors` as col on c.color_id = col.id
                  where `byer` is null
                  ";
if (isset($_POST['filter'])) {
    $model = $_POST['modelFilter'];
    $after = $_POST['dateAfterFilter'];
    $before = $_POST['dateBeforeFilter'];
    $engine = $_POST['engineFilter'];
    $minPrice = $_POST['minPriceFilter'];
    $maxPrice = $_POST['maxPriceFilter'];

    if ($model) {
        $query .= " and m.name like '%$model%'";
    }
    if ($after) {
        $after = date("Y-m-d", strtotime($after));
        $query .= " and c.created_date >='$after'";
    }
    if ($before) {
        $before = date("Y-m-d", strtotime($before));
        $query .= " and c.created_date <='$before'";
    }
    if ($engine > -1) {
        $query .= " and et.id='$engine'";
    }
    if ($minPrice) {
        $query .= " and c.price >= $minPrice";
    }
    if ($maxPrice) {
        $query .= " and c.price <= $maxPrice";
    }

} else {
    if (isset($_POST['addModel'])) {
        $name = $_POST['model'];
        $country = $_POST['country'];
        $q = "insert into `models` (`name`,`country`) values ('$name','$country')";
        mysql_query($q);
    }
    if (isset($_POST['addEngine'])) {
        $name = $_POST['engine'];
        $q = "insert into `engines` (`type`)values('$name')";
        mysql_query($q);
    }
    if (isset($_POST['addAuto'])) {
        $number = $_POST['engine_number'];
        $engine_type = $_POST['engine_type'];
        $model = $_POST['model'];
        $price = $_POST['price'];
        $color = $_POST['color'];
        $created = date("Y-m-d", strtotime($_POST['create_date']));
        $q = "insert into `cars` (`engine_number`,`engine_type_id`,`model_id`,`price`,`created_date`,`color_id`)
                              values('$number',$engine_type,$model,'$price','$created',$color)";
        mysql_query($q);
    }
    if (isset($_POST['addColor'])) {
        $color = $_POST['color'];
        $q = "insert into `colors` (`name`) values('$color')";
        mysql_query($q);
    }
    if (isset($_POST['delColor'])) {
        $color = $_POST['color'];
        $q = "delete from `colors` where `id`=$color";
        mysql_query($q);
    }
    if (isset($_POST['addByer'])) {
        $surname = $_POST['surname'];
        $name = $_POST['name'];
        $middle_name = $_POST['middle_name'];
        $driver_license = $_POST['driver_license'];
        $q = "INSERT INTO `buyers` (`driver_license`, `name`, `surname`, `middlename`)
                                         VALUES ('$driver_license','$name','$surname','$middle_name')";
        mysql_query($q);
    }
    if (isset($_POST['delByer'])) {
        $byer = $_POST['byer'];
        $q = "delete from `buyers` where `driver_license`='$byer'";
        mysql_query($q);
    }
    if (isset($_POST['delEngine'])) {
        $engine = $_POST['engine'];
        $q = "delete from `engines` where `id`='$engine'";
        mysql_query($q);
    }
    if (isset($_POST['delModel'])) {
        $model = $_POST['model'];
        $q = "delete from `models` where `id`=$model";
        mysql_query($q);
    }
    if (isset($_POST['buyCar'])) {
        $buyer = $_POST['buyer'];
        $car = $_POST['auto'];
        $q = "update `cars` set `byer`='$buyer' where `engine_number`='$car'";
        mysql_query($q);
    }
    if (isset($_POST['returnAuto'])) {
        $car = $_POST['auto'];
        $q = "update `cars` set `byer`=NULL where `engine_number`='$car'";
        mysql_query($q);
    }
    if(isset($_POST['editModel'])){
        $id = $_POST['id'];
        $name = $_POST['name'];
        $country= $_POST['country'];
        $q = "update `models` set `name` = '$name',`country`='$country' where `id`=$id ";
        mysql_query($q);
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

//получаем покупателей
$buyersQuery = "select * from `buyers`";
$resultBuyers = mysql_query($buyersQuery, $connect);
if (!$resultColors) {
    die(mysql_error());
}
$buyers = array();
while ($row = mysql_fetch_array($resultBuyers)) {
    array_push($buyers, $row);
}
mysql_free_result($resultBuyers);

//получаем все авто из базы
$allAutoQuery = "select c.*,et.type as engine_type,m.name as model,m.country,
                  col.name as color,
                  b.name as buyer_name,
                  b.surname as buyer_surname,
                  b.middlename as buyer_middlename,
                  b.driver_license as buyer_driver_license
                  from `cars` as c
                  join `engines` as et on c.engine_type_id = et.id
                  join `models` as m on c.model_id = m.id
                  join `colors` as col on c.color_id = col.id
                  left join `buyers` as b on b.driver_license = c.byer";

$resultAllAuto = mysql_query($allAutoQuery, $connect);
if (!$resultAllAuto) {
    die(mysql_error());
}
$allAuto = array();
while ($row = mysql_fetch_array($resultAllAuto)) {
    array_push($allAuto, $row);
}
mysql_free_result($resultAllAuto);

//получаем проданные авто
$buyedAutoQuery = "select c.*,et.type as engine_type,m.name as model,m.country,
                  col.name as color
                  from `cars` as c
                  join `engines` as et on c.engine_type_id = et.id
                  join `models` as m on c.model_id = m.id
                  join `colors` as col on c.color_id = col.id
                  where c.byer is not null
                  ";

$resultByedAuto = mysql_query($buyedAutoQuery, $connect);
if (!$resultByedAuto) {
    die(mysql_error());
}
$buyedAuto = array();
while ($row = mysql_fetch_array($resultByedAuto)) {
    array_push($buyedAuto, $row);
}
mysql_free_result($resultByedAuto);

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
Автомобили на продажу
<table class='results' cellspacing='0' cellpadding='0'>
    <thead>
    <tr>
        <th>Модель</th>
        <th>Дата производства</th>
        <th>Цвет</th>
        <th>Тип двигателя</th>
        <th>Номер двигателя</th>
        <th>Цена</th>
    </tr>
    </thead>
    <tbody>

    <?php
    foreach ($auto as $a) {
        $model = $a['model'];
        $engineNumber = $a['engine_number'];
        $created = $a['created_date'];
        $engineType = $a['engine_type'];
        $price = $a['price'];
        $color = $a['color'];
        echo('<tr>');

        echo("<td>$model</td>");
        echo("<td>$created</td>");
        echo("<td>$color</td>");
        echo("<td>$engineType</td>");
        echo("<td>$engineNumber</td>");
        echo("<td>$price руб</td>");

        echo('</tr>');
    }
    ?>
    </tbody>
</table>
<br/>
<table>
    <tr>
        <td><b>Внести авто в базу</b></td>
        <td><b>Продать авто</b></td>
    </tr>
    <tr>
        <td>
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
                <br/>
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
        </td>
        <td>
            <form action="index.php" method="post">
                <label> Выбрать автомобиль для продажи
                    <select name="auto">
                        <?php
                        foreach ($auto as $a) {
                            $model = $a['model'];
                            $engineNumber = $a['engine_number'];
                            $created = $a['created_date'];
                            $engineType = $a['engine_type'];
                            $color = $a['color'];
                            $price = $a['price'];
                            $value = "$color $model($engineType); выпуска:$created; цена:$price";
                            echo("<option value='$engineNumber'>$value</option>");
                        }
                        ?>
                    </select>
                </label>
                <br/>
                <label>Покупатель
                    <select name='buyer'>
                        <?php
                        foreach ($buyers as $b) {
                            $id = $b['driver_license'];
                            $name = $b['name'];
                            $surname = $b['surname'];
                            $middlename = $b['middlename'];
                            $value = "$surname $name $middlename($id)";
                            echo("<option value='$id'>$value</option> ");
                        }
                        ?>
                    </select>
                </label>
                <br/>
                <input type="submit" name='buyCar' value="Продать"/>
            </form>
        </td>
    </tr>
</table>
<br/>

<div class='with-border'>
    Марка авто
    <form action="index.php" method="post">
        <label> Марка
            <input name='model' type='text'/>
        </label>
        <label> Страна производства
            <input name='country' type='text'/>
        </label>
        <input name='addModel' type="submit" value="Добавить"/>
    </form>
    <form action="index.php" method="post">
        <label> Марка
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
        <input name='delModel' type="submit" value="Удалить"/>
    </form>
    <?php
        foreach ($models as $model) {
            $id = $model['id'];
            $name = $model['name'];
            $country= $model['country'];
            echo("<form action='index.php' method='post'>");
            echo("<input type='hidden' name='id' value='$id'>");
            echo("<input type='text' name='name' value='$name'>");
            echo("<input type='text' name='country' value='$country'>");
            echo("<input type='submit' name='editModel' value='Сохранить' /></form>");
        }
    ?>
</div>
<br/>

<div class='with-border'>
    Тип двигателя
    <form action="index.php" method="post">
        <label> Тип двигателя
            <input name='engine' type='text'/>
        </label>
        <input name='addEngine' type="submit" value="Добавить"/>
    </form>
    <form action="index.php" method="post">
        <label> Тип двигателя
            <select name='engine'>
                <?php
                foreach ($engines as $engine) {
                    $id = $engine['id'];
                    $value = $engine['type'];
                    echo("<option value='$id'>$value</option> ");
                }
                ?>
            </select>
        </label>
        <input name='delEngine' type="submit" value="Удалить"/>
    </form>
</div>
<br/>

<div class='with-border'>
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
    <div></div>
</div>
<br/>

<div class='with-border'>
    Покупатели
    <form action="index.php" method="post">
        <label>Фамилия
            <input name='surname' type='text'/>
        </label>
        <label>Имя
            <input name='name' type='text'/>
        </label>
        <label>Отчество
            <input name='middle_name' type='text'/>
        </label>
        <label>Номер водительского удостоверения
            <input name='driver_license' type='text'/>
        </label>

        <input name='addByer' type="submit" value="Добавить"/>
    </form>
    <form action="index.php" method="post">
        <label> Покупатели
            <select name='byer'>
                <?php
                foreach ($buyers as $b) {
                    $id = $b['driver_license'];
                    $name = $b['name'];
                    $surname = $b['surname'];
                    $middlename = $b['middlename'];
                    $value = "$surname $name $middlename($id)";
                    echo("<option value='$id'>$value</option> ");
                }
                ?>
            </select>
        </label>
        <input name='delByer' type="submit" value="Удалить"/>
    </form>

</div>
<br/>

<div class='with-border'>
    Все автомобили
    <table class='results' cellspacing='0' cellpadding='0'>
        <thead>
        <tr>
            <th>Модель</th>
            <th>Дата производства</th>
            <th>Цвет</th>
            <th>Тип двигателя</th>
            <th>Номер двигателя</th>
            <th>Цена</th>
            <th>Покупатель</th>
        </tr>
        </thead>
        <tbody>

        <?php
        foreach ($allAuto as $a) {
            $model = $a['model'];
            $engineNumber = $a['engine_number'];
            $created = $a['created_date'];
            $engineType = $a['engine_type'];
            $price = $a['price'];
            $color = $a['color'];

            $name = $a['buyer_name'];
            $sur = $a['buyer_surname'];
            $mid = $a['buyer_middlename'];
            $license = $a['buyer_driver_license'];
            $fullName = $license ? "$sur $name $mid($license)" : 'Ещё не продана';
            echo('<tr>');


            echo("<td>$model</td>");
            echo("<td>$created</td>");
            echo("<td>$color</td>");
            echo("<td>$engineType</td>");
            echo("<td>$engineNumber</td>");
            echo("<td>$price руб</td>");
            echo("<td>$fullName</td>");

            echo('</tr>');
        }
        ?>
        </tbody>
    </table>
    <br/>

    <div>
        <form action="index.php" method="post">
            <label>Вернуть авто
                <select name='auto'>
                    <?php
                    foreach ($buyedAuto as $a) {
                        $model = $a['model'];
                        $engineNumber = $a['engine_number'];
                        $created = $a['created_date'];
                        $engineType = $a['engine_type'];
                        $color = $a['color'];
                        $price = $a['price'];
                        $value = "$color $model($engineType); выпуска:$created; цена:$price";
                        echo("<option value='$engineNumber'>$value</option>");
                    }
                    ?>
                </select>
                <input type="submit" name='returnAuto' value="Вернуть"/>
            </label>
        </form>
    </div>
</div>
</body>
</html>