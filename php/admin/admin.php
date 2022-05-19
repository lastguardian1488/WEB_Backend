<?php
// файл с HTTP авторизацией для админа

// подключаем содержимое файла db.php для запросов к бд, из него будет доступна переменная $db
include("../db.php");

if (!empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW'])){
  $stmt = $db->prepare("SELECT admin_login, admin_password FROM admins;");
  $stmt->execute();
  
  $auth_flag = FALSE;
  foreach ($stmt as $row){
    if ($_SERVER['PHP_AUTH_USER'] == $row[0] && password_verify($_SERVER['PHP_AUTH_PW'],$row[1]))
    $auth_flag = TRUE;
  }
  if(!$auth_flag){
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
  }
}
elseif (empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW'])){
  header('HTTP/1.1 401 Unanthorized');
  header('WWW-Authenticate: Basic realm="My site"');
  print('<h1>401 Требуется авторизация</h1>');
  exit();
}
// Пример HTTP-аутентификации.
// PHP хранит логин и пароль в суперглобальном массиве $_SERVER.
// Подробнее см. стр. 26 и 99 в учебном пособии Веб-программирование и веб-сервисы.

// *********
// Здесь нужно прочитать отправленные ранее пользователями данные и вывести в таблицу.
// Реализовать просмотр и удаление всех данных.
// *********

