<?

if (!empty($data['result'])) {
    echo $data['result'];
}
?>

<section class="form-container">
    <div class="form-container-text">
        <span class="form-container-text-title">Добавить филиал</span>
    </div>

        <form method="post" action="" class="form">
            <div class="form-row">
                <label class="form-row-label" for="name">Название</label>
                <input type="text" placeholder="Название" name="name" maxlength="128" required class="form-row-input" id="name">
            </div>
            <div class="form-row">
                <label class="form-row-label" for="address">Адрес</label>
                <input type="text" placeholder="Адрес" name="address" maxlength="128" required class="form-row-input" id="address">
            </div>
            <div class="form-row">
                <label class="form-row-label" for="description">Описание</label>
                <input type="text" placeholder="Описание" name="description" maxlength="128" required class="form-row-input" name="description">
            </div>
                <input type="submit" value="Создать филиал" class="form-row-submit">
        </form>

</section>