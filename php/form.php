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
                        Год рождения:<br>
                        <select name="field-date-birth">
                            <option value="default" hidden>Выберите год рождения</option>
                            <?php for($i = 1900; $i <= date('Y'); $i++): ?>
                            <option value="<?=$i?>"><?=$i?></option>
                            <?php endfor; ?>
                        </select>
                    </label><br />
                    <span>Ваш пол:<br /></span> 
                    <label><input type="radio" name="sex" value="m"/>Мужской</label>
                    <label><input type="radio" name="sex" value="w"/>Женский</label><br /> 
                    <span>Количество конечностей:<br /></span>
                    <label><input type="radio" name="NumberOfLimbs" value="4" />4</label>
                    <label><input type="radio" name="NumberOfLimbs" value="6" />6</label>
                    <label><input type="radio" name="NumberOfLimbs" value="15"/>15</label><br />
                    <label>
                        Ваши суперспособности:<br />
                        <select name="SuperAbilities[]" multiple="multiple">
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