<secton class="form-container">
    <div class="form-container-text">
        <span class="form-container-text-title">Изменение личных данных</span>
        <span class="form-container-text-subtitle">Пожалуйста, обязательно проверьте правильность ввода</span>
    </div>
    <form method="post" action="" class="form">
        <? foreach ($data['userData'] as $key => $value): ?>
            <div class="form-row">
                <label class="form-row-label" for="<?=$key?>"><?=$key?></label>
                <input placeholder="<?= $key?>" value="<?=$value?>" name="<?=$key?>" id="<?=$key?>" required class="form-row-input"><br>
            </div>
        <?endforeach;?>
        <div class="form-row">
            <label for="id_role" class="form-row-label">Роль</label>
            <select name="id_role" class="form-row-input" id="id_role">
                <? foreach ($data['roles'] as $key => $role):?>
                    <option <? if ($role['id'] == $data['id_role']) echo "selected"?> value="<?=$role['id']?>"><?=$role['name']?></option>
                <?endforeach;?>
            </select>
        </div>
        <input type="submit" value="Сохранить изменения"  class="form-row-submit">
    </form>
</secton>