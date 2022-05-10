<!-- Navigation -->
<ul class="nav nav-tabs" id="navId">
    <li class="nav-item">
        <a class="nav-link active" href="php/login.php"><?php if (session_status() == PHP_SESSION_NONE && empty($_COOKIE[session_name()])) {print'Вход';} else {print 'Выход';}?></a>
    </li>
</ul>