<?php
class Controller_User extends Controller
{
	public function __construct()
	{
		$this->model = new Model_User();

		parent::__construct();
	}

	/*  Генерация пользователей для добавления в БД
    
    Для использования раскомментировать эту функцию + autoAdd в Model_User
    
    public function action_autoadd($href) {
        $string = explode("_", $href); //
        $quantity = $string[0]; //сколько записей добавить
        $role = $string[1];  //какую роль присвоить пользователям (число)
        
        if ($this->model->isUserAuthorizedAsAdmin() && (is_numeric($quantity) && is_numeric($role))) {

            $this->model->autoAdd($quantity, $role);
        }
    }
    */
//Админ панель
	function action_admin()
	{
		if ($this->model->isUserAuthorizedAsAdmin()) { //если пользователь авторизован как админ
			$data['name'] = $this->model->getOneUserParamByID('name', (htmlspecialchars($_SESSION['id']))); //получение имени администратора из БД
			$data['cards']['proposals'] = $this->model->getNewestProposalsCard(); //получение заказ-нарядов (DESC)
			$data['cards']['requests'] = $this->model->getNewestRequestCard(); //получение заказ-нарядов с новыми заявками
			$this->view->generate('adminpanel_view.php', 'template_view.php', $data);
		} else {
			header("Location: /"); //если не админ - переадресация на главную
			die();
		}
	}

//авторизация
	function action_login()
	{
		if (!($model->isUserAuthorized())) { //если пользователь не авторизован
			if (($_SERVER["REQUEST_METHOD"] == "POST") & (!empty($_POST))) {
				$data = array();
				if ($this->model->formFieldValidation($_POST)) { // если все поля формы заполнены
					if ($secureArray = $this->model->securityCheck($_POST, true)) { //пост прогоняется через мини проверку безопасности
						if ($this->model->userSearch($secureArray['login'], md5($secureArray['password']))) { //если пользователь найден в бд + в функции заполняются сессии
							$this->model->redirectAuth(); //переадресация по личым кабинетам
						} else { //если пользователь не найден
							$data['error'] = "Неверный логин или пароль";
						}
					}
				} else { //если не все поля заполнены
					$data['error'] = "Все поля обязательны для заполнения";
				}
			}
			$this->view->generate('login_view.php', 'template_view.php', $data);
		} else { //если пользователь уже авторизован
			$this->model->redirectAuth(); //переадресация по личым кабинетам
		}
	}
//личный кабинет менеджера
	public function action_manager($id_user)
	{
		if ($this->model->isUserAuthorizedAsManager()) { //если пользователь авторизован как менеджер

			$data['name'] = $this->model->getOneUserParamByID('name', (htmlspecialchars($_SESSION['id']))); //получение имени пользователя из БД
			$data['cards']['proposals'] = $this->model->getNewestProposalsCardBranchManager($id_user); //получение заказ-нарядов с новыми заявками (в филиалах менеджера)
			$this->view->generate('adminpanel_view.php', 'template_view.php', $data);
		} else {
			die();
		}
	}
//постраничный вывод списка пользователей в админке
	public function action_list($page)
	{
		if ($this->model->isUserAuthorizedAsManagerOrAdmin()) { //если пользователь авторизован под админом
			if (!isset($page) || empty($page)) { //если страница не указана, ей присваивается 1
				$page = 1;
			}
			$data = $this->model->getAllUsers($page); //получение всех пользователей со страницы $page
			$this->view->generate('userlist_view.php', 'template_view.php', $data);
		}
	}

//Добавление учетной записи
	public function action_add()
	{
		if (!empty($_POST)) {
			if ($array = $this->model->securityCheck($_POST, 1)) { //пост прогоняется через проверку безопасности
				if ($this->model->formFieldValidation($array)) { //если все поля заполнены
					$this->model->addUser($array); //добавляется пользователь
					header("Location: /user/list");
					die();
				}
			}
		}
		$data['role'] = $this->model->getRoles(); //получение ролей из БД
		$this->view->generate('adduser_view.php', 'template_view.php', $data);
	}

//завершение регистрации пользователем
	public function action_registration($hash)
	{
		$hash = htmlspecialchars($hash); //Экранируются символы хэша
		if ($this->model->getUserParamByHash('id', $hash)) { //если хэш есть в бд
			$idUser = $this->model->getUserParamByHash('id', $hash); //получение id пользователя из БД по хэшу
			$data['login'] = $this->model->getUserParamByHash('login', $hash); //получение логина пользователя из БД по хэшу
			if ((!empty($hash)) && empty($_SESSION["id"])) { //если хэш не пустой и пользователь не авторизован
				if ((!empty($_POST)) && ($array = $this->model->securityCheck($_POST, true))) { //если пост отправлен
					if ($this->model->formFieldValidation($array)) { //если все поля заполнены
						if ($this->model->updateUserData($idUser, $array)) { //обновление данных в бд
							if ($this->model->updateHash($idUser)) {
								if ($this->model->userSearch($data['login'], md5($array['password']))) { //авторизация
									$this->model->redirectAuth(); //переадресация в ЛК
								}
							}
						} else {
							$data['error'] = "Заполните все поля";
						}
					}
					$this->view->generate('registration_view.php', 'template_view.php', $data);
				}
			} else { //если хэша нет в БД
				header("Location: /user/login"); //переадресация на авторизацию
			}
		}
	}

