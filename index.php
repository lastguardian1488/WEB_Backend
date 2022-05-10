<?php
header('Content-Type: text/html; charset=UTF-8');
// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  
  $messages = array();  

  if (!empty($_COOKIE['save'])) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('save', '', 100000);
    setcookie('login', '', 100000);
    setcookie('pass', '', 100000);
    // Если есть параметр save, то выводим сообщение пользователю.
    $messages['save'] = '<div class="save">Спасибо, результаты сохранены.</div>';
    // Если в куках есть пароль, то выводим сообщение.
    if (!empty($_COOKIE['pass'])) {
      $messages['authorize'] = sprintf('Вы можете <a href="php/login.php">войти</a> с логином <strong>%s</strong> и паролем <strong>%s</strong> для изменения данных.', strip_tags($_COOKIE['login']), strip_tags($_COOKIE['pass']));
    }
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

  // проверка на наличие ошибок
  $error_flag = FALSE;
  foreach ($errors as $error){
    if (!empty($error))
    $error_flag = TRUE;
  }
  
  // Если нет предыдущих ошибок ввода, есть кука сессии, начали сессию и
  // ранее в сессию записан факт успешного логина.
  // загружаем из бд данные в $values, предварительно санитизируем
  if (!$error_flag && !empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
    $user_db = 'u47500';
    $pass_db = '7787869';
    $db = new PDO('mysql:host=localhost;dbname=u47500', $user_db, $pass_db, array(PDO::ATTR_PERSISTENT => true));

    $stmt1 = $db->prepare("SELECT name,email,birth_year,sex,number_of_limbs,biography FROM user_form WHERE user_login=:user_login");
    $stmt1->bindParam(':user_login', $_SESSION['login']);
    $stmt1->execute();
    $row = $stmt1->fetch();

    // санитизация с помощью filter_var и htmlspecialchars
    $namefield = htmlspecialchars($row[0], ENT_QUOTES);
    $email = filter_var($row[1],FILTER_SANITIZE_EMAIL);
    $birth_year = filter_var($row[2],FILTER_SANITIZE_NUMBER_INT);
    $sex = htmlspecialchars($row[3], ENT_QUOTES);
    $num_of_limbs = filter_var($row[4],FILTER_SANITIZE_NUMBER_INT);
    $biography = htmlspecialchars($row[5], ENT_QUOTES);

    $stmt2 = $db->prepare("SELECT superabillity_id FROM user_superabillities WHERE user_login=:user_login");
    $stmt2->bindParam(':user_login', $_SESSION['login']);
    $stmt2->execute();

    $superabillities = array();
    foreach($stmt2 as $row){
      $element = filter_var($row[0],FILTER_SANITIZE_NUMBER_INT);
      array_push($superabillities, $element);
    }

    $values['field-name'] = $namefield;
    $values['field-email'] = $email;
    $values['field-date-birth'] = $birth_year;
    $values['sex'] = $sex;
    $values['num-of-limbs'] = $num_of_limbs;
    $values['super-abillities'] = implode(",",$superabillities);
    $values['field-biography']  = $biography;
    printf('Вход с логином %s', $_SESSION['login']);
  }

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
  
  // Проверяем меняются ли ранее сохраненные данные или отправляются новые.
  if (!empty($_COOKIE[session_name()]) && session_start() && !empty($_SESSION['login'])) {
    // для user_form делаем update всего, кроме даты чекбокса(остается такой же как при регистрации) и логина
    $user_db = 'u47500';
    $pass_db = '7787869';
    $db = new PDO('mysql:host=localhost;dbname=u47500', $user_db, $pass_db, array(PDO::ATTR_PERSISTENT => true));

    // update данных в таблице user_form, логин берем из $_SESSION
    $stmt1 = $db->prepare("UPDATE user_form SET name=:namefield, email=:email, birth_year=:birth_year, sex=:sex, number_of_limbs=:num_of_limbs, biography=:biography WHERE user_login=:login;");
    $stmt1->bindParam(':namefield', $namefield);
    $stmt1->bindParam(':email', $email);
    $stmt1->bindParam(':birth_year', $birth_year);
    $stmt1->bindParam(':sex', $sex);
    $stmt1->bindParam(':num_of_limbs', $num_of_limbs);
    $stmt1->bindParam(':biography', $biography);
    $stmt1->bindParam(':login', $login);
    $namefield = $_POST['field-name'];
    $email = $_POST['field-email'];
    $birth_year = (int)$_POST['field-date-birth'];
    $sex = $_POST['sex'];
    $num_of_limbs = (int)$_POST['num-of-limbs'];
    $biography = $_POST['field-biography'];
    $login = $_SESSION['login'];
    $stmt1->execute();

    $superabillities_post = $_POST['super-abillities'];
    // проверим нужно ли вставить в таблицу или оставить запись
    foreach ($superabillities_post as $supabil_post){
      $flag = false;
      $res1 = $db->prepare("SELECT superabillity_id FROM user_superabillities WHERE user_login=:login;");
      $res1->bindParam(':login', $login);
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
        $stmt3->bindParam(':user_login', $login);
        $stmt3->bindParam(':superabillity_id', $supabil_post);
        $supabil_post = (int)$supabil_post;
        $stmt3->execute();
      } 
    }
    // получаем данные из таблицы user_superabillities, чтобы потом сверять с $_POST
    $res2 = $db->prepare("SELECT superabillity_id FROM user_superabillities WHERE user_login=:login;");
    $res2->bindParam(':login', $login);
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
        $stmt2 = $db->prepare("DELETE FROM user_superabillities WHERE user_login=:login AND superabillity_id=:superabillity_id;");
        $stmt2->bindParam(':login', $login);
        $stmt2->bindParam(':superabillity_id', $supabil_check);
        $supabil_check = (int)$supabil_check;
        $stmt2->execute();
      }
    }
  }
  // работа с новым пользователем
  else {
    $user_db = 'u47500';
    $pass_db = '7787869';
    $db = new PDO('mysql:host=localhost;dbname=u47500', $user_db, $pass_db, array(PDO::ATTR_PERSISTENT => true));

    // Функция генерации паролей
    // TODO: можно исправить последний str_shuffle
    function generatePassword($numAlpha = 4, $numDigit = 3, $numNonAlpha = 2): string
    {
      $listAlpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $listDigits = '0123456789';
      $listNonAlpha = '_';
      return str_shuffle(
        substr(str_shuffle($listAlpha), 0, $numAlpha) .
        substr(str_shuffle($listDigits), 0, $numDigit) .
        substr(str_shuffle($listNonAlpha), 0, $numNonAlpha)
      );
    }

    // генерация уникального логина, для этого берем последний логин из таблицы users
    // и добавляем к нему единицу, вид логина u*******, где ******** - число от 1 до 9999999 с незначимыми нулями слева
    $res = $db->query("SELECT max(user_login) FROM users");
    $row = $res->fetch();
    $last_user = $row[0];
    $bad_nums = array('9', '99', '999', '9999', '99999', '999999'); // исключительная ситуация, когда увеличивается количество знаков в логине - недопустимо
    $last_user = substr($last_user, 1, strlen($last_user));
    for ($i = 0; $i < strlen($last_user); $i++){
      if ($last_user[$i] != '0'){
        $tmp = (int)substr($last_user, $i, strlen($last_user)-$i);
        $new_tmp = strval($tmp + 1);
        $tmp = strval($tmp);
        $flag = FALSE;
        foreach ($bad_nums as $bad_num)
          if ($tmp == $bad_num)
            $flag = TRUE;
        if ($flag)     
          $last_user =  'u'.str_replace('0'.$tmp, $new_tmp, $last_user);
        else
          $last_user =  'u'.str_replace($tmp, $new_tmp, $last_user);
        break;
      }
    }

    // используем вышеописанные способы, чтобы получить уникальный логин и пароль к нему 
    $login = $last_user;
    $pass = generatePassword();
    // Сохраняем в Cookies.
    setcookie('login', $login);
    setcookie('pass', $pass);

    // выполняем вставку в таблицу users, где будут храниться данные о пользователях
    $stmt0 = $db->prepare("INSERT INTO users VALUES (:login, :pass_hash);");
    $stmt0->bindParam(':login', $login);
    $stmt0->bindParam(':pass_hash', $pass_hash);
    $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
    $stmt0->execute();
    
    // вставка в таблицу user_form, в которой хранятся пользовательские данные и дата подписания чекбокса, выбранные суперспособности в отдельной таблице
    $stmt1 = $db->prepare("INSERT INTO user_form VALUES (:login,:namefield, :email, :birth_year, :sex, :num_of_limbs, :biography, :checkbox_date);");
    $stmt1->bindParam(':login', $login);
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
    
    // вставка в таблицу user_superabillities, таблица содержит записи вида логин - айди выбранной способности,
    // если способностей несколько, то будет несколько таблиц
    // таблица реализует связь многие-ко-многим
    $superabillities = $_POST['super-abillities'];
    
    foreach($superabillities as $superabillity_id) {
      $stmt2 = $db->prepare("INSERT INTO user_superabillities VALUES (:user_login, :superabillity_id);");
      $stmt2->bindParam(':user_login', $login);
      $stmt2->bindParam(':superabillity_id', $superabillity_id);
      $stmt2 -> execute();
    }
  }
  
  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');

  // Делаем перенаправление.
  header('Location: ./');
}
?>