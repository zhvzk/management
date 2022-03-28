<?
//echo "<pre>";print_r($data);die();
?>

<section class="regular-container">
    <div class="page-title-box">
        <div class="page-title-textbox">
            <span class="page-title">Филиалы</span>
            <span class="list-box-text-container-subtitle">Всего: <?=$data['countBranches']?></span>
        </div>
        <?if ($_SESSION['id_role'] == 1):?>
        <div  class="employee-box-right-col">
            <a href="/branch/add" class="regular-red-link">Добавить</a>
        </div>
        <?endif;?>
    </div>
    <?if(!empty($_SESSION["success"])):?>
        <span class="res-success"><?=$_SESSION["success"]?></span>
        <?unset($_SESSION["success"]); endif;?>
    <?if(!empty($data['branch'])):?>
    <section class="regular-list-box-container admin-panel-right-column userlist-width">
        <div class="list-box">
            <div class="list-box-row">
                <span class="list-box-text-container-title list-box-text-container-title-width">Название</span>
                <span class="list-box-text-container-title list-box-text-container-title-width">Адрес</span>
                <span class="list-box-text-container-title list-box-text-container-title-width">Действия</span>
            </div>
            <?foreach ($data['branch'] as $key => $branch):?>
                <div class="list-box-row">
                    <!--        <td>--><?//=$key + 1?><!--</td>-->
                    <span class="list-box-text-container-text"><a href="/branch/info/<?=$branch['id']?>" class="list-box-text-container-text-link"><?=$branch['name']?></a></span>
                    <span class="list-box-text-container-text list-box-text-container-text-width"><?=$branch['address']?></span>
                    <div class="list-box-links">
                        <?if ($_SESSION['id_role'] == 1):?>
                        <span class="list-box-text-container-text"><a href="/branch/edit/<?=$branch['id']?>" class="tmp-working regular-table-cell-link">Изменить</a></span>
                        <span class="list-box-text-container-text"><a href="/branch/delete/<?=$branch['id']?>" class="list-box-row-link header-exit-link">Удалить</a></span>
                        <?else:?>
                            <span class="list-box-text-container-text"><a href="/branch/info/<?=$branch['id']?>" class="list-box-row-link header-exit-link">Просмотр</a></span>
                        <?endif;?>
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
    <?else: ?>
        <span class="list-box-text-container-subtitle">Филиалов нет</span>
    <?endif;?>
</section>

