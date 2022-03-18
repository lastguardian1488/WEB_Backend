<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="slick/slick-theme.css"/>
    <link rel="stylesheet" type="text/css" href="styles/style.css">
    <title>Document</title>
</head>
<body>
    <div class="main-content">
        <div class="task_1">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-center my-5">Задание 1</h1>
                    </div>
                    <div class="col-12">
                        <div class="slider-task-1">
                            <div class="slick-slide">
                                <div class="sreenshot-block">
                                    <img src="images/task_1/step_2_Ping.png" alt = "pic">
                                </div>
                                <p>
                                    2) С помощью команды ping на учебном сервере узнать IP-адрес веб-сервера kubsu.ru<br>
                                    Ping – утилита командной строки, которая нужна для проверки подключения к другому компьютеру на уровне IP.
                                </p>
                            </div>
                            <div class="slick-slide">
                                <div class="sreenshot-block">
                                    <img src="images/task_1/step_3_nslookup.png" alt = "pic">
                                </div>
                                <p>
                                    3) С помощью команды nslookup узнал A-записи и MX-записи домена kubsu.ru и kubsu-dev.ru<br>
                                    Запись MX (Mail eXchange, обмен почтой) хранит соответствие доменного имени почтовому серверу этого домена.<br>
                                    Запись A(address) хранит IP-адрес сервера и его доменное имя.
                                </p>
                            </div>
                            <div class="slick-slide">
                                <div class="sreenshot-block">
                                    <img src="images/task_1/step_4_whois.png" alt = "pic">
                                </div>
                                <p>
                                    4) С помощью команды whois узнал дату регистрации домена kubsu.ru и kubsudev.ru
                                </p>
                            </div>
                            <div class="slick-slide">
                                <div class="sreenshot-block">
                                    <img src="images/task_1/step_5_FTP.png" alt = "pic">
                                </div>
                                <p>
                                    6) С помощью программы FileZilla соединился с учебным сервером с моим логином и паролем по протоколу FTP<br>
                                    и скопировал на локальный компьютер файлы задания из каталога /var/www/html/WEB_Backend/
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="split-line">
        <div class="task_2">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-center my-5">Задание 2</h1>
                    </div>
                    <div class="col-12">
                        <div class="slider-task-2">
                            <div class="slick-slide">
                                <div class="sreenshot-block">
                                    <img src="images/task_2/пункт1.png" alt = "pic">
                                </div>
                                <p class="slide-text">
                                    1) получение главной страницы методом GET в протоколе HTTP 1.0<br>
                                       ip сервера 212.192.134.20 (kubsu-dev.ru)
                                </p>
                            </div>
                            <div class="slick-slide">
                                <div class="sreenshot-block">
                                    <img src="images/task_2/пункт2.png" alt = "pic">
                                </div>
                                <p class="slide-text">
                                    2) получение внутренней страницы методом GET в протоколе HTTP 1.1<br>
                                       ip сервера 212.192.134.20 (kubsu-dev.ru)
                                </p>
                            </div>
                            <div class="slick-slide">
                                <div class="sreenshot-block">
                                    <img src="images/task_2/пункт3.png" alt = "pic">
                                </div>
                                <p class="slide-text">
                                    3) определение размера файла file.tar.gz<br>
                                    Для определения использовался метод HEAD,<br>
                                    а сам размер файла отражен в заголовке ответа Content-Length
                                </p>
                            </div>
                            <div class="slick-slide">
                                <div class="sreenshot-block">
                                    <img src="images/task_2/пункт4.png" alt = "pic">
                                </div>
                                <p class="slide-text">
                                    4) определение медиатипа ресурса /image.png<br>
                                    Для определения использовался метод HEAD,<br>
                                    а медиатип отражен в заголовке ответа Content-Type
                                </p>
                            </div>
                            <div class="slick-slide">
                                <div class="sreenshot-block">
                                    <img src="images/task_2/пункт5.png" alt = "pic">
                                </div>
                                <p class="slide-text">
                                    5) отправление комментария на сервер по адресу files/index.php<br>
                                    Для этого использовался метод POST,<br>
                                    в сущности ответа передается комментарий
                                </p>
                            </div>
                            <div class="slick-slide">
                                <div class="sreenshot-block">
                                    <img src="images/task_2/пункт6.png" alt = "pic">
                                </div>
                                <p class="slide-text">
                                    6) получение первых 100 байт файла /file.tar.gz<br>
                                    Для этого использовался метод GET,<br>
                                    в заголовке запроса Range указывается необходимый диапазон
                                </p>
                            </div>
                            <div class="slick-slide">
                                <div class="sreenshot-block">
                                    <img src="images/task_2/пункт7.png" alt = "pic">
                                </div>
                                <p class="slide-text">
                                    7) определение кодировки ресурса /index.php<br>
                                    Для определения использовался метод HEAD,<br>
                                    а сама кодировка отражена в заголовке ответа Content-Type в параматре charset
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="split-line">
        <div class="task_3">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h1 class="text-center my-5">Задание 3</h1>
                    </div>
                    <div class="col-12">
                        <form action="" method="POST">
                            <label> Ваше имя:<br /><input name="field-name" value=""/></label><br />
                            <label> Ваш email:<br/><input name="field-email" value="" type="email"/></label><br/>
                            <label> 
                                Год Рождения:<br />
                                <select name="field-date-birth">
                                    <option value="default" hidden>Выберите год рождения</option>
                                    <option value="2022">2022</option>
                                    <option value="2021">2021</option>
                                    <option value="2020">2020</option>
                                    <option value="2019">2019</option>
                                    <option value="2018">2018</option>
                                    <option value="2017">2017</option>
                                    <option value="2016">2016</option>
                                    <option value="2015">2015</option>
                                    <option value="2014">2014</option>
                                    <option value="2013">2013</option>
                                </select>
                            </label><br />
                            <span>Ваш пол:<br /></span> 
                            <label><input type="radio" name="sex" value="1"/>Мужской</label>
                            <label><input type="radio" name="sex" value="2"/>Женский</label><br /> 
                            <span>Количество конечностей:<br /></span>
                            <label><input type="radio" name="NumberOfLimbs" value="4" />4</label>
                            <label><input type="radio" name="NumberOfLimbs" value="6" />6</label>
                            <label><input type="radio" name="NumberOfLimbs" value="15"/>15</label><br />
                            <label>
                                Ваши суперспособности:<br />
                                <select name="SuperAbilities" multiple="multiple">
                                    <option value="X-RAY видение">X-RAY видение</option>
                                    <option value="Вставать по будильнику">Вставать по будильнику</option>
                                    <option value="Полёт">Полёт</option>
                                    <option value="Бессмертие">Бессмертие</option>
                                </select>
                            </label><br />
                            <label> Биография:<br /><textarea name="field-biography" placeholder="С чего начнёте?"></textarea></label><br />
                            <label><br /><input type="checkbox" name="checkbox" />С контрактом ознакомлен(а)</label><br />
                            <label>Отправить форму:<input type="submit" value="Отправить" /></label>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="slick/slick.min.js"></script>
    <script src="javascript/sliders.js"></script>
</body>
</html>