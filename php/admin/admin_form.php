<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="../../styles/style_admin.css">
    <title>ADMIN</title>
</head>
<body>
    <div class="main-content">
        <div class="adm-panel">
            <h1 class="text-center">Панель администратора</h1>
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="statistics">
                            <h2>Статистика суперспособностей</h2>
                            <table>
                                <tr><th>Название</th><th>Количество</th></tr>
                                <?php 
                                $stmt1 = $db->prepare("SELECT superabillity_name, id FROM superabillities;");
                                $stmt1->execute();
                                foreach ($stmt1 as $row){
                                    // делаем запрос для подсчета количества
                                    $stmt2 = $db->prepare("SELECT COUNT(*) FROM user_superabillities WHERE superabillity_id=:id;");
                                    $stmt2->bindParam(":id", $row[1]);
                                    $stmt2->execute();
                                    $res = $stmt2->fetch();
                                    print '<tr><td>'.$row[0].'</td><td>'.$res[0].'</td></tr>';
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <form class="form" action="" method="POST">
                            <label>
                                <span>Выберите пользователя</span><br>
                                <select name="users" id="select-users">
                                    <?php 
                                    $stmt = $db->prepare("SELECT user_login, name FROM user_form ORDER BY user_login");
                                    $stmt->execute();
                                    foreach ($stmt as $row){
                                    $login = $row[0];
                                    $name = $row[1];
                                    ?>
                                    <option value=<?= $login ?> <?php if(!empty($_COOKIE['selected-user']) && $_COOKIE['selected-user'] == $login){ print 'selected';} ?>><?= $login,'  ',$name ?></option>
                                    <?php } ?>
                                </select>
                            </label><br>
                            <label><input name="delete-user" type="submit" value="УДАЛИТЬ ПОЛЬЗОВАТЕЛЯ" /></label>
                            <label><input name="edit-user" type="submit" value="РЕДАКТИРОВАТЬ ДАННЫЕ" /></label><br>
                            <?php
                            if (!empty($_COOKIE['edit'])){
                            if (!empty($_COOKIE['save'])) { print $messages['save'];}?>
                            <label> <span>Имя:<br /></span><input name="field-name" value="<?php print $values['field-name']; ?>" type="text"/><?php if ($errors['field-name']) {print ($messages['field-name']);}?></label><br />
                            <label> <span>Email:<br/></span><input name="field-email" value="<?php print $values['field-email']; ?>" type="email"/><?php if ($errors['field-email']) {print ($messages['field-email']);}?></label><br/>
                            <label>
                                <span>Год рождения:<br></span>
                                <select name="field-date-birth">
                                <option value="default" hidden>Выберите год рождения</option>
                                <?php for($i = 1900; $i <= date('Y'); $i++): ?>
                                    <option value="<?=$i?>" <?php if($values['field-date-birth'] == $i) {print 'selected';}?>><?=$i?></option>
                                    <?php endfor; ?>
                                </select>
                                <?php if ($errors['field-date-birth']) {print ($messages['field-date-birth']);}?>
                            </label><br />
                            <fieldset class="my-1" name="sex" >
                                <span>Пол:<br /></span> 
                                <label><input class="custom-radio mx-1" type="radio" name="sex" value="m" <?php if($values['sex'] == 'm') {print 'checked';}?>/>Мужской</label>
                                <label><input class="custom-radio mx-1" type="radio" name="sex" value="w" <?php if($values['sex'] == 'w') {print 'checked';}?>/>Женский</label><br />
                                <?php if ($errors['sex']) {print ($messages['sex']);}?> 
                            </fieldset>
                            <fieldset class="my-1" id="num-of-limbs" value="<?php print $values['num-of-limbs'];?>">
                                <span>Количество конечностей:<br /></span>
                                <label><input class="custom-radio mx-1" type="radio" name="num-of-limbs" value="4" <?php if($values['num-of-limbs'] == '4') {print 'checked';}?>/>4</label>
                                <label><input class="custom-radio mx-1" type="radio" name="num-of-limbs" value="6" <?php if($values['num-of-limbs'] == '6') {print 'checked';}?>/>6</label>
                                <label><input class="custom-radio mx-1" type="radio" name="num-of-limbs" value="15"<?php if($values['num-of-limbs'] == '15') {print 'checked';}?>/>15</label><br />
                                <?php if ($errors['num-of-limbs']) {print ($messages['num-of-limbs']);}?>
                            </fieldset>
                            <label>
                                <span>Суперспособности:<br /></span>
                                <select name="super-abillities[]" multiple="multiple" >
                                <?php 
                                    // запрос в базу данных для выгрузки суперспособностей из таблицы superabillities
                                    $results = $db->query("SELECT * FROM superabillities");
                                    $superabillities = explode(",",$values['super-abillities']);  //массив для заполнения значений тега select через cockies(selected выставляется на выбранные)
                                    while($row = $results->fetch()) {
                                        $super_id = $row[0];
                                        $super_name = $row[1];
                                        ?>
                                    <option value="<?=$super_id?>" <?php foreach($superabillities as $superabillity) if($superabillity == $super_id) print 'selected'?> ><?=$super_name?></option>
                                    <?php } ?>
                                    </select>
                                    <?php if ($errors['super-abillities']) {print ($messages['super-abillities']);}?>
                            </label><br /> 
                            <label> <span>Биография:</span><br><textarea name="field-biography" placeholder="С чего начнёте?"><?php print $values['field-biography'];?></textarea></label><br />
                            <label><input name="save-changed" type="submit" value="СОХРАНИТЬ ИЗМЕНЕНИЯ"/></label>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
</body>
</html>