    //изменение данных пользователя
	public function action_edit($id)
	{
		if ($this->model->isUserAuthorizedAsAdmin()) { //если админ
			if ((isset($id)) && (!empty($id))) { //если передан параметр $id
				$params = '`login`, `surname`, `name`, `patronymic`, `date_of_birth`, `passport`, `snils`, `inn`, `phone_number`, `email`, `salary_increase`'; //какие поля нужны
				$data['userData'] = $this->model->getMultiplyUserParamsById($id, $params); //получение персональных данных пользователя
				$data['id_role'] = $this->model->getOneUserParamByID('id_role', $id); //получение id роли пользователя
				$data['roles'] = $this->model->getRoles(); //получение всех ролей
				$paramsToCheck = '`id_role`, `login`, `surname`, `name`, `patronymic`, `date_of_birth`, `passport`, `snils`, `inn`, `phone_number`, `email`, `salary_increase`';

				if (!empty($_POST)) { //если пост отправлен
					$oldDataArray = $this->model->getMultiplyUserParamsById($id, $paramsToCheck); //исходные данные пользователя
					$newDataArray = $this->model->securityCheck($_POST, true); //данные из поста
					$diff = $this->model->diffBetweenArrays($oldDataArray, $newDataArray); //"вычитание" массивов
					if (!empty($diff)) { //если массивы разные
						$updateRes = $this->model->updateUserData($id, $diff); //данные обновляются
						$userEmail = $this->model->getOneUserParamByID('email', $id); //получение email пользователя
						$this->model->sendEmail(
							$userEmail,
							"Обновление данных",
							"Ваши данные были обновлены администратором"
						); //уведомление об изменении данных на почту
					}
					header("Location: /user/list/1"); //переадресация на список пользователей
					die();
				}
				$this->view->generate('edituser_view.php', 'template_view.php', $data);
			} else { //если не передан id
				header("Location: /404");
				die();
			}
		} else {
			header("Location: /user/login"); //переадресация на авторизацию
			die();
		}
	}

    //прикрепление менеджера к филиалу 
    //формат ссылки УИД филиала_УИД менеджера
	public function action_attach($href)
	{
		if ($this->model->isUserAuthorizedAsAdmin()) {
			$string = explode("_", $href);
			$id_branch = $string[0];
			$id_user = $string[1];
			if ($this->model->attach($id_branch, $id_user)) { //прикрепить
				header("Location: " . $_SERVER['HTTP_REFERER']); //вернуться на предудущую страницу (страница одного менеджера)
				die();
			}
		} else {
			die();
		}
	}


    //открепление менеджера от филиала 
    //формат ссылки УИД филиала_УИД менеджера
	public function action_unattach($href)
	{
		if ($this->model->isUserAuthorizedAsAdmin()) {
			$string = explode("_", $href);
			$id_branch = $string[0];
			$id_user = $string[1];

			if ($this->model->unattach($id_branch, $id_user)) { //открепить
				header("Location: " . $_SERVER['HTTP_REFERER']);
				die();
			}
		} else {
			die();
		}
	}


