<?php
class Model_User extends Model
{
    //Валидация полей формы
	public function formFieldValidation($dataArray)
	{
		$error = 0;
		foreach ($dataArray as $data) {
			if (empty($data)) { //если поле пустое - изменяется значение переменной
				$error = 1;
			}
		}
		if ($error === 0) { //если значение переменной не изменилось, ошибок нет
			return true;
		} else {
			return false;
		}
	}
	/*
     Генерация пользователей для добавления в БД
    Для использования раскомментировать эту функцию + action_autoadd в controller_user

    public function autoAdd($quantity, $role) {
    //данные
        $persons = [
        //женщины
            '0' => [
                'names' => ['Ольга', 'Василиса', 'Майя', 'Вера', 'Елизавета', 'Эмма', 'Милана', 'Софья', 'Анастасия', 'Виктория', 'Ева', 'Варвара', 'Алиса', 'Алёна', 'Кира', 'Полина', 'Валерия', 'Ясмина', 'София'],
                'surnames' => ['Покровская', 'Александрова', 'Козлова', 'Капустина', 'Карпова', 'Панова', 'Андреева', 'Евсеева', 'Рубцова', 'Захарова', 'Ермилова', 'Исаева', 'Богданова', 'Смирнова', 'Жукова', 'Алексеева', 'Попова'],
                'patronymics' => ['Давидовна', 'Тимуровна', 'Игоревна', 'Руслановна', 'Юрьевна', 'Степановна', 'Никитична', 'Всеволодовна', 'Максимовна', 'Фёдоровна', 'Владимировна'],

            ],
         //мужчины
            '1' => [
                'surnames' => [
                    'Головин','Иванов','Самсонов','Балашов','Сидоров','Греков','Леонов','Юдин','Майоров','Парфенов','Тарасов','Герасимов','Захаров','Федоров','Поляков','Мельников'
                ],
                'names' => [
                    'Максим', 'Алексей', 'Руслан', 'Роман', 'Владимир', 'Иван', 'Александр', 'Тигран', 'Герман', 'Дмитрий', 'Георгий', 'Лев', 'Кирилл', 'Михаил', 'Марк', 'Даниил', 'Тимофей', 'Артём', 'Никита'
                ],
                'patronymics' => ['Никитич', 'Александрович', 'Матвеевич', 'Даниилович', 'Павлович', 'Сергеевич', 'Кириллович', 'Святославович', 'Львович', 'Михайлович'],

            ],
        ];

        $queries = array();
        for ($i = 1; $i <= $quantity; $i++){
            $gender = random_int(0, 1); //пол
            $name = $persons[$gender]['names'][ random_int(0, count($persons[$gender]['names']))]; //количество имен
            $surname = $persons[$gender]['surnames'][ random_int(0, count($persons[$gender]['surnames']))]; //количество фамилий
            $patronymic = $persons[$gender]['patronymics'][ random_int(0, count($persons[$gender]['patronymics']))]; //количество отчеств
            if ($role == 1) {
                $login = "admin";
            }
            elseif ($role == 2) {
                $login = "manager";
            } elseif ($role == 3) {
                $login = "user";
            }
            $login .= time() . $i;


            $queries[] = "INSERT INTO `user` (`id`, `id_role`, `login`, `password`, `surname`, `name`, `patronymic`, `date_of_birth`, `passport`, `snils`, `inn`, `phone_number`, `email`, `salary_increase`, `hash`, `profile_photo`) VALUES (NULL, '$role', '".$login."', '123', '".$surname."', '".$name."', '".$patronymic."', '2021-12-30', '134', '124', '135', '1344', '333', '1', '', '');";
        }
        if ($sql = $this->sql_connect()) {
            foreach ($queries as $query) {
                if($sql->query($query)) {
                    echo "Добавлено";
                }
            }
        }

    }
    */
    //проверка безопасности (массив, bool ассоциативность)
	public function securityCheck($array, $isAssoc = "")
	{
		if ($isAssoc) { //если ассоциативный
			foreach ($array as $key => $value) {
				$secureArray[$key] = htmlspecialchars(stripslashes(trim($value)));
			}
		} else { //если не ассоцативный
			foreach ($array as $value) {
				$secureArray[] = htmlspecialchars(stripslashes(trim($value)));
			}
		}
		return $secureArray;
	}

