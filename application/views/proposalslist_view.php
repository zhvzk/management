<?if(!empty($_SESSION["success"])):?>
    <span class="res-success"><?=$_SESSION["success"]?></span>
    <?unset($_SESSION["success"])?>
<?elseif (!empty($_SESSION["error"])):?>
    <span class="res-error"><?=$_SESSION["error"]?></span>
    <?unset($_SESSION["error"])?>
<?endif;?>

<?//echo "<pre>"; print_r($data);die();?>
<section class="regular-container">
    <div class="page-title-box">
        <div class="page-title-textbox">
            <span class="page-title">Активные смены</span>
            <?if(!empty($data['branch'])):?>
            <span class="proposal-count">Адрес филиала: <?=$data['branch']['address']?></span>
            <?endif;?>
            <span class="list-box-text-container-subtitle">Всего: <?=$data['countProposals']?></span>
        </div>
        <?if (($_SESSION['id_role'] == 1) || ($_SESSION['id_role'] == 2)):?>
        <div  class="employee-box-right-col">
            <a href="/proposal/add" class="regular-red-link">Добавить</a>
        </div>
        <?endif;?>
    </div>
        <section class="archive-main-section">
            <!--            --><?//if ($data['requests'][0]['id'] != 0):?>
            <!--                <span class="page-title-textbox-text page-title-textbox-text-margin">Найдено: --><?//=count($data['requests'])?><!--</span>-->
            <section class="regular-list-box-container admin-panel-right-column userlist-width">
                <div class="list-box">
                    <div class="list-box-row">
                        <span class="list-box-text-container-title list-box-text-container-title-width list-box-text-container-text-width list-box-text-container-text-centered">Название</span>
                        <span class="list-box-text-container-title list-box-text-container-title-width list-box-text-container-text-width list-box-text-container-text-centered">Дата</span>
                        <span class="list-box-text-container-title list-box-text-container-title-width list-box-text-container-text-width list-box-text-container-text-centered">Действие</span>
                    </div>
                    <?foreach ($data['proposal'] as $key => $request):?>
                        <div class="list-box-row">
                            <!--        <td>--><?//=$key + 1?><!--</td>-->

                            <span class="list-box-text-container-text list-box-text-container-text-width"><a href="/proposal/view/<?=$request['id']?>" class="list-box-text-container-text-link"><?=$request['title']?></a></span>
                            <span class="list-box-text-container-text list-box-text-container-text-width list-box-text-container-text-centered"><?=$request['date']?></span>
                            <div class="list-box-links">
                                <span class="list-box-text-container-text list-box-text-container-text-width   list-box-text-container-text-centered"><a href="/proposal/view/<?=$request['id']?>" class="list-box-text-container-text-link">Посмотреть</a></span>
                            </div>
                        </div>
                    <?endforeach;?>
                </div>
            </section>
            <!--            --><?//else: ?>
            <!--                <span class="page-title-textbox-text page-title-textbox-text-margin">Ничего не найдено</span>-->
            <!--            --><?//endif;?>
        </section>
        <!--    <table>-->
        <!--        <tr>-->
        <!--            <td>Смена</td>-->
        <!--            <td>Действия</td>-->
        <!--        </tr>-->
        <!--        --><?//foreach ($data['proposal'] as $key => $proposal):?>
        <!--            <tr>-->
        <!--                <td>-->
        <!--                    <span>--><?//=$proposal['title']?><!--</span>-->
        <!--                </td>-->
        <!--                <td><a href="/proposal/view/--><?//=$proposal['id']?><!--">Посмотреть</a></td>-->
        <!--            </tr>-->
        <!--        --><?//endforeach;?>
        <!--    </table>-->

        <?
        if (!empty($data['pagination'])) :?>
            <section class="pagination-container">
                <? foreach ($data['pagination'] as $key => $value){
                    echo $value;
                }?>
            </section>

        <?endif;?>
</section>
