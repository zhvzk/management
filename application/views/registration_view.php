<?php
//echo "<pre>";
//print_r($data);
//////print_r($_POST);
//print_r($_FILES);
//if (!empty($data['success'])) {
//    echo $data['success'];
//}
//?>
<!--<span>Завершение регистрации</span>-->
<section class="form-container">
<div class="form-container-text">
    <span class="form-container-text-title">Завершение регистрации</span>
    <span class="form-container-text-subtitle">Ваш логин: <span class="text-bold"><?=$data['login']?></span></span>
</div>
<form action="" method="post" class="form">
    <?if (!empty($data['error'])):?>
        <?foreach ($data['error'] as $error):?>
            <span class="form-container-text-subtitle"><?=$error?></span>
        <?endforeach;?>
    <?endif;?>
    <div class="form-column">
        <div class="form-row">
            <label class="form-row-label" for="password">Пароль</label>
            <input name="password" placeholder="Пароль" id="password" required class="form-row-input">
        </div>
        <input type="button" value="Сгеренировать пароль" onclick="generatePasswordIntoInputByID(10, 'password')" class="generate-password">
    </div>

    <div class="form-row">
        <label class="form-row-label" for="surname">Фамилия</label>
        <input name="surname" class="form-row-input" id="surname" placeholder="Фамилия" type="text" maxlength="32" required >
    </div>
    <div class="form-row">
        <label class="form-row-label" for="name">Имя</label>
        <input name="name" class="form-row-input" id="name" placeholder="Имя" type="text" maxlength="32" required>
    </div>
    <div class="form-row">
        <label class="form-row-label" for="patronymic">Отчество</label>
        <input name="patronymic" class="form-row-input" id="patronymic" placeholder="Отчество" type="text" maxlength="32" required>
    </div>
    <div class="form-row">
        <label class="form-row-label" for="date_of_birth">Дата рождения</label>
        <input name="date_of_birth" class="form-row-input" id="date_of_birth" placeholder="Дата рождения" type="date" required min="<?=date("Y-m-d", strtotime("-80 years"))?>" max="<?=date("Y-m-d", strtotime("-16 years"));?>">
    </div>
    <div class="form-row">
        <label class="form-row-label" for="passport">Паспорт</label>
        <input name="passport" class="form-row-input" id="passport" placeholder="Паспорт" type="number" minlength="10" maxlength="10" required>
    </div>
    <div class="form-row">
        <label class="form-row-label" for="snils">СНИЛС</label>
        <input name="snils" class="form-row-input" id="snils" placeholder="СНИЛС" type="number" minlength="11" maxlength="11" required>
    </div>
    <div class="form-row">
        <label class="form-row-label" for="inn">ИНН</label>
        <input name="inn" class="form-row-input" id="inn" placeholder="ИНН" type="number" minlength="10" maxlength="10" required>
    </div>
    <div class="form-row">
        <label class="form-row-label" for="phone_number">Номер телефона</label>
        <input name="phone_number" class="form-row-input" id="phone_number" placeholder="Номер телефона" minlength="10" type="tel" maxlength="10" required>
    </div>
<!--    <input type="file" name="profile_photo" value="Фото профиля" class="form-row" required>-->
    <input type="submit" value="Подтвердить данные" class="form-row-submit">
</form>
</section>
<script src="/js/generatePassword.js"></script>
