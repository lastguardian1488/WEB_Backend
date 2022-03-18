<?php
header('Content-Type: text/html; charset=UTF-8');
// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  // В суперглобальном массиве $_GET PHP хранит все параметры, переданные в текущем запросе через URL.
  if (!empty($_GET['save'])) {
    // Если есть параметр save, то выводим сообщение пользователю.
    print('Спасибо, результаты сохранены.');
  }
  
  // Включаем содержимое файла main.php
  include('./php/main.php');
  // Завершаем работу скрипта.
  exit();
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.

// Проверяем ошибки.
$errors = FALSE;
if (empty($_POST['field-name'])) {
  print('Заполните имя.<br/>');
  $errors = TRUE;
}

if (!preg_match("/@/",$_POST['field-email'])) {
  print('Заполните email.<br/>');
  $errors = TRUE;
}

if ($_POST['field-date-birth'] == "default") {
  print('Выберите год рождения.<br/>');
  $errors = TRUE;
}

if (empty($_POST['sex'])) {
  print('Выберите пол.<br/>');
  $errors = TRUE;
}

if (empty($_POST['NumberOfLimbs'])) {
  print('Выберите количество конечностей.<br/>');
  $errors = TRUE;
}

if (empty($_POST['SuperAbilities'])) {
  print('Выберите суперспособности.<br/>');
  $errors = TRUE;
}

if (empty($_POST['checkbox'])) {
  print('Поставьте галочку.<br/>');
  $errors = TRUE;
}

if ($errors) {
  // При наличии ошибок завершаем работу скрипта.
  exit();
}

// Сохранение в базу данных.

$user = 'u47500';
$pass = '7787869';
$db = new PDO('mysql:host=localhost;dbname=u47500', $user, $pass, array(PDO::ATTR_PERSISTENT => true));

// // Подготовленный запрос. Не именованные метки.
// try {
//   $stmt = $db->prepare("INSERT INTO application SET name = ?");
//   $stmt -> execute(array('fio'));
// }
// catch(PDOException $e){
//   print('Error : ' . $e->getMessage());
//   exit();
// }

//  stmt - это "дескриптор состояния".
 
//  Именованные метки.
//$stmt = $db->prepare("INSERT INTO test VALUES (:label,:color)");
//$stmt -> execute(array('label'=>'perfect', 'color'=>'green'));
 
//Еще вариант
$stmt1 = $db->prepare("INSERT INTO person_form VALUES (NULL,:namefield, :email, :birth_year, :sex, :num_of_limbs, :biography);");
$stmt1->bindParam(':namefield', $namefield);
$stmt1->bindParam(':email', $email);
$stmt1->bindParam(':birth_year', $birth_year);
$stmt1->bindParam(':sex', $sex);
$stmt1->bindParam(':num_of_limbs', $num_of_limbs);
$stmt1->bindParam(':biography', $biography);
$namefield = $_POST['field-name'];
$email = $_POST['field-email'];
$birth_year = (int)$_POST['field-date-birth'];
$sex = $_POST['sex'];
$num_of_limbs = (int)$_POST['NumberOfLimbs'];
$biography = $_POST['field-biography'];
$stmt1->execute();

//!здесь еще можно написать проверку и вставку в таблицу superabillities
//(если в ней есть все суперспособности из main.php, то хорошо, иначе вставить недостающие суперспособности)

//вставка в таблицу person_superabillities
//получение последнего айди
$res = $db->query("SELECT max(id) FROM person_form");
$row = $res->fetch();
$count = (int) $row[0];

$superabillities = $_POST['SuperAbilities'];

foreach($superabillities as $superabillity_name) {
  $stmt2 = $db->prepare("INSERT INTO person_form_superabillities VALUES (:person_id, :superabillity_name);");
  $stmt2->bindParam(':person_id', $count);
  $stmt2->bindParam(':superabillity_name', $superabillity_name);
  $stmt2 -> execute();
}

// Делаем перенаправление.
// Если запись не сохраняется, но ошибок не видно, то можно закомментировать эту строку чтобы увидеть ошибку.
// Если ошибок при этом не видно, то необходимо настроить параметр display_errors для PHP.
header('Location: ?save=1');
?>