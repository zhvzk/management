

<section class="form-container">
    <div class="form-container-text">
        <span class="form-container-text-title">Добавить смену</span>
    </div>
    <?if (!empty($data['error'])):?>
        <?foreach ($data['error'] as $error):?>

            <span class="form-container-text-subtitle"><?=$error?></span>
        <?endforeach;?>
    <?endif;?>

    <form method="post" action="" class="form">
        <div class="form-row">
            <label class="form-row-label" for="title">Название</label>
            <input type="text" placeholder="Название" name="title" maxlength="128" required class="form-row-input" id="title">
        </div>
        <div class="form-row">
            <label class="form-row-label" for="description">Описание</label>
            <input type="text" placeholder="Описание" name="description" maxlength="128" required class="form-row-input" id="description">
        </div>
        <div class="form-row">
            <label class="form-row-label" for="branch">Филиал</label>
            <select required class="form-row-input" id="branch" name="branch">
                <option disabled>Выберите филиал</option>
                <?if ((!empty($data['branches']))&&(count($data['branches']))):?>
                    <?foreach ($data['branches'] as $branch):?>

                        <option value="<?=$branch['id']?>"><?=$branch['name']?> - <?=$branch['address']?></option>
                    <?endforeach;?>
                <?endif;?>
            </select>
        </div>
        <div class="form-row">
            <label class="form-row-label" for="rate_per_hour">Ставка в час</label>
            <input type="number" placeholder="185" value="185" name="rate_per_hour" maxlength="11" required class="form-row-input" id="rate_per_hour">
        </div>
        <div class="form-row">
            <label class="form-row-label" for="date">Дата</label>
            <input type="date" name="date" maxlength="11" required class="form-row-input" id="date" min="<?=date("Y-m-d", strtotime("now"))?>" max="<?=date("Y-m-d", strtotime("first day of +1 month"));?>">
        </div>
        <div class="form-row">
            <label class="form-row-label" for="wd_start">Начало смены</label>
            <input type="time" name="wd_start" maxlength="11" required class="form-row-input" id="wd_start">
        </div>
        <div class="form-row">
            <label class="form-row-label" for="wd_end">Конец смены</label>
            <input type="time" name="wd_end" maxlength="11" required class="form-row-input" id="wd_end">
        </div>
        <div class="form-row">
            <label class="form-row-label" for="lunch_start">Начало перерыва</label>
            <input type="time" name="lunch_start" maxlength="11" required class="form-row-input" id="lunch_start">
        </div>
        <div class="form-row">
            <label class="form-row-label" for="lunch_end">Конец перерыва</label>
            <input type="time" name="lunch_end" maxlength="11" required class="form-row-input" id="lunch_end">
        </div>
        <input type="submit" value="Добавить" class="form-row-submit">
    </form>

</section>