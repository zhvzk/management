<?//print_r($_SESSION)?>

<section class="admin-panel">
    <div class="admin-panel-left-column">
        <div class="hello-box">
            <span class="hello-box-title">Рады видеть вас, <?=$data['name']?></span>
            <span class="hello-box-time"> Сегодня <?=date("d.m.Y")?></span>
        </div>
        <?if($_SESSION['id_role'] == 1):?>
            <div class="admin-panel-requests-container">
                <span class="page-title admin-pane-title-padding">Новые заявки</span>
                <?if(!empty($data['cards']['requests'])):?>
                    <div class="admin-panel-requests-box">
                        <?foreach ($data['cards']['requests'] as $key=>$request):?>
                            <div class="request-card">
                                <span class="request-card-title"><?=$request['title']?></span>
                                <span class="request-card-subtitle"><?=$request['date']?></span>
                                <span class="request-card-subtitle">Заявок: <span class="request-card-text-accent"><?=$request['count']?></span></span>
                                <a href="/proposal/view/<?=$request['id_proposal']?>" class="request-card-link">Посмотреть</a>
                            </div>
                        <?endforeach;?>
                    </div>
                    <a href="/proposal/list" class="request-box-link">Открыть все</a>
                <?else:?>
                    <span class="request-card-title">Заявок нет</span>

                <?endif;?>
            </div>
        <?endif;?>
    </div>
    <section class="regular-list-box-container admin-panel-right-column">
        <span class="regular-list-title">Новые смены</span>

        <?if(!empty($data['cards']['proposals'])):?>
            <div class="list-box">
                <?foreach ($data['cards']['proposals'] as $key=>$proposal):?>
                    <div class="list-box-row">
                        <div class="list-box-text-container list-box-text-container-text-align">
                            <span class="list-box-text-container-title list-box-text-container-text-align"><?=$proposal['title']?>  </span>
                            <?if(!empty($proposal['surname'])):?>
                            <span class="list-box-text-container-subtitle">Создано: <?=$proposal['surname']?> <?=$proposal['name']?> <?=$proposal['patronymic']?></span>
                        <?endif;?>
                        </div>
                        <a href="/proposal/view/<?=$proposal['id_proposal']?>"  class="list-box-row-link">Посмотреть</a>
                    </div>
                <?endforeach;?>
                <a href="/proposal/list" class="list-box-link">Открыть все</a>
            </div>
        <?else:?>
            <span  class="list-box-text-container-title">Смен еще нет, но вы можете их создать</span>
            <span><a href="/proposal/add" class="request-box-link">Создать</a></span>
        <?endif;?>
    </section>
</section>
