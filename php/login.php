<?php

/**
 * Файл login.php для не авторизованного пользователя выводит форму логина.
 * При отправке формы проверяет логин/пароль и создает сессию,
 * записывает в нее логин и id пользователя.
 * После авторизации пользователь перенаправляется на главную страницу
 * для изменения ранее введенных данных.
 **/

// Отправляем браузеру правильную кодировку,
// файл login.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// Начинаем сессию.
session_start();

// включаем содержимое db.php
include("db.php");

// В суперглобальном массиве $_SESSION хранятся переменные сессии.
// Будем сохранять туда логин после успешной авторизации.
if (!empty($_SESSION['login'])) {
  // Если есть логин в сессии, то пользователь уже авторизован.
  // при этом эта часть скрипта сработает, когда пользователь нажмет на кнопку Выход и затем он выйдет из сессии
  // Замечание: Это уничтожит сессию, а не только данные сессии!
  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
  }

  session_destroy();

  // удаляем данные значений введеных в форму из cookie
  setcookie('field-name-value', '', 100000);
  setcookie('field-email-value', '', 100000);
  setcookie('field-date-birth-value', '', 100000);
  setcookie('sex-value', '', 100000);
  setcookie('num-of-limbs-value', '', 100000);
  setcookie('super-abillities-value', '', 100000);
  setcookie('field-biography-value', '', 100000);
  // Делаем перенаправление на форму.
  header('Location: ../');
}

// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
?>

<form action="" method="post">
  <input name="login" />
  <input name="pass" />
  <input type="submit" value="Войти" />
</form>

<?php
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {
  //Проверка есть ли такой логин и пароль в базе данных.

  $stmt = $db->prepare("SELECT user_password FROM users WHERE user_login=:login;");
  $stmt->bindParam(':login', $_POST['login']);
  $stmt->execute();
  if($res = $stmt->fetch()){
    $pass_hash = $res[0];
    if (password_verify($_POST['pass'],$pass_hash)){
      // Если все ок, то авторизуем пользователя.
      $_SESSION['login'] = $_POST['login'];
      // Записываем ID пользователя.
      // TODO: генерация id (не нужен, вместо идентификатора логин, он уникальный)   
      // $_SESSION['uid'] = 123;
      // Делаем перенаправление.
      header('Location: ../');
    }
    else{
      print 'пароль неверный';
    }
  }
  else{
    print 'Нет такого логина';
  }

}
