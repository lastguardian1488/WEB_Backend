<?php

// обращение к базе данных
// повторяется очень часто -> вынесено в отдельный файл
$user_db = 'u47500';
$pass_db = '7787869';
$db = new PDO('mysql:host=localhost;dbname=u47500', $user_db, $pass_db, array(PDO::ATTR_PERSISTENT => true));

?>