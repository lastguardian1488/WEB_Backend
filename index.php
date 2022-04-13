<?php
header('Content-Type: text/html; charset=UTF-8');
// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  
  $messages = array();
  
  if (!empty($_COOKIE['save'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('save', '', 100000);
    // Если есть параметр save, то выводим сообщение пользователю.
    $messages['save'] = '<div class="save">Спасибо, результаты сохранены.</div>';
  }

  // Складываем признак ошибок в массив.
  $errors = array();
  $errors['field-name'] = !empty($_COOKIE['field-name-error']);
  $errors['field-email'] = !empty($_COOKIE['field-email-error']);
  $errors['field-date-birth'] = !empty($_COOKIE['field-date-birth-error']);
  $errors['sex'] = !empty($_COOKIE['sex-error']);
  $errors['num-of-limbs'] = !empty($_COOKIE['num-of-limbs-error']);
  $errors['super-abillities'] = !empty($_COOKIE['super-abillities-error']);
  $errors['checkbox'] = !empty($_COOKIE['checkbox-error']);
  
  // Выдаем сообщения об ошибках.
  if ($errors['field-name']) {
    setcookie('field-name-error', '', 100000);  // Удаляем куку, указывая время устаревания в прошлом.
    $messages['field-name'] = '<div class="error name-error">Заполните имя.</div>';  // Выводим сообщение.
  }
  if ($errors['field-email']) {
    setcookie('field-email-error', '', 100000);
    $messages['field-email'] = '<div class="error email-error">Заполните email.</div>';
  }
  if ($errors['field-date-birth']) {
    setcookie('field-date-birth-error', '', 100000);
    $messages['field-date-birth'] = '<div class="error date-birth-error">Выберите год рождения.</div>';
  }
  if ($errors['sex']) {
    setcookie('sex-error', '', 100000);
    $messages['sex'] = '<div class="error sex-error">Выберите пол.</div>';
  }
  if ($errors['num-of-limbs']) {
    setcookie('num-of-limbs-error', '', 100000);
    $messages['num-of-limbs'] = '<div class="error num-of-limbs-error">Выберите количество конечностей.</div>';
  }
  if ($errors['super-abillities']) {
    setcookie('super-abillities-error', '', 100000);
    $messages['super-abillities'] = '<div class="error super-abillities-error">Выберите суперспособности.</div>';
  }
  if ($errors['checkbox']) {
    setcookie('checkbox-error', '', 100000);
    $messages['checkbox'] = '<div class="error checkbox-error">Заполните чекбокс.</div>';
  }

  // Складываем предыдущие значения полей в массив, если есть.
  $values = array();
  $values['field-name'] = empty($_COOKIE['field-name-value']) ? '' : $_COOKIE['field-name-value'];
  $values['field-email'] = empty($_COOKIE['field-email-value']) ? '' : $_COOKIE['field-email-value'];
  $values['field-date-birth'] = empty($_COOKIE['field-date-birth-value']) ? '' : $_COOKIE['field-date-birth-value'];
  $values['sex'] = empty($_COOKIE['sex-value']) ? '' : $_COOKIE['sex-value'];
  $values['num-of-limbs'] = empty($_COOKIE['num-of-limbs-value']) ? '' : $_COOKIE['num-of-limbs-value'];
  $values['super-abillities'] = empty($_COOKIE['super-abillities-value']) ? '' : $_COOKIE['super-abillities-value'];
  $values['field-biography'] = empty($_COOKIE['field-biography-value']) ? '' : $_COOKIE['field-biography-value'];
  $values['checkbox'] = empty($_COOKIE['checkbox-value']) ? '' : $_COOKIE['checkbox-value'];

  // Включаем содержимое файла main.php
  // В нем будут доступны переменные $messages, $errors и $values для вывода 
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
  include('./php/main.php');
}
// Иначе, если запрос был методом POST, т.е. нужно проверить данные и сохранить их в XML-файл.
else {

  // Проверяем ошибки.
  $errors = FALSE;
  if (empty($_POST['field-name'])) {
    // Выдаем куку на день с флажком об ошибке в поле field-name.
    setcookie('field-name-error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('field-name-value', $_POST['field-name'], time() + 30 * 24 * 60 * 60);
  }
  if (!preg_match("/@/",$_POST['field-email'])) {
    setcookie('field-email-error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('field-email-value', $_POST['field-email'], time() + 30 * 24 * 60 * 60);
  }
  
  if ($_POST['field-date-birth'] == "default") {
    setcookie('field-date-birth-error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('field-date-birth-value', $_POST['field-date-birth'], time() + 30 * 24 * 60 * 60);
  }
  
  if (empty($_POST['sex'])) {
    setcookie('sex-error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('sex-value', $_POST['sex'], time() + 30 * 24 * 60 * 60);
  }
  
  if (empty($_POST['num-of-limbs'])) {
    setcookie('num-of-limbs-error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('num-of-limbs-value', $_POST['num-of-limbs'], time() + 30 * 24 * 60 * 60);
  }
  
  if (empty($_POST['super-abillities'])) {
    setcookie('super-abillities-error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('super-abillities-value', implode(",",$_POST['super-abillities']), time() + 30 * 24 * 60 * 60);
  }

  if(!empty($_POST['field-biography'])){
    setcookie('field-biography-value', $_POST['field-biography'], time() + 30 * 24 * 60 * 60);
  }
  
  if (empty($_POST['checkbox'])) {
    setcookie('checkbox-error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  
  if ($errors) {
    // При наличии ошибок завершаем работу скрипта.
    header('Location: index.php');
    exit();
  }
  else {
    // Удаляем Cookies с признаками ошибок.
    setcookie('field-name-error', '', 100000);
    setcookie('field-email-error', '', 100000);
    setcookie('field-date-birth-error', '', 100000);
    setcookie('sex-error', '', 100000);
    setcookie('num-of-limbs-error', '', 100000);
    setcookie('super-abillities-error', '', 100000);
    setcookie('checkbox-error', '', 100000);
  }
  
  // Сохранение в базу данных.
  $user = 'u47500';
  $pass = '7787869';
  $db = new PDO('mysql:host=localhost;dbname=u47500', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
   
  $stmt1 = $db->prepare("INSERT INTO person_form VALUES (NULL,:namefield, :email, :birth_year, :sex, :num_of_limbs, :biography, :checkbox_date);");
  $stmt1->bindParam(':namefield', $namefield);
  $stmt1->bindParam(':email', $email);
  $stmt1->bindParam(':birth_year', $birth_year);
  $stmt1->bindParam(':sex', $sex);
  $stmt1->bindParam(':num_of_limbs', $num_of_limbs);
  $stmt1->bindParam(':biography', $biography);
  $stmt1->bindParam(':checkbox_date', $checkbox_date);
  $namefield = $_POST['field-name'];
  $email = $_POST['field-email'];
  $birth_year = (int)$_POST['field-date-birth'];
  $sex = $_POST['sex'];
  $num_of_limbs = (int)$_POST['num-of-limbs'];
  $biography = $_POST['field-biography'];
  $checkbox_date = date(DATE_RFC822);
  $stmt1->execute();
  
  //вставка в таблицу person_superabillities
  //получение айди последней записи
  $res = $db->query("SELECT max(id) FROM person_form");
  $row = $res->fetch();
  $count = (int) $row[0];
  
  $superabillities = $_POST['super-abillities'];
  
  foreach($superabillities as $superabillity_id) {
    $stmt2 = $db->prepare("INSERT INTO person_form_superabillities VALUES (:person_id, :superabillity_id);");
    $stmt2->bindParam(':person_id', $count);
    $stmt2->bindParam(':superabillity_id', $superabillity_id);
    $stmt2 -> execute();
  }
  
  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');

  // Делаем перенаправление.
  header('Location: index.php');
}
?>