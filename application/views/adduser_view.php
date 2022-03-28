<secton class="form-container">
    <div class="form-container-text">
        <span class="form-container-text-title">Добавить сотрудника</span>
        <span class="form-container-text-subtitle">Пожалуйста, обязательно проверьте правильность ввода</span>
    </div>
    <form method="post" action="" class="form">
        <div class="form-row">
            <label class="form-row-label" for="email">Почта</label>
            <input type="email" placeholder="Введите e-mail" name="email" maxlength="32" required id="email" class="form-row-input">
        </div>
        <div class="form-row">
            <label for="role" class="form-row-label">Роль</label>
            <select name="role" required class="form-row-input" id="role">
                <option disabled>Выберите роль</option>
                <?foreach ($data['role'] as $key => $role): ?>
                    <option value="role<?=$role['id']?>"><?=$role['name']?></option>
                <?endforeach; ?>
            </select>
        </div>
        <div class="form-row">
            <label class="form-row-label" for="salary_increase">Надбавка</label>
            <input maxlength="11" placeholder="Надбавка" value="1" required name="salary_increase" class="form-row-input" id="salary_increase">
        </div>
        <input type="submit" value="Создать аккаунт" class="form-row-submit">
    </form>
</secton>