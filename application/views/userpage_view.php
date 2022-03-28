<?//echo "<pre>";
//print_r($data   );
//die();?>
<?error_reporting(0);?>
<!---->
<div class="regular-container">
    <div class="page-title-box">
        <div class=" page-title-textbox">
            <span class="page-title"><?=$data['user']['surname']?> <?=$data['user']['name']?> <?=$data['user']['patronymic']?></span>
            <span class="list-box-text-container-subtitle"><?=$data['role']['name']?></span>
        </div>

        <?if ($_SESSION['id_role'] == 1):?>
            <div  class="employee-box-right-col">
                <a href="/user/edit/<?=$data['user']['id']?>" class="regular-red-link">Изменить</a>
            </div>

        <?endif;?>
    </div>

    <?if(($data['user']['id_role'] == 2)):?>
        <?  if ((!empty($data['branches']))&&(count($data['branches']))):?>
            <div class="userpage-info-row">
                <?foreach ($data['branches'] as $type => $branch):?>
                    <section class="regular-list-box-container admin-panel-right-column">
                        <span class="userpage-title-regular">
                            <?if ($type == 'attachedTo'):?>
                                Прикрепленные филиалы
                            <?else:?>
                                Остальные
                            <?endif;?>
                        </span>
                        <div class="list-box-row">
                            <span class="list-box-text-container-title list-box-text-container-title-width">Название</span>
                            <span class="list-box-text-container-title list-box-text-container-title-width">Адрес</span>
                            <span class="list-box-text-container-title list-box-text-container-title-width">Действия</span>
                        </div>
                        <?foreach ($branch as $key => $branchData):?>
                            <?if ($branchData['id'] !=0):?>
                                <div class="list-box-row">

                                    <span class="list-box-text-container-text"><a href="/user/info/<?=$branchData['id']?>"  class="list-box-text-container-text-link"><?=$branchData['name']?></a></span>

                                    <span class="list-box-text-container-text"><?=$branchData['address']?></span>



                                    <?if ($type == 'attachedTo'):?>
                                        <span class="list-box-text-container-text"> <a href="/user/unattach/<?=$branchData['id']?>_<?=$data['user']['id']?>"  class="list-box-row-link">Открепить</a></span>
                                    <?else:?>
                                        <span class="list-box-text-container-text"> <a href="/user/attach/<?=$branchData['id']?>_<?=$data['user']['id']?>"   class="list-box-row-link">Прикрепить</a></span>
                                    <?endif;?>
                                </div>
                            <?else:  echo "<span>Нет ни одного филиала</span>"; break;?>
                            <?endif;?>
                        <?endforeach;?>

                    </section>

                <?endforeach;?>
            </div>
        <?else:
            echo "<span>Нет ни одного филиала</span>";
        endif;?>

    <?endif;?>
<!--    <section>-->
        <?if(($data['user']['id_role'] == 3)):?>

        <?  if ((!empty($data['request']))&&(count($data['request']))):?>
            <section class="page-title-textbox ">
                <span class="page-title-textbox-text">Статистика <span><?= date("d.m.Y", strtotime("now"));?> - <?= date("d.m.Y", strtotime("first day of +1 month"));?></span></span>
                <!--            --><?//echo "<pre>";print_r($data);die();?>

                <span class="list-box-text-container-subtitle list-box-text-container-subtitle-width">Всего заявок этот период: <span><?= $data['request']['count']?></span></span>
            </section>
            <div class="userpage-info-row">
                <!--    <section>-->
                <!--        --><?//echo "<pre>";print_r($data['request']); die();?>
                <?foreach ($data['request'] as $type => $request):?>

                    <!--        <section class="userpage-info-row">-->
                    <section class="regular-list-box-container admin-panel-right-column">
                        <span class="userpage-title-regular">
                            <?if ($type == 'active'):?>
                                Подтвержденные
                            <?elseif ($type == 'archive'):?>
                                Завершенные
                            <?elseif ($type == 'canceled'):?>
                                <span>Отмененные</span>
                            <?endif;?>
                             </span>

                        <!--                    --><?//echo "<pre>request\n"; print_r($request); echo "</pre>";?>
                        <?foreach ($request as $key => $requestData):?>
                            <div class="list-box-row">
                                <!--                        --><?//echo "<pre>requestData\n"; print_r($requestData); echo "</pre>";?>
                                <?if ($requestData['id_request'] !=0):?>
                                    <span class="list-box-text-container-text list-box-text-container-text-width list-box-text-container-text-align">

                                            <a href="/proposal/view/<?=$requestData['id']?>" class="list-box-text-container-text-link"><?=$requestData['title']?></a><br>
                                            <span class="list-box-text-container-subtitle"><?=$requestData['date']?></span>
                                        </span>
                                    <a class="list-box-row-link"  href="/proposal/view/<?=$requestData['id']?>">Просмотр</a>
                                <?else:  echo "<span>Нет ни одной заявки</span> </div>"; break;?>
                                <?endif;?>
                            </div>
                        <?endforeach;?>
                        <!--                            <div class="list-box-row">-->
                        <!--                                <a class="list-box-link" href="/user/archive/--><?//=$data['user']['id']?><!--/--><?//=$type?><!--">Открыть все</a>-->
                        <!--                            </div>-->
                    </section>

                <?endforeach;?>

            </div>
        <?else:?>
<!--    </section>-->
<?endif;?>
<?endif;?>

<!--    </section>-->
    <a class="list-box-link" href="/user/archive/<?=$data['user']['id']?>">Архив заявок</a>

</div>

