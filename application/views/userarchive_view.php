<?//echo "<pre>"; print_r($data);die();?>

<section class="page-title-box">
    <section class="page-title-textbox">

        <span class="page-title"><?=$data['user']['surname']?> <?=$data['user']['name']?> <?=$data['user']['patronymic']?></span>
        <span class="list-box-text-container-subtitle">Архив</span>
    </section>
</section>
<?if(!empty($_SESSION['error'])):?>
    <span class="page-title-textbox-text page-title-textbox-text-margin margin-left-default"><?=$_SESSION['error'];?></span>

    <?unset($_SESSION['error']);endif;?>


<form class="archive-form" method="post" action="">
    <?if($data['action'] == 'user'):?>
        <div class="box-checkbox">
            <?foreach ($data['params'] as $param):?>
                <div class="box-checkbox-row">
                    <input type="checkbox" value="<?=$param['id']?>" name="status[]" id="params_<?=$param['id']?>" class="box-checkbox-row-cb">
                    <label for="params_<?=$param['id']?>" class="box-checkbox-label"><?=$param['name']?></label>
                </div>
            <?endforeach;?>

        </div>
    <?endif;?>

    <div class="date-picker">
        <span class="date-picker-text">С</span>
        <input class="date-picker-input" type="date" value="<?=date("Y-m-d", strtotime("first day of -1 month"));?>" name="startDate" required>
        <span class="date-picker-text date-picker-padding">По</span>
        <input class="date-picker-input" type="date" value="<?= date("Y-m-d", strtotime("now"))?>" name="endDate" required>
    </div>

    <input type="submit" class="archive-form-submit" value="Поиск">
</form>

<?
if (!empty($data['requests'])):?>
    <section class="archive-main-section">
        <?if ($data['requests'][0]['id'] != 0):?>
            <span class="page-title-textbox-text page-title-textbox-text-margin">Найдено: <?=count($data['requests'])?></span>
            <section class="regular-list-box-container admin-panel-right-column userlist-width">
                <div class="list-box">
                    <div class="list-box-row">
                        <span class="list-box-text-container-title list-box-text-container-title-width list-box-text-container-text-width list-box-text-container-text-centered">Название</span>
                        <span class="list-box-text-container-title list-box-text-container-title-width list-box-text-container-text-width list-box-text-container-text-centered">Дата</span>
                        <span class="list-box-text-container-title list-box-text-container-title-width list-box-text-container-text-width list-box-text-container-text-centered">Статус</span>
                    </div>
                    <?foreach ($data['requests'] as $key => $request):?>
                        <div class="list-box-row">
                            <span class="list-box-text-container-text list-box-text-container-text-width"><a href="/proposal/view/<?=$request['id']?>" class="list-box-text-container-text-link"><?=$request['title']?></a></span>
                            <span class="list-box-text-container-text list-box-text-container-text-width list-box-text-container-text-centered"><?=$request['date']?></span>
                            <div class="list-box-links">
                                <span class="list-box-text-container-text list-box-text-container-text-width list-box-text-container-text-centered">
                                    <?if($data['action'] == 'branch'):?>

                                        <?if ($request['is_closed'] == 1):?>
                                            Смена закрыта
                                        <?else:?>
                                            Смена не закрыта
                                        <?endif;?>


                                    <?elseif($data['action'] == 'user'):?>
                                        <?=$request['status_name']?>
                                    <?endif;?>
                                </span>
                                <!--                                <span class="list-box-text-container-text list-box-text-container-text-width">--><?//=$request['status_name']?><!--</span>-->
                                <!--                                <span class="list-box-text-container-text list-box-text-container-text-width">--><?//=$request['status_name']?><!--</span>-->
                            </div>
                        </div>
                    <?endforeach;?>
                </div>
            </section>
        <?else: ?>
            <span class="page-title-textbox-text page-title-textbox-text-margin">Ничего не найдено</span>
        <?endif;?>
    </section>
<?else: ?>
<!--    <span class="page-title-textbox-text page-title-textbox-text-margin">Ничего не найдено</span>-->
<?endif;?>
