<?if(!empty($_SESSION["success"])):?>
    <span class="res-success"><?=$_SESSION["success"]?></span>
    <?unset($_SESSION["success"])?>
<?elseif (!empty($_SESSION["error"])):?>
    <span class="res-error"><?=$_SESSION["error"]?></span>
    <?unset($_SESSION["error"])?>
<?endif;?>
<section class="regular-container">
    <div class="page-title-box">
        <div class="page-title-textbox">
            <span class="page-title">Сотрудники</span>
            <span class="list-box-text-container-subtitle">Всего: <?=$data['countUsers']?></span>
        </div>
        <div  class="employee-box-right-col">
            <a href="/user/add" class="regular-red-link">Добавить</a>
        </div>
    </div>
    <section class="regular-list-box-container admin-panel-right-column userlist-width">
        <div class="list-box">
            <div class="list-box-row">
                <!--        <td>№</td>-->
                <span class="list-box-text-container-title list-box-text-container-title-width">ФИО</span>
                <span class="list-box-text-container-title list-box-text-container-title-width">Должность</span>
                <span class="list-box-text-container-title list-box-text-container-title-width">Действия</span>
            </div>
            <?foreach ($data['users'] as $key => $user):?>
                <div class="list-box-row">
                    <!--        <td>--><?//=$key + 1?><!--</td>-->
                    <span class="list-box-text-container-text"><a href="/user/info/<?=$user['id']?>" class="list-box-text-container-text-link"><? echo $user['surname']." ".  $user['name']." ". $user['patronymic']?></a></span>
                    <span class="list-box-text-container-text"><?=$user['role_name']?></span>
                    <div class="list-box-links">
                        <span class="list-box-text-container-text"><a href="/user/edit/<?=$user['id']?>" class="list-box-row-link">Изменить</a></span>
                        <span class="list-box-text-container-text"><a href="/user/delete/<?=$user['id']?>" class="list-box-row-link header-exit-link">Удалить</a></span>
                    </div>
                </div>
            <?endforeach;?>
        </div>
    </section>
    <section class="pagination-container">
        <?foreach ($data['pagination'] as $key => $value){
            echo $value;
        }
        ?>
    </section>
</section>
