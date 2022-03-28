<?//echo "<pre>"; print_r($data);die();?>
<section class="regular-container">
    <div class="page-title-box">
        <div class=" page-title-textbox page-title-textbox-width">
            <span class="page-title"><?=$data['branchInfo']['name']?></span>
            <span class="list-box-text-container-subtitle  list-box-text-container-subtitle-width"><?=$data['branchInfo']['address']?></span>
        </div>
        <?if ($_SESSION['id_role'] == 1):?>
            <div  class="employee-box-right-col list-box-text-container">
                <a href="/branch/edit/<?=$data['branchInfo']['id']?>" class="regular-red-link  zero-padding">Изменить</a>
                <a href="/branch/delete/<?=$data['branchInfo']['id']?>" class="list-box-text-container-text-link">Удалить</a>
            </div>
        <?endif;?>
    </div>


    <?if ($_SESSION['id_role'] == 1):?>
        <?if((!empty($data['proposals']))&&(count($data['proposals']))):?>
            <section class="archive-main-section">
                <span class="proposal-count">Активные смены</span>

                <!--            <span class="proposals-count">Всего: --><?//=$data['proposals']['countProposals']?><!--</span>-->
                <section class="regular-list-box-container admin-panel-right-column userlist-width">
                    <div class="list-box">
                        <div class="list-box-row">
                            <span class="list-box-text-container-title list-box-text-container-title-width list-box-text-container-text-width list-box-text-container-text-centered">Название</span>
                            <span class="list-box-text-container-title list-box-text-container-title-width list-box-text-container-text-width list-box-text-container-text-centered">Дата</span>
                            <span class="list-box-text-container-title list-box-text-container-title-width list-box-text-container-text-width list-box-text-container-text-centered">Действие</span>
                        </div>

                        <?foreach ($data['proposals']as $key => $request):?>
                            <div class="list-box-row">
                                <span class="list-box-text-container-text list-box-text-container-text-width"><a href="/proposal/view/<?=$request['id_proposal']?>" class="list-box-text-container-text-link"><?=$request['title']?></a></span>
                                <span class="list-box-text-container-text list-box-text-container-text-width list-box-text-container-text-centered"><?=$request['date']?></span>
                                <div class="list-box-links">
                                    <span class="list-box-text-container-text list-box-text-container-text-width   list-box-text-container-text-centered"><a href="/proposal/view/<?=$request['id']?>" class="list-box-text-container-text-link">Посмотреть</a></span>
                                </div>
                            </div>
                        <?endforeach;?>
                    </div>
                </section>
            </section>


        <?else:?>
            <span class="list-box-text-container-text">В этом филиале нет активных смен</span>
        <?endif;?>
        <div class="branch-info-row">
            <?  if ((!empty($data['managers']))&&(count($data['managers']))):?>
                <?foreach ($data['managers'] as $type => $branch):?>
                    <section class="regular-list-box-container admin-panel-right-column">

        <span class="userpage-title-regular">
            <?if ($type == 'thisBranch'):?>
                Прикрепленные руководители
            <?else:?>
                Остальные
            <?endif;?>
        </span>
                        <div class="list-box">
                            <?foreach ($branch as $key => $manager):?>
                                <?if ($manager['id'] !=0):?>
                                    <div class="list-box-row">
                                        <div class="list-box-text-container">
                                            <a href="/user/info/<?=$manager['id']?>"  class="list-box-text-container-text-link"><?=$manager['surname']?> <?=$manager['name']?> <?=$manager['patronymic']?></a>
                                        </div>
                                        <div>
                                            <?if ($type == 'thisBranch'):?>
                                                <a href="/user/unattach/<?=$data['branchInfo']['id']?>_<?=$manager['id']?>"  class="list-box-row-link">Открепить</a>
                                            <?else:?>
                                                <a href="/user/attach/<?=$data['branchInfo']['id']?>_<?=$manager['id']?>"  class="list-box-row-link">Прикрепить</a>
                                            <?endif;?>

                                        </div>


                                    </div>

                                <?else:  echo "<span>Нет ни одного менеджера</span>"; break;?>
                                <?endif;?>
                            <?endforeach;?>
                        </div>
                    </section>
                <?endforeach;?>
            <?endif;?>


        </div>
    <?endif;?>

    <!--    --><?//if($_SESSION['id_role'] == 3):?>


    <?if (($_SESSION['id_role'] == 1)||($_SESSION['id_role'] == 2)):?>
        <!--        <div class="admin-panel-requests-container">-->
        <!--            <span class="page-title admin-pane-title-padding">Новые заявки</span>-->
        <!--            --><?//if(!empty($data['proposals'])):?>
        <!--                <div class="admin-panel-requests-box">-->
        <!--                    --><?//foreach ($data['proposals'] as $key=>$request):?>
        <!--                        <div class="request-card margin">-->
        <!--                            <span class="request-card-title ">--><?//=$request['title']?><!--</span>-->
        <!--                            <span class="request-card-subtitle">--><?//=$request['date']?><!--</span>-->
        <!--                            <span class="request-card-subtitle">Заявок: <span class="request-card-text-accent">--><?//=$request['count']?><!--</span></span>-->
        <!--                            <a href="/proposal/view/--><?//=$request['id_proposal']?><!--" class="request-card-link">Посмотреть</a>-->
        <!--                        </div>-->
        <!--                    --><?//endforeach;?>
        <!--                </div>-->
        <!--                <a href="/branch/proposals/--><?//=$data['branchInfo']['id']?><!--" class="request-box-link">Открыть все</a>-->
        <!---->
        <!--            --><?//else:?>
        <!--                <span class="request-card-title">Заявок нет</span>-->
        <!--            --><?//endif;?>
        <a href="/branch/archive/<?=$data['branchInfo']['id']?>" class="request-box-link request-box-link-red">Архив смен</a>
        <a href="/proposal/add" class="request-box-link">Создать смену</a>
        <!--        </div>-->
    <?endif;?>
    <!--        --><?//endif;?>
</section>