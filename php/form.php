<div class="task_3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center my-5">Задание 3</h1>
            </div>
            <!-- TODO: адекватно настроить стили формы и отображения ошибок -->
            <div class="col-12">
                <div class="form-wrapper">
                    <form class="form align-center" action="" method="POST">
                        <?php if (!empty($_COOKIE['save'])) { print $messages['save'];}?>
                        <label> <span>Ваше имя:<br /></span><input name="field-name" value="<?php print $values['field-name']; ?>" type="text"/><?php if ($errors['field-name']) {print ($messages['field-name']);}?></label><br />
                        <label> <span>Ваш email:<br/></span><input name="field-email" value="<?php print $values['field-email']; ?>" type="email"/><?php if ($errors['field-email']) {print ($messages['field-email']);}?></label><br/>
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
                            <span>Ваш пол:<br /></span> 
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
                            <span>Ваши суперспособности:<br /></span>
                            <select name="super-abillities[]" multiple="multiple" >
                                <?php 
                                // запрос в базу данных для выгрузки суперспособностей из таблицы superabillities
                                $user = 'u47500';
                                $pass = '7787869';
                                $db = new PDO('mysql:host=localhost;dbname=u47500', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
                                $results = $db->query("SELECT * FROM superabillities");
                                $superabillities = explode(",",$values['super-abillities']);  //массив для заполнения значений тега select через cockies(selected выставляется на выбранные)
                                while($row = $results->fetch()) {
                                    $super_name = $row[0];
                                    $super_id = $row[1];
                                    ?>
                                <option value="<?=$super_id?>" <?php foreach($superabillities as $superabillity) if($superabillity == $super_id) print 'selected'?> ><?=$super_name?></option>
                                <?php } ?>
                            </select>
                            <?php if ($errors['super-abillities']) {print ($messages['super-abillities']);}?>
                        </label><br /> 
                        <label> <span>Биография:</span><br><textarea name="field-biography" placeholder="С чего начнёте?"><?php print $values['field-biography'];?></textarea></label><br />
                        <label><br /><input class="custom-checkbox" type="checkbox" name="checkbox"/>С контрактом ознакомлен(а)<?php if ($errors['checkbox']) {print ($messages['checkbox']);}?></label><br />
                        <label><span>Отправить форму:<br></span><input type="submit" value="Отправить" /></label>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>