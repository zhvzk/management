<section class="admin-panel">
<?//print_r($data); die();?>
    <section class="admin-panel-left-column">
        <section class="proposal-card">
            <div class="proposal-card-title-box">
                <span class="proposal-card-title"><?=$data['proposal']['title']?></span>
                <?if($data['proposal']['is_closed'] == 0):?>
                    <!--                --><?//echo "<pre>";print_r($data); die();?>
                    <span class="proposal-card-status active">Активна</span>
                <?else:?>
                    <span class="proposal-card-status closed">Закрыта </span>
                <?endif;?>
            </div>
            <span class="proposal-card-text">Описание: <?=$data['proposal']['description']?></span>
            <span class="proposal-card-text">Дата: <?=$data['proposal']['date']?></span>
            <span class="proposal-card-text">Рабочий день: <?=$data['proposal']['wd_start']?>-<?=$data['proposal']['wd_end']?></span>
            <span class="proposal-card-text">Обед: <?=$data['proposal']['lunch_start']?>-<?=$data['proposal']['lunch_end']?></span>
            <span class="proposal-card-text">Ставка в час: <?=$data['proposal']['rate_per_hour']?></span>
            <span class="proposal-card-text">Адрес: <a href="/branch/info/<?=$data['proposal']['id_branch']?>" class="list-box-text-container-text-link"><?=$data['proposal']['address']?></a></span>
            <?if($_SESSION['id_role'] == 1): ?>
                <a href="/proposal/delete/<?=$data['proposal']['id']?>" class="header-exit-link">Удалить</a>
            <?endif;?>
        </section>
        <?if ((($_SESSION['id_role'] == 1) || ($_SESSION['id_role'] == 2)) && ($data['proposal']['is_closed'] == 0)):?>
            <form method="post" class="form form-proposal">
                <span class="form-text-title">Отправить смену</span>
                <!--                <label class="form-row-label" for="employee">Филиал</label>-->
                <select required class="form-proposal-input" id="employee" name="employee">
                    <option disabled>Выберите сотрудника</option>
                    <?if ((!empty($data['employees']))&&(count($data['employees']))):?>
                        <?foreach ($data['employees'] as $employee):?>
                            <option value="<?=$employee['id']?>"><?=$employee['surname']?> <?=$employee['name']?> <?=$employee['patronymic']?> </option>
                        <?endforeach;?>
                    <?endif;?>
                    <?if (!empty($data['res'])) {
                        echo "<span class='form-text-subtitle'>".$data['res'] ."</span>";
                    }?>
                </select>
                <input type="submit" class="form-row-submit form-proposal" value="Отправить смену">
            </form>
        <?endif;?>
    </section>
    <section class="proposal-right-column">
        <?if($data['proposal']['is_closed'] == 0):?>
        <span class="proposal-count">Отправлено заявок: <span class="proposal-count-accent"><?=$data['requests']['count']?></span></span>

        <?if($_SESSION['id_role'] == 3): ?>
            <!--            ->num_rows == 0-->

            <?if(empty($data['isRequestExists'])):?>
                <a class="form-row-submit form-proposal" href="/user/addrequest/<?=$_SESSION['id']?>_<?=$data['proposal']['id']?>" style="text-decoration: none">Оставить заявку</a>
            <?else:?>
                <!--            --><?//print_r($data['isRequestExists']['id_status']); die();?>
                <?if(($data['isRequestExists']['id_status'] == 2)||($data['isRequestExists']['id_status'] == 5)):?>
                    <a class="form-row-submit form-proposal button-active"  href="/proposal/takeback/<?=$data['proposal']['id']?>_<?=$_SESSION['id']?>">Отменить заявку</a>
                <?elseif (($data['isRequestExists']['id_status'] != 2) && ($data['isRequestExists']['id_status'] != 5)):?>
                    <span class="proposal-count">Ваша заявка была отменена</span>

                <?endif;?>
            <?endif;?>
        <?endif;?>
        <?if (($_SESSION['id_role'] == 1) || ($_SESSION['id_role'] == 2)):?>

        <section class="regular-list-box-container admin-panel-right-column oneproposal-width">
            <?if((!empty($data['requests']['employeesRequests'])&& (count($data['requests']['employeesRequests'])))):?>
                <div class="list-box">
                    <div class="list-box-row">
                        <span class="list-box-text-container-title list-box-text-container-title-width">ФИО</span>
                        <span class="list-box-text-container-title list-box-text-container-title-width">Действия</span>
                    </div>
                    <?foreach ($data['requests']['employeesRequests'] as $key => $user):?>
                        <div class="list-box-row">
                            <span class="list-box-text-container-text"><a href="/user/info/<?=$user['id']?>" class="list-box-text-container-text-link"><? echo $user['surname']." ".  $user['name']." ". $user['patronymic']?></a></span>
                            <div class="list-box-links">
                                <?if ($user['id_status'] != 5):?>
                                    <span><a class="list-box-row-link" href="/proposal/submit/<?=$data['proposal']['id']?>_<?=$user['id']?>">Подтвердить</a></span>
                                <?endif;?>
                                <span><a class="list-box-row-link" href="/proposal/cancel/<?=$data['proposal']['id']?>_<?=$user['id']?>">Отменить</a></span>
                            </div>
                        </div>
                    <?endforeach;?>
                </div>

            <?else:?>
                <span class="proposal-count">Нет заявок</span>
            <?endif;?>
            <?endif;?>
            <?else:?>
                <span class="proposal-count">Смена закрыта <?=$data['user']['surname']?> <?=$data['user']['name']?> <?=$data['user']['patronymic']?></span>
            <?endif;?>
        </section>
    </section>
</section>