if ($_SERVER['REQUEST_METHOD'] == 'GET'){


  $messages = array();
  // TODO: изменение структуры бд для админа и проверка из неё авторизации
  $errors = array();
  $errors['field-name'] = !empty($_COOKIE['field-name-error']);
  $errors['field-email'] = !empty($_COOKIE['field-email-error']);
  $errors['field-date-birth'] = !empty($_COOKIE['field-date-birth-error']);
  $errors['sex'] = !empty($_COOKIE['sex-error']);
  $errors['num-of-limbs'] = !empty($_COOKIE['num-of-limbs-error']);
  $errors['super-abillities'] = !empty($_COOKIE['super-abillities-error']);

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

  // Помещаем в $values значения из cookies, которые подгрузились из бд
  $values = array();
  $values['field-name'] = empty($_COOKIE['field-name-value']) ? '' : $_COOKIE['field-name-value'];
  $values['field-email'] = empty($_COOKIE['field-email-value']) ? '' : $_COOKIE['field-email-value'];
  $values['field-date-birth'] = empty($_COOKIE['field-date-birth-value']) ? '' : $_COOKIE['field-date-birth-value'];
  $values['sex'] = empty($_COOKIE['sex-value']) ? '' : $_COOKIE['sex-value'];
  $values['num-of-limbs'] = empty($_COOKIE['num-of-limbs-value']) ? '' : $_COOKIE['num-of-limbs-value'];
  $values['super-abillities'] = empty($_COOKIE['super-abillities-value']) ? '' : $_COOKIE['super-abillities-value'];
  $values['field-biography'] = empty($_COOKIE['field-biography-value']) ? '' : $_COOKIE['field-biography-value'];

  // подключаем содержимое файла admin_form.php
  include("./admin_form.php");
}
elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['delete-user'])){
  // после удаления юзера чистим cookie, чтобы не было несоответствий
  setcookie('field-name-value', '', 100000);
  setcookie('field-email-value', '', 100000);
  setcookie('field-date-birth-value', '', 100000);
  setcookie('sex-value', '', 100000);
  setcookie('num-of-limbs-value', '', 100000);
  setcookie('super-abillities-value', '', 100000);
  setcookie('field-biography-value', '', 100000);
  setcookie('selected-user', '', 100000);
  setcookie('edit', '', 100000);

  // для удаления пользователя мы должны удалить данные о нём из таблиц user_form, users, user_superabillities
  $stmt1 = $db->prepare("DELETE FROM user_form WHERE user_login=:user_login");
  $stmt1->bindParam(':user_login', $user_login);
  $user_login = explode(" ", $_POST['users']);
  $user_login = $user_login[0];
  $stmt1->execute();
  
  $stmt2 = $db->prepare("DELETE FROM user_superabillities WHERE user_login=:user_login");
  $stmt2->bindParam(':user_login', $user_login);
  $stmt2->execute();

  // удаляем из users в последнюю очередь, т.к. есть ограничение - внешние ключи
  $stmt3 = $db->prepare("DELETE FROM users WHERE user_login=:user_login");
  $stmt3->bindParam(':user_login', $user_login);
  $stmt3->execute();


  header('Location: ./admin.php');
}
elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['edit-user'])){
  setcookie('edit', '1');
  
  // получаем значения для заполнения $values из SELECT
  $stmt1 = $db->prepare("SELECT name,email,birth_year,sex,number_of_limbs,biography FROM user_form WHERE user_login=:user_login");
  $stmt1->bindParam(':user_login', $user_login);
  $user_login = explode(" ", $_POST['users']);
  $user_login = $user_login[0];
  $stmt1->execute();
  $row = $stmt1->fetch();

  // санитизация с помощью filter_var и htmlspecialchars
  $namefield = htmlspecialchars($row[0], ENT_QUOTES);
  $email = filter_var($row[1],FILTER_SANITIZE_EMAIL);
  $birth_year = filter_var($row[2],FILTER_SANITIZE_NUMBER_INT);
  $sex = htmlspecialchars($row[3], ENT_QUOTES);
  $num_of_limbs = filter_var($row[4],FILTER_SANITIZE_NUMBER_INT);
  $biography = htmlspecialchars($row[5], ENT_QUOTES);
  
  // для superabillities сделаем отдельный запрос
  $stmt2 = $db->prepare("SELECT superabillity_id FROM user_superabillities WHERE user_login=:user_login");
  $stmt2->bindParam(':user_login', $user_login);
  $stmt2->execute();

  $superabillities = array();
  foreach($stmt2 as $row){
    $element = filter_var($row[0],FILTER_SANITIZE_NUMBER_INT);
    array_push($superabillities, $element);
  }

  if (empty($_COOKIE['selected-user'])) setcookie('selected-user', $user_login);
  if (empty($_COOKIE['field-name-value'])) setcookie('field-name-value', $namefield);
  if (empty($_COOKIE['field-email-value'])) setcookie('field-email-value', $email);
  if (empty($_COOKIE['field-date-birth-value'])) setcookie('field-date-birth-value', $birth_year);
  if (empty($_COOKIE['sex-value'])) setcookie('sex-value', $sex);
  if (empty($_COOKIE['num-of-limbs-value'])) setcookie('num-of-limbs-value', $num_of_limbs);
  if (empty($_COOKIE['super-abillities-value'])) setcookie('super-abillities-value', implode(",",$superabillities));
  if (empty($_COOKIE['field-biography-value'])) setcookie('field-biography-value', $biography);
  
  header('Location: ./admin.php');
}
elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['save-changed'])){
  // проверка ошибок
  $errors_flag = FALSE;
  if (empty($_POST['field-name'])) {
    // Выдаем куку на день с флажком об ошибке в поле field-name.
    setcookie('field-name-error', '1', time() + 24 * 60 * 60);
    $errors_flag = TRUE;
  }
  else {
    // Сохраняем ранее введенное в форму значение на месяц.
    setcookie('field-name-value', $_POST['field-name'], time() + 30 * 24 * 60 * 60);
  }
  if (!preg_match("/@/",$_POST['field-email'])) {
    setcookie('field-email-error', '1', time() + 24 * 60 * 60);
    $errors_flag = TRUE;
  }
  else {
    setcookie('field-email-value', $_POST['field-email'], time() + 30 * 24 * 60 * 60);
  }
  
  if ($_POST['field-date-birth'] == "default") {
    setcookie('field-date-birth-error', '1', time() + 24 * 60 * 60);
    $errors_flag = TRUE;
  }
  else {
    setcookie('field-date-birth-value', $_POST['field-date-birth'], time() + 30 * 24 * 60 * 60);
  }
  
  if (empty($_POST['sex'])) {
    setcookie('sex-error', '1', time() + 24 * 60 * 60);
    $errors_flag = TRUE;
  }
  else {
    setcookie('sex-value', $_POST['sex'], time() + 30 * 24 * 60 * 60);
  }
  
  if (empty($_POST['num-of-limbs'])) {
    setcookie('num-of-limbs-error', '1', time() + 24 * 60 * 60);
    $errors_flag = TRUE;
  }
  else {
    setcookie('num-of-limbs-value', $_POST['num-of-limbs'], time() + 30 * 24 * 60 * 60);
  }
  
  if (empty($_POST['super-abillities'])) {
    setcookie('super-abillities-error', '1', time() + 24 * 60 * 60);
    $errors_flag = TRUE;
  }
  else {
    setcookie('super-abillities-value', implode(",",$_POST['super-abillities']), time() + 30 * 24 * 60 * 60);
  }

  if(!empty($_POST['field-biography'])){
    setcookie('field-biography-value', $_POST['field-biography'], time() + 30 * 24 * 60 * 60);
  }
  
  if ($errors_flag) {
    // При наличии ошибок завершаем работу скрипта.
    header('Location: ./admin.php');
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
  }
  
  // update данных в таблице user_form
  $stmt1 = $db->prepare("UPDATE user_form SET name=:namefield, email=:email, birth_year=:birth_year, sex=:sex, number_of_limbs=:num_of_limbs, biography=:biography WHERE user_login=:user_login;");
  $stmt1->bindParam(':namefield', $namefield);
  $stmt1->bindParam(':email', $email);
  $stmt1->bindParam(':birth_year', $birth_year);
  $stmt1->bindParam(':sex', $sex);
  $stmt1->bindParam(':num_of_limbs', $num_of_limbs);
  $stmt1->bindParam(':biography', $biography);
  $stmt1->bindParam(':user_login', $user_login);
  $namefield = $_POST['field-name'];
  $email = $_POST['field-email'];
  $birth_year = (int)$_POST['field-date-birth'];
  $sex = $_POST['sex'];
  $num_of_limbs = (int)$_POST['num-of-limbs'];
  $biography = $_POST['field-biography'];
  $user_login = explode(" ", $_POST['users']);
  $user_login = $user_login[0];
  $stmt1->execute();

  $superabillities_post = $_POST['super-abillities'];
  // проверим нужно ли вставить в таблицу или оставить запись
  foreach ($superabillities_post as $supabil_post){
    $flag = false;
    $res1 = $db->prepare("SELECT superabillity_id FROM user_superabillities WHERE user_login=:user_login;");
    $res1->bindParam(':user_login', $user_login);
    $res1->execute();
    foreach ($res1 as $row) {
      $supabil_check = $row[0];
      if ($supabil_post == $supabil_check){
        $flag = false;
        break;
      }
      $flag = true;
    }
    if ($flag == true){
      $stmt3 = $db->prepare("INSERT INTO user_superabillities VALUES (:user_login, :superabillity_id);");
      $stmt3->bindParam(':user_login', $user_login);
      $stmt3->bindParam(':superabillity_id', $supabil_post);
      $supabil_post = (int)$supabil_post;
      $stmt3->execute();
    } 
  }
  // получаем данные из таблицы user_superabillities, чтобы потом сверять с $_POST
  $res2 = $db->prepare("SELECT superabillity_id FROM user_superabillities WHERE user_login=:user_login;");
  $res2->bindParam(':user_login', $user_login);
  $res2->execute();
  // проверка нужно ли удалить запись из таблицы или оставить
  foreach ($res2 as $row) {
    $supabil_check = $row[0];
    $flag = false;
    foreach ($superabillities_post as $supabil_post){
      if ($supabil_post == $supabil_check){
        $flag = false;
        break;
      }
      $flag = true;
    }
    if ($flag == true){
      $stmt2 = $db->prepare("DELETE FROM user_superabillities WHERE user_login=:user_login AND superabillity_id=:superabillity_id;");
      $stmt2->bindParam(':user_login', $user_login);
      $stmt2->bindParam(':superabillity_id', $supabil_check);
      $supabil_check = (int)$supabil_check;
      $stmt2->execute();
    }
  }

  // перезагружаем скрипт
  header('Location: ./admin.php');
}
?>