    //страница с информацией об одном пользователе
	public function action_profile($id_user)
	{
		if ($this->model->isUserAuthorizedAsManagerOrAdmin()|| (($this->model->isUserAuthorizedAsEmployee() && ($_SESSION['id'] == $id_user))) { //если авторизован админ или менеджер пользователь заходит на свою страницу
			$data['role'] = $this->model->getUserRoleName($id_user); //получение роли искомого пользователя
			if ($data['role']['id'] == 2) { //если менеджер

				$data['branches']['attachedTo'] = $this->model->getBranches($id_user); //филиалы, к которым прикреплен
				$data['branches']['notAttachedTo'] = $this->model->getBranches($id_user, 0); //филиалы, к которым не прикреплен
			} elseif ($data['role']['id'] == 3) { //если пользователь
            //                    $data['count'] = $this->model->countAllRequests($id_user);
				$data['request']['count'] = $this->model->countRequests($id_user); //счетчик заявок
                //1 - подтверждена
                //2 - Ожидает подтверждения
                //3 - Отменена работодателем
                //4 - отменена работником
                //5 - отправлена работодателем
				$data['request']['active'] = $this->model->getRequests($id_user, '1'); //подтвержденные заявки
				$data['request']['canceled'] = $this->model->getRequests($id_user, '3, 4'); //отмененные по различным причинам
			
				$data['request']['archive'] = $this->model->getRequests($id_user, 1, date("Y-m-d", strtotime("first day of -1 month"));, date("Y-m-d", strtotime("now"))); //архивные
				$data['request']['suggested'] = $this->model->getRequests($id_user, '5'); //предложенные заявки

			}

			$params = "`id`, `id_role`, `surname`,`name`,`patronymic`"; //краткая информация о пользователе

			$data['user'] = $this->model->getMultiplyUserParamsById($id_user, $params); //получение информации

			$this->view->generate('userpage_view.php', 'template_view.php', $data);
		}
	}
    //Выход из системы
	public function action_logout()
	{
		$this->model->logout();
	}
    //Архив заявок по пользователю
	public function action_archive($id_user)
	{
		$data['role'] = $this->model->getUserRoleName($id_user); //Получение названия роли пользователя

		if ($data['role']['id'] == 3) { //Если обычный пользователь

			if (($_SERVER["REQUEST_METHOD"] == "POST") & (!empty($_POST))) { //Если форма отправлена
				if (!empty($_POST['status'])) { //если выбран хотя бы один статус
					if ((count($_POST['status']) > 1)) { //если выбрано больше одного статуса
						$status = $this->model->arrayToString($_POST['status']); //выбор превращается в строку с разделителем ","
					} else {
						$status = $_POST['status'][0]; //если один, он присваивается переменной
					}

					$data['requests'] = $this->model->getRequests($id_user, $status, $_POST['startDate'], $_POST['endDate']); //запрос на получние
				} else {
					$_SESSION['error'] = "Выберите хотя бы один статус";
				}
			}
			$params = "`id`, `id_role`, `surname`,`name`,`patronymic`"; //информация о пользователе

			$data['user'] = $this->model->getMultiplyUserParamsById($id_user, $params);
			$data['params'] = $this->model->getRequestsStatus();
			$data['action'] = 'user';

			$this->view->generate('userarchive_view.php', 'template_view.php', $data);
		}
	}
//удаление пользователя
	public function action_delete($id_user)
	{
		if ($this->model->isUserAuthorizedAsAdmin()) { //если админ
			if ($data = $this->model->delete($id_user)) { //удаление
				header('Location: /user/list');
				die();
			}
		} else {
			die();
		}
	}

//Оставить заявку
	public function action_addRequest($url)
	{
		$string = explode("_", $url);
		$id_user = $string[0];
		$id_proposal = $string[1];

		if ($this->model->addRequest($id_user, $id_proposal)) {
			header("Location: " . $_SERVER['HTTP_REFERER']);
			die();
		}
	}
}
