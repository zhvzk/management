
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <title>WM Главная</title>
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&display=swap" rel="stylesheet">
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<?
$urlReg = substr($_SERVER['REQUEST_URI'], 0, 18);?>
<?if(!empty($_SESSION['id'])&&($_SERVER['REQUEST_URI'] != '/user/login')):?>
    <body>
    <div id="wrapper">

        <div id="header">
            <span class="logo"><a class="logo" href="/">up to you<span class="logo-accent">.</span></a></span>
            <menu class="header-menu">
                <?if($_SESSION['id_role'] == 1):?>
                <li class="header-menu-item"><a href="/user/list" class="header-menu-item-link">Сотрудники</a></li>

                <?endif;?>
                <li class="header-menu-item header-menu-item-middle"><a href="/branch/list" class="header-menu-item-link">Филиалы</a></li>
                <li class="header-menu-item"><a href="/proposal/list" class="header-menu-item-link">Смены</a></li>
            </menu>
            <span class="header-exit"><a href="/user/logout" class="header-exit-link">Выход</a></span>
        </div>



        <div id="content">
            <div class="box" >
                <?php include 'application/views/'.$content_view; ?>
            </div>
        </div>
    </div>

    </div>
    </body>

<?elseif ((empty($_SESSION['id']))&&(($_SERVER['REQUEST_URI'] == '/user/login')||($urlReg == "/user/registration"))):?>
    <div id="content">
        <div class="box">

            <?php include 'application/views/'.$content_view; ?>
        </div>
    </div>
<?elseif ((empty($_SESSION['id']))&&(($_SERVER['REQUEST_URI'] != '/user/login')||($urlReg == "/user/registration"))):?>
<span class="page-title">У вас нет доступа к этой странице. Пожалуйста, авторизуйтесь.</span><br>
<a  class="regular-red-link" href="/user/login">Авторизация</a>
<!--    --><?php //echo $_SERVER['REQUEST_URI'];?>
<?// header("Location: /user/login"); die();?>
<?endif;?>
<?//if ((empty($_SESSION['id']))&&($_SERVER['HTTP_REFERER'] != 'http://'.$_SERVER['HTTP_HOST'].'/user/login')) {
//    header("Location: /user/login");
//    die();
//}?>
<?//if (empty($_SESSION['id']))   'application/views/login_view.php'; die();?>
</html>


