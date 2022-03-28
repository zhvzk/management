<section class="form-container">
    <div class="form-container-text">
        <span class="form-container-text-title">Изменение данных филиала</span>
        <span class="form-container-text-subtitle">Пожалуйста, обязательно проверьте правильность ввода</span>
    </div>
    <form method="post" action="" class="form">
        <? foreach ($data['branchData'] as $key => $value): ?>
            <div class="form-row">
                <label class="label" class="form-row-label" for="<?=$key?>"><?=$key?></label>
                <input placeholder="<?= $key?>" value="<?=$value?>" name="<?=$key?>" id="<?=$key?>" required class="form-row-input"><br>
            </div>
        <?endforeach;?>
        <input  class="form-row-submit" type="submit" value="Сохранить изменения"  class="regular-button">
    </form>
</section>

