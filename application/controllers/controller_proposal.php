<?class Controller_Proposal extends Controller
{
    public function __construct()
    {
        $this->model = new Model_Proposal();
        parent::__construct();
    }
    /*
    Закрывает все вчерашние смены
    public function action_updatetmp() {
        $this->model-> closeAllProposalsByDate();
    }*/

    //Страница одной смены
    public function action_view($id_proposal) {
        $data['proposal'] = $this->model->getProposal($id_proposal); //Информация о смене
        if ($data['proposal']['is_closed'] == 1) { //Если смена закрыта
            $data['user'] = $this->model->getEmployee($id_proposal); //Информация об исполнителе
        }
        $data['requests']['count'] = $this->model->countRequests($id_proposal); //Счетчик заявок на смену
        if (($this->model->isUserAuthorizedAsAdmin())||($this->model->isUserAuthorizedAsManager())) { //Если страницу открывает администратор или менеджер
            if (($_SERVER["REQUEST_METHOD"] == "POST") & (!empty($_POST))) { //Если отправлен POST запрос
                $data['res'] = $this->model->sendRequestOnEmail(htmlspecialchars($_POST['employee']), $id_proposal); //Заявка отправляется исполнителю
            }
            $data['requests']['employeesRequests'] = $this->model->getEmployeesRequests($id_proposal); //Заявки от исполнителей
            $data['employees'] = $this->model->getEmployees($id_proposal); //Информация обо всех доступных исполнителях 
        } elseif ($this->model->isUserAuthorizedAsUser()) { //Если страницу открывает пользователь
            $data['isRequestExists'] = $this->model->isRequestExists(htmlspecialchars($_SESSION['id']), $id_proposal); //Отправлена ли заявка на смену?
        }
        $this->view->generate('oneproposal_view.php', 'template_view.php', $data);
    }
    //Подтвердить заявку
    public function action_submit($href) {
        if (($this->model->isUserAuthorizedAsManager())||($this->model->isUserAuthorizedAsAdmin())) { //Если действие вызывает администратор или менеджер
            $string = explode("_", $href); //урл разбивается на массив
            $id_proposal = $string[0]; //уд смены
            $id_user = $string[1]; //уд пользователя

            if ($this->model->submitRequest($id_proposal, $id_user)) { //Подтвердить заявку
                header("Location: " . $_SERVER['HTTP_REFERER']); //Переадресация на предыдущую страницу
                die();
            }
        }

    }
    //Отменить заявку
    public function action_cancel($href) {
        if (($this->model->isUserAuthorizedAsManager())||($this->model->isUserAuthorizedAsAdmin())) {//Если действие вызывает администратор или менеджер
            $string = explode("_", $href); //урл разбивается на массив
            $id_proposal = $string[0];//уд смены
            $id_user = $string[1];//уд пользователя

            if ($this->model->cancelRequest($id_proposal, $id_user)) {//Отменить заявку
                header("Location: " . $_SERVER['HTTP_REFERER']);//Переадресация на предыдущую страницу
                die();
            }
        }
    }
//    Отмена заявки по инициативе сотрудника
    public function action_takeback($href) {
        $string = explode("_", $href);//урл разбивается на массив
        $id_proposal = $string[0//уд смены
        $id_user = $string[1//уд пользователя

        if($this->model->cancelRequest($id_proposal, $id_user, '3')) {//Отменить заявку со статусом 3
            header("Location: ".$_SERVER['HTTP_REFERER']);
            die();
        }
    }
    //Список смен
    public function action_list($page)
    {
            if (!isset($page) || empty($page)) { //если страница не указана, ей присваивается 1
                $page = 1;
            }
        if ($this->model->isUserAuthorizedAsManager()) { //Если страницу открывает менеджер
            $idBranchManager = $this->model->getIdBranchManager(htmlspecialchars($_SESSION['id'])); //запись о менеджере в таблице менеджеров
            $data = $this->model->getAllProposals($page, 'manager', $idBranchManager); //получение всех смен филиалов менеджера со страницы $page

        } else {
            $data = $this->model->getAllProposals($page); //Получение всех смен
        }
            $this->view->generate('proposalslist_view.php', 'template_view.php', $data);

    }
    //Удалить смену
    public function action_delete($id_proposal) {
        if (($this->model->isUserAuthorizedAsManager())||($this->model->isUserAuthorizedAsAdmin())) { //Если действие вызывает администратор или менеджер
            if ($this->model->delete($id_proposal)) { //Удалить
                header('Location: /proposal/list'); //Переадресация на список смен
                die();

            }
        }
    }

    //Добавить смену
    public function action_add() {
        $id_user = htmlspecialchars($_SESSION['id']); //УИД пользователя
        if (($_SERVER["REQUEST_METHOD"] == "POST") & (!empty($_POST))) { //если отправлен пост
            $post = $this->model->securityCheck($_POST, true); //экранирование символов
            if ($this->model->isUserAuthorizedAsAdmin()) { //Если запрос отправлен администратором
                $post['branch'] = $this->model->insertAdminIntoBranchManager($id_user, $post['branch']); //он добавляется в состав управленцев филиала
            }
            if ($error = $this->model->checkProposal($post)) { //проверка на ошибки заполнения формы
                if (!empty($error)) { //если ошибки есть
                    $data['error'] = $error; //они выводятся на экран

                }
            } else { //если ошибок нет
                if ($this->model->add($post)) { //смена добавляется
                    header('Location: /proposal/list');
                    die();
                }
            }
        }
        $data['id_user'] = $id_user; //УИД пользователя
        $data['post'] = $post; //Информация о смене
        if ($this->model->isUserAuthorizedAsAdmin()) { //Если администратор
            $data['branches'] = $this->model->getAllBranches(); //все филиалы
        } elseif ($this->model->isUserAuthorizedAsManager()) { //Если менеджер
            $data['branches'] = $this->model->getBranchesByUserId($id_user); //филиалы менеджера
        }
        $this->view->generate('addproposal_view.php', 'template_view.php', $data);
    }
}