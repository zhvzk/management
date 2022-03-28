<div class="regular-container">
    <section>
<!--        --><?//echo "<pre>"; print_r($data);die();?>

        <!--    <div class="page-title-box">-->
        <div class="textbox-align textbox-padding">
            <span class="page-title"><?=$data['user']['surname']?> <?=$data['user']['name']?> <?=$data['user']['patronymic']?></span>
            <span class="list-box-text-container-subtitle"><?=$data['role']['name']?></span>
        </div>
        <!--    </div>-->

        <?if(($data['user']['id_role'] == 3)):?>
            <?if (($_SESSION['id_role'] == 3)&&(!empty($data['suggested']))):?>
                <span>Работодатель предложил вам выйти на смену!</span>
<!--                <span class="list-box-text-container-text list-box-text-container-text-width list-box-text-container-text-align">-->
                <ul >
                    <?foreach ($data['suggested'] as $key => $suggestion):?>
                        <li><a href="/proposal/view/<?=$suggestion['id_proposal']?>" class="list-box-text-container-text-link"><?=$suggestion['title']?></a>
                            <span class="list-box-text-container-subtitle"><?=$suggestion['date']?></span><br>
                            <a class="list-box-row-link"  href="/proposal/view/<?=$suggestion['id_proposal']?>">Просмотр</a></li>
                    <?endforeach;?>
                </ul>


<!--                </span>-->

            <?endif;?>

                <section class="page-title-textbox textbox-align textbox-margin">
                    <span class="section-subtitle">Статистика <span class="section-subtitle-accent"><?= date("d.m.Y", strtotime("first day of -1 month"));?> - <?= date("d.m.Y", strtotime("first day of +1 month"));?></span></span>
                    <span class="list-box-text-container-subtitle list-box-text-container-subtitle-width">Всего заявок этот период: <span><?= $data['count']?></span></span>
                </section>
            <?  if ((!empty($data['request']))&&(count($data['request']))):?>
        <div class="userpage-right-column">
                    <?foreach ($data['request'] as $type => $request):?>
                        <section class="userpage-list-box-container ">
                        <span class="userpage-title-regular">
                            <?if ($type == 'active'):?>
                                Подтвержденные
                            <?elseif ($type == 'canceled'):?>
                                Отмененные
                            <?elseif ($type == 'waiting'):?>
                                Ожидающие подтверждения
                            <?endif;?>
                             </span>

                            <!--                    --><?//echo "<pre>request\n"; print_r($request); echo "</pre>";?>
                            <?foreach ($request as $key => $requestData):?>
                                <div class="list-box-row">
                                    <!--                        --><?//echo "<pre>requestData\n"; print_r($requestData); echo "</pre>";?>
                                    <?if ($requestData['id_request'] !=0):?>
                                        <span class="list-box-text-container-text list-box-text-container-text-width list-box-text-container-text-align">

                                            <a href="/proposal/view/<?=$requestData['id_proposal']?>" class="list-box-text-container-text-link"><?=$requestData['title']?></a><br>
                                            <span class="list-box-text-container-subtitle"><?=$requestData['date']?></span>
                                        </span>
                                        <?if($type == 'waiting'):?>
                                            <a class="list-box-row-link" href="/proposal/takeback/<?=$requestData['id_proposal']?>_<?=$_SESSION['id']?>">Отменить</a><br>
                                            <!--                                            <a class="list-box-row-link"  href="/proposal/cancel/--><?//=$requestData['id']?><!--">Отменить</a><br>-->
                                        <?endif;?>
                                        <a class="list-box-row-link"  href="/proposal/view/<?=$requestData['id_proposal']?>">Просмотр</a>
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
            <?endif;?>
            <a class="list-box-link link-archive" href="/user/archive/<?=$data['user']['id']?>">Архив заявок</a>
        <?endif;?>

    </section>
</div>