    //Поиск пользователя в БД
	public function userSearch($login, $password)
	{
		if ($sql = $this->sql_connect()) { //подключение к БД
			$result = $sql->query("SELECT `id`, `id_role` FROM `user` WHERE `login` = '$login' AND `password` = '$password'"); //логин и пароль из БД
			if ($result->num_rows == 1) { //если пользователь найден
				$row = $result->fetch_assoc();
				$_SESSION['id'] = $row['id'];
				$_SESSION['id_role'] = $row['id_role'];
				return true;
			} else { //если пользователь не найден
				return false;
			}
		} else {
			die();
		}
	}

//получить один параметр $param из таблицы с пользователями по id пользователя
	public function getOneUserParamByID($param, $id)
	{
		if ($sql = $this->sql_connect()) {
			$query = "SELECT `$param` FROM `user` WHERE `id` = $id";
			$result = $sql->query($query);
			if ($result->num_rows == 1) { //если пользователь найден
				$row = $result->fetch_assoc();
				$result = $row[$param];
				return $result;
			}
		} else {
			die();
		}
	}


//получить всех пользователей постранично
	public function getAllUsers($page, $role = "")
	{
		if ($sql = $this->sql_connect()) {
			$usersPerPage = 10; //пользователей на странице
			$usersPerPageSql = ($page - 1) * $usersPerPage; //пользователей на странице для Sql
			$query = "SELECT `user`.`id`, `user`.`surname`, `user`.`name`, `user`.`patronymic`, `role`.`name` AS `role_name` FROM `user` INNER JOIN `role` ON `user`.`id_role` = `role`.`id`";
			if (!empty($role)) {
				$query .= "WHERE `role`.`id`=2";
			}
			$query .= " ORDER BY `role_name`";
			$queryLimit = $query . " LIMIT $usersPerPageSql, $usersPerPage";
			if ($result = $sql->query($queryLimit)) {
				if ($result->num_rows) { //если найдено
					$users = array();
					while ($row = $result->fetch_assoc()) {
						$users[] = [
							'id' => $row['id'],
							'surname' => $row['surname'],
							'name' => $row['name'],
							'patronymic' => $row['patronymic'],
							'role_name' => $row['role_name'],
						];
					}
					$countUsers = $sql->query($query)->num_rows; //сколько всего пользователей
					$countPage = ceil($countUsers / $usersPerPage); //сколько страниц нужно. Округление в большую сторону
					$pagination = array();
					for ($i = 1; $i <= $countPage; $i++) { //массив для пагинации
						if ($i == $page) {
							$pagination[] = ' <a href="/user/list/' . $i . '" class="regular-pagination regular-pagination-active">' . $i . '</a>';
						} else {
							$pagination[] = ' <a href="/user/list/' . $i . '"  class="regular-pagination">' . $i . '</a>';
						}
					}
                    //результат
					$resultsToReturn = [
						'users' => $users,
						'pagination' => $pagination,
						'countUsers' => $countUsers
					];

					return $resultsToReturn;
				}
			}
		} else {
			die();
		}
	}

//получить все роли
	public function getRoles()
	{
		if ($sql = $this->sql_connect()) {
			$getRolesQuery = "SELECT `id`, `name` FROM `role`";

			if ($result = $sql->query($getRolesQuery)) {
				if ($result->num_rows) { //если запрос что-то вернул
					while ($row = $result->fetch_assoc()) { //разбиение на ассоциативный массив
						$roles[] = [
							'id' => $row['id'],
							'name' => $row['name']
						];
					}
					return $roles;
				}
			}
		} else {
			die();
		}
	}

//создание учетной записи администратором
	public function addUser($userData)
	{
		$hash = md5($userData['email'] . time() . random_int(0, 100)); //генерация хэша
		$idRole = substr($userData['role'], 4);
		$email = explode('@', $userData['email'], 2);
		$login = $email[0];
		$emailFromForm = $userData['email'];
		if ($sql = $this->sql_connect()) {
			$addUserQuery = "INSERT INTO `user` (`id`, `id_role`, `login`, `password`, `surname`, `name`, `patronymic`, `date_of_birth`, `passport`, `snils`, `inn`, `phone_number`, `email`, `salary_increase`, `hash`) VALUES (NULL, '" . $idRole . "', '" . $login . "', 'null', 'null', 'null', 'null', '1000-01-01', '0', '0', '0', '0', '" . $emailFromForm . "', '1', '" . $hash . "')";

			if ($sql->query($addUserQuery)) {  //Добавление в БД
				$headers = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=utf-8\r\n";
				$headers .= "To: <$emailFromForm>\r\n";
				$headers .= "From: <admin@admin.com>\r\n";

				$message = '
                <html>
                <head>
                <title>Пожалуйста, завершите регистрацию</title>
                </head>
                <body>
                <p>Для этого перейдите по <a href="http://localhost/user/registration/' . $hash . '">ссылке</a>. Ваш логин для входа в систему: <b>' . $login . '</b></p>
                </body>
                </html>
                ';
				if (mail($userData['$email'], "Завершение регистрации", $message, $headers)) { //отправка сообщения для завершения регистрации
					return "Письмо для завершения регистрации отправлено";
				}
			}
		} else {
			die();
		}
	}

    //Обновление хэша в БД
	public function updateHash($idUser)
	{
		if ($sql = $this->sql_connect()) {
			$hash = md5('876543' . time() . '0988');

			$query = "UPDATE `user` SET `hash` = '" . $hash . "' WHERE `id` = $idUser";
			if ($sql->query($query)) {
				return true;
			} else {
				return false;
			}
		}
	}

//получить $param пользователя по хэшу
	public function getUserParamByHash($param, $hash)
	{
		$getParamQuery = "SELECT `$param` FROM `user` WHERE `hash` = '" . $hash . "'";

		if ($sql = $this->sql_connect()) {
			if (($result = $sql->query($getParamQuery)) && ($result->num_rows)) {
				if ($result->num_rows == 1) { //если пользователь найден
					$row = $result->fetch_assoc();
					$result = $row[$param];
					return $result;
				}
			}
		}
	}

    //завершение регистрации. Обновление данных пользователя
	public function updateUserData($idUser, $array)
	{
		if ($sql = $this->sql_connect()) {
			$updateUserQuery = "UPDATE `user` SET ";
			foreach ($array as $columnName => $newValue) { //составления запроса из массива
				$updateUserQuery .= " `$columnName` = '";
				if ($columnName == 'password') { //пароль кодируется
					$updateUserQuery .= md5($newValue) . "', ";
				} else {
					$updateUserQuery .= $newValue . "', ";
				}
			}
			$updateUserQuery = substr($updateUserQuery, 0, -2); //удаление двух последниех символов запроса (пробел и запятая)
			$updateUserQuery .= " WHERE `id` = '" . $idUser . "'";
			if ($sql->query($updateUserQuery)) { //выполнение запроса
				return true;
			} else {
				return false;
			}
		} else {
			die();
		}
	}



    //Получение нескольких параметров по id
	public function getMultiplyUserParamsById($id, $params)
	{
		if ($sql = $this->sql_connect()) {
			$getParamsQuery = "SELECT $params FROM `user` WHERE `id` = $id";

			if (($result = $sql->query($getParamsQuery)) && ($result->num_rows)) {
				$row = $result->fetch_assoc();
				return $row;
			} else {
				die();
			}
		} else {
			die();
		}
	}
    //"Вычитание" массивов
	public function diffBetweenArrays($oldArray, $newArray)
	{
		return array_diff($newArray, $oldArray);
	}

    //Отправка сообщения на $userEmail
	public function sendEmail($userEmail, $subject, $text)
	{
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=utf-8\r\n";
		$headers .= "To: <$userEmail>\r\n";
		$headers .= "From: <admin@admin.com>\r\n";
		if (mail($userEmail, $subject, $text, $headers)) {
			return true;
		}
	}
    //Прикрепление руководителя к филиалу
	function attach($id_branch, $id_user)
	{
		if ($sql = $this->sql_connect()) {
			$attachQuery = "INSERT INTO `branch_manager` (`id`, `id_branch`, `id_user`) VALUES (NULL, '" . $id_branch . "', '" . $id_user . "');";
			if ($sql->query($attachQuery)) {
				return true;
			}
		}
	}
    //Открепление руководителя от филиала
	function unattach($id_branch, $id_user)
	{
		if ($sql = $this->sql_connect()) {
			$getBranchManagerId = "SELECT `id` FROM `branch_manager` WHERE `id_user` = $id_user AND `id_branch` = $id_branch";
			if ($idBranchManager = $sql->query($getBranchManagerId)->fetch_assoc()) {
				$idBranchManager = $idBranchManager['id'];
				$deleteAllRequestQuery = "DELETE FROM  `request` WHERE `id_proposal` IN (SELECT `id` FROM `proposal` WHERE `id_branch_manager` IN ($idBranchManager))"; //удаление заявок на смены, созданные этим руководителем
				$deleteAllProposalsQuery = "DELETE FROM `proposal` WHERE `id_branch_manager` IN ($idBranchManager)"; //удаление смен, созданных этим руководителем
				$unattachQuery = "DELETE FROM `branch_manager` WHERE `id` IN ($idBranchManager)"; //удаление руководителя из урководящего состава

				if ($sql->query($deleteAllRequestQuery)) {
					if ($sql->query($deleteAllProposalsQuery)) {
						if ($sql->query($unattachQuery)) {
							return true;
						}
					}
				}
			}
		}
	}

  //Получить список филиалов, к которым прикреплен или не прикреплен менеджер
  //По умолчанию прикреплен
	public function getBranches($id_user, $action = 1)
	{
		if ($sql = $this->sql_connect()) {
			$query = "SELECT `id`, `name`, `address` FROM `branch` WHERE `id`";
			if ($action == "0") {
				$query .= " NOT";
			}
			$query .= " IN (SELECT `id_branch` FROM `branch_manager` WHERE `id_user` = $id_user) ORDER BY `id`;";
			if (($result = $sql->query($query)) && ($result->num_rows)) { //если найдено 
				while ($row = $result->fetch_assoc()) { //разбиение на ассоциативный массив
					$branches[] = [ //заполнение
						'id' => $row['id'],
						'name' => $row['name'],
						'address' => $row['address'],
					];
				}
			} else {
				$branches[] = [
					'id' => 0,
				];
			}
			return $branches;
		} else {
			die();
		}
	}
   //Получить название роли по id
	public function getRoleName($id)
	{
		if ($sql = $this->sql_connect()) {
			$query = "SELECT `name` FROM `role` WHERE `id` = $id";
			if (($result = $sql->query($query)) && ($result->num_rows)) {
				$result = $result->fetch_assoc();
				return $result['name'];
			}
		} else {
			die();
		}
	}
    //Счетчик заявок пользователя за промежуток "месяц до сегодняшнего дня - месяц после сегодняшнего дня"
	public function countRequests($id_user)
	{
		if ($sql = $this->sql_connect()) {
			$monthsAgo = date("Y-m-d", strtotime("first day of -1 month"));
			$monthsAfter = date("Y-m-d", strtotime("first day of +1 month"));
			$query = "SELECT COUNT(*) FROM `request` WHERE `id_proposal` IN (SELECT `id` FROM `proposal` WHERE `date` BETWEEN '" . $monthsAgo . "' AND '" . $monthsAfter . "') AND `id_user` = " . $id_user . " AND `id_status` IN (1, 3, 4)";
			if (($result = $sql->query($query)) && ($result->num_rows)) {
				$result = $result->fetch_assoc();
				return $result['COUNT(*)'];
			}
		} else {
			die();
		}
	}
  
//1 - подтверждена
//2 - Ожидает подтверждения
//3 - Отменена работодателем
//4 - отменена работником
//5 - отправлена работодателем

//Получить заявки пользователя по статусам
	public function getRequests($id_user, $id_status, $startDate = "", $endDate = "")
	{
		if ((empty($startDate)) || (empty($endDate))) { //Если даты начала или конца не указаны, они возвращаются к значениям по умолчанию
			$startDate = date("Y-m-d", strtotime("first day of -1 month")); //Начало прошлого месяца
			$endDate = date("Y-m-d", strtotime("first day of +1 month")); //Начало следующего месяца 
		} 
        //Получение заявок
		$query = "SELECT `title`, `proposal`.`id`, `date`, `id_status` FROM `proposal` INNER JOIN `request` ON `proposal`.`id` = `request`.`id_proposal` WHERE `proposal`.`id` IN (SELECT `id_proposal` FROM `request` WHERE `id_status` IN ($id_status)) AND `id_user` = $id_user AND `date` BETWEEN '" . $startDate . "' AND '" . $endDate . "' ORDER BY `date`";
		if ($sql = $this->sql_connect()) {
			if (($result = $sql->query($query)) && ($result->num_rows)) {
				while ($row = $result->fetch_assoc()) {
					$getStatusNameQuery = "SELECT `name` FROM `status` WHERE `id` =" . $row['id_status'];
					$statusName = $sql->query($getStatusNameQuery)->fetch_assoc();
					$requests[] = [
						'id' => $row['id'],
						'id_request' => $row['id'],
						'title' => $row['title'],
						'date' => date("d.m.Y", strtotime($row['date'])),
						'status_name' => $statusName['name'],
					];
				}
			} else {
				$requests[] = [
					'id' => 0,
				];
			}
			return $requests;
		}
	}
    //Преобразование массива в строку
	public function arrayToString($array)
	{
		return implode(',', $array);
	}
    //Получение имени роли пользователя по id
	public function getUserRoleName($id_user)
	{
		$query = "SELECT `id`,`name` FROM `role` WHERE `id` IN (SELECT `id_role` FROM `user` WHERE `id` = $id_user)";
		if ($sql = $this->sql_connect()) {
			if (($result = $sql->query($query)) && ($result->num_rows)) {
				$result = $result->fetch_assoc();
				return $result;
			}
		} else {
			die();
		}
	}
    //Получение новых заказ-нарядов
	public function getNewestProposalsCard()
	{
		if ($sql = $this->sql_connect()) {
			$query = "SELECT `user`.`name`, `user`.`surname`, `user`.`patronymic`, `proposal`.`title`, `proposal`.`id` FROM `user` INNER JOIN `branch_manager` ON `user`.`id` = `branch_manager`.`id_user` INNER JOIN `proposal` ON `branch_manager`.`id` = `proposal`.`id_branch_manager` WHERE `branch_manager`.`id` IN (SELECT `proposal`.`id_branch_manager` FROM `proposal`) ORDER BY `proposal`.`id` DESC LIMIT 0, 4";
			if ($result = $sql->query($query)) {
				if ($result->num_rows) {
					while ($row = $result->fetch_assoc()) {
						$card[] = [
							'id_proposal' => $row['id'],
							'name' => mb_substr($row['name'], 0, 1) . ". ",
							'surname' => $row['surname'],
							'patronymic' => mb_substr($row['patronymic'], 0, 1) . ". ",
							'title' => $row['title'],
						];
					}
					return $card;
				}
			}
		}
	}
    //Получение новых заказ-нарядов для филиалов менеджера
	public function getNewestProposalsCardBranchManager($id_user)
	{
		if ($sql = $this->sql_connect()) {
			$query = "SELECT `title`, `date`, `id` from `proposal` WHERE `id_branch_manager` IN (SELECT `id` FROM `branch_manager` WHERE `id_user` = $id_user) ORDER BY `proposal`.`date` DESC LIMIT 0, 4";
			if ($result = $sql->query($query)) {
				if ($result->num_rows) {
					while ($row = $result->fetch_assoc()) {
						$card[] = [
							'id_proposal' => $row['id'],
							'date' => date("d.m.Y", strtotime($row['date'])),
							'title' => $row['title'],
						];
					}
					return $card;
				}
			}
		}
	}
    //Получение заказ-нарядов со свежими откликами
	public function getNewestRequestCard()
	{
		if ($sql = $this->sql_connect()) {
			$query = "SELECT DISTINCT `proposal`.`id` as `id_proposal`, `proposal`.`title`,`proposal`.`date`, (SELECT COUNT(*) FROM `request` WHERE `proposal`.`id` = `request`.`id_proposal`) as `count`  FROM `proposal` INNER JOIN `request` ON `proposal`.`id` = `request`.`id_proposal` LIMIT 0, 3";

			if ($result = $sql->query($query)) {
				if ($result->num_rows) {
					while ($row = $result->fetch_assoc()) {
						$card[] = [
							'id_proposal' => $row['id_proposal'],
							'title' => $row['title'],
							'date' => date("d.m.Y", strtotime($row['date'])),
							'count' => $row['count'],
						];
					}
					return $card;
				}
			}
		}
	}
    //Выход из системы
	public function logout()
	{
		session_destroy();
		header("Location: /user/login");
		die();
	}
    //Получить статус заявки
	public function getRequestsStatus()
	{
		if ($sql = $this->sql_connect()) {
			$query = "SELECT `id`, `name` FROM `status`";
			if ($result = $sql->query($query)) {
				if ($result->num_rows) {
					while ($row = $result->fetch_assoc()) {
						$status[] = [
							'id' => $row['id'],
							'name' => $row['name'],
						];
					}
					return $status;
				}
			}
		}
	}

    //Пользователь авторизован?
	public function isUserAuthorized()
	{
		if (!empty($_SESSION["id"])) {
			return true;
		} else {
			return false;
		}
	}
    
    //Пользователь авторизован под админом?
	public function isUserAuthorizedAsAdmin()
	{
		if ($this->isUserAuthorized()) {
			if ((!empty($_SESSION["id_role"])) && ($_SESSION["id_role"] == 1)) {
				return true;
			}
			return false;
		} else {
			return false;
		}
	}
    //Пользователь авторизован под менеджером?
	public function isUserAuthorizedAsManager()
	{
		if ($this->isUserAuthorized()) {
			if ((!empty($_SESSION["id_role"])) && ($_SESSION["id_role"] == 2)) {
				return true;
			}
			return false;
		} else {
			return false;
		}
	}
    //Авторизован ли пользователь под менеджером или админом?
	public function isUserAuthorizedAsManagerOrAdmin()
	{
		if ($this->isUserAuthorizedAsAdmin() || $this->isUserAuthorizedAsManager()) {
			return true;
		} else {
			return false;
		}
	}
    //Пользователь авторизован под обычным сотрудником?
	public function isUserAuthorizedAsEmployee()
	{
		if ($this->isUserAuthorized()) {
			if ((!empty($_SESSION["id_role"])) && ($_SESSION["id_role"] == 3)) {
				return true;
			}
			return false;
		} else {
			return false;
		}
	}

//Удаление пользователя
	public function delete($id_user)
	{
		if ($role = $this->getOneUserParamByID('id_role', $id_user)) {
			if ($sql = $this->sql_connect()) {
				if (($role == 2) || ($role == 1)) { //если пользователь из руководящего состава
					$query[] = "DELETE FROM  `request` WHERE `id_proposal` IN (SELECT `id` FROM `proposal` WHERE `id_branch_manager` IN (SELECT `id` FROM `branch_manager` WHERE `id_user` = $id_user)"; //удаляются все заявки на его заказ-наряды
					$query[] = "DELETE FROM `proposal` WHERE `id_branch_manager` IN (SELECT `id` FROM `branch_manager` WHERE `id_user` = $id_user)"; //удаляются все его заказ-наряды
					$query[] = "DELETE FROM `branch_manager` WHERE `id_user` = $id_user"; //он удаляется из таблицы руководящего состава
				} else {
					$query[] = "DELETE FROM `request` WHERE `id_user` = $id_user"; //если пользователь - обычный сотрудник, удаляются все его щаявки

				}

				$query[] = "DELETE FROM `user` WHERE `id` = $id_user"; //пользователь удаляется
				$error = 0;
				foreach ($query as $q) { //поочередное выполнение запросов
					$sql->query($q);
				}
				return true;
			} else {
				die();
			}
		}
	}
    //Оставить заявку
	public function addRequest($id_user, $id_proposal)
	{
		$query = "INSERT INTO `request` (`id`, `id_proposal`, `id_user`, `id_status`) VALUES (NULL, '" . $id_proposal . "', '" . $id_user . "', '2')";

		if ($sql = $this->sql_connect()) {
			if ($sql->query($query)) {
				return true;
			}
		} else {
			die();
		}
	}
    //Переадресация авторизованного пользователя в ЛК
	public function redirectAuth()
	{
		if ($this->misUserAuthorizedAsAdmin()) { //если админ
			header("Location: /user/admin/" . $_SESSION["id"]);//личный кабинет админа (админ-панель)
			die();
		} elseif ($this->isUserAuthorizedAsManager()) {//если менеджер
			header("Location: /user/manager/" . $_SESSION["id"]);  //личный кабинет менеджера
			die();
		} elseif ($this->isUserAuthorizedAsEmployee()) { //если пользователь
			header("Location: /user/profile/" . $_SESSION["id"]); //личный кабинет пользователя
			die();
		}
	}
}
