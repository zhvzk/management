<?php
class Controller_Branch extends Controller
{
    public function __construct()
    {
        $this->model = new Model_Branch();
        parent::__construct();
    }

    /*
    Генерация филиалов
      Для использования раскомментировать эту функцию + autoAdd в Model_Branch
    public function action_autoadd($quantity) {
        if ($this->model->isUserAuthorizedAsAdmin()) {
            $this->model->autoAdd($quantity);
        }
    }
    */
    //Список филиалов
    public function action_list($page)
    {
        if (!empty($_SESSION["id"])) { //если пользователь авторизован
            if (!isset($page) || empty($page)) { //если страница не указана, ей присваивается 1
                $page = 1;
            }
            if ($this->model->isUserAuthorizedAsManager()) {
                $idBranchManager = $this->model->getIdBranchManager(htmlspecialchars($_SESSION['id']));
                $data = $this->model->getAllBranches($page, 'manager', $idBranchManager); //получение всех филиалов менеджера со страницы $page

            } else {
                $data = $this->model->getAllBranches($page); //получение всех филиалов со страницы $page
            }

            $this->view->generate('branchlist_view.php', 'template_view.php', $data);
        }
//        }
    }

    //Добавление филиала
    public function action_add()
    {
        if (!empty($_POST)) {
            if ($array = $this->model->securityCheck($_POST, true)) { 
                $this->model->addBranch($array); //добавляется филиал
                header("Location: /branch/list/1");
                die();
            }
        }
        $this->view->generate('addbranch_view.php', 'template_view.php', $data);
    }
    //Редактирование 
    public function action_edit($id)
    {
        if (!empty($_SESSION["id"])) { //если пользователь авторизован
            if ($this->model->isUserAuthorizedAsAdmin()) { //если пользователь авторизован под админом
                if ((isset($id)) && (!empty($id))) {
                    $params = '`name`, `description`, `address`';
                    $data['branchData'] = $this->model->getMultiplyBranchParamsById($id, $params); //получение указанных параметров филиала
                    if (!empty($_POST)) { //если отправлен пост
                        $array = $this->model->securityCheck($_POST, true);
                        $diff = $this->model->diffBetweenArrays($data['branchData'], $array); //измененные данные
                        if (!empty($diff)) { //если в форме данные изменены
                            $this->model->updateBranchData($id, $diff); //информация о филиале меняется в БД

                        }
                        header("Location: /branch/list/1");
                        die();
                    }
                    $this->view->generate('editbranch_view.php', 'template_view.php', $data);
                } else {
                    header("Location: /404");
                    die();
                }
            }else {
                header("Location: /404");
                die();
            }
        }else {
            header("Location: /user/login");
            die();
        }
    }

    //Страница одного филиала
    public function action_info($id) {
        if (!empty($_SESSION["id"])) { //если пользователь авторизован
            $params = '`id`, `name`, `description`, `address`'; //параметры для получения
            $data['branchInfo'] = $this->model->getMultiplyBranchParamsById($id, $params); //получение параметров
            $data['proposals'] = $this->model->getProposalsByBranchId($id); //смены филиала
            if ($this->model->isUserAuthorizedAsAdmin() || $this->model->isUserAuthorizedAsManager()) { //если пользователь авторизован под админом или менеджером
                $data['proposals'] = $this->model->getProposalsLimit3($id); //три последние смены
              
            }
            if ($this->model->isUserAuthorizedAsAdmin(){ //если пользователь авторизован под админом
                $data['managers']['thisBranch'] = $this->model->getManagersByBranchId($id); //руководящий состав филиала
                $data['managers']['notThisBranch'] = $this->model->getManagersByBranchId($id, 0); //остальные менеджеры
            }
            $this->view->generate('branchinfo_view.php', 'template_view.php', $data);

        } else {
            header("Location: /user/login");
            die();
        }
    }
    //Смены филиала
    public function action_proposals($id_branch) {
        $data = $this->model->getProposalsByBranchId($id_branch); //получение смен филиала
        $data['branch'] = $this->model->getMultiplyBranchParamsById($id_branch, 'address'); //адрес филиала
        $this->view->generate('proposalslist_view.php', 'template_view.php', $data);
    }
    //Архив смен
    public function action_archive($id_branch) {
        if ($this->model->isUserAuthorizedAsAdmin() || $this->model->isUserAuthorizedAsManager()) {//если пользователь авторизован под админом или менеджером
            $data['action'] = "branch";
            if (($_SERVER["REQUEST_METHOD"] == "POST") & (!empty($_POST))) { //если пост отправлен
                $props = $this->model->getProposalsByBranchId($id_branch, '0, 1', htmlspecialchars($_POST['startDate']), htmlspecialchars($_POST['endDate'])); //получение смен из указанного диапазона
                $data['requests'] = $props['proposal']; //смены
            }
            $this->view->generate('userarchive_view.php', 'template_view.php', $data);
        } else {
            header('Location: /');
            die();
        }
    }
    //удаление филиала
    public function action_delete($id_branch) {
        if ($this->model->isUserAuthorizedAsAdmin()){ //если пользователь авторизован под админом
           if($this->model->delete($id_branch)) { //удалить
               header('Location: /branch/list'); //переадресация на список филиалов
               die();
           }
        } else {
            die();
        }

    }

}