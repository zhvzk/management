<?php
class Model_Proposal extends Model
{
/*
    Закрывает все вчерашние смены
    public function closeAllProposalsByDate() {
        $yesterday= date("Y-m-d", strtotime("- 1 day"));
        $queryUpdateRequests = "UPDATE `request` SET `id_status` = '4' WHERE `id_proposal` IN (SELECT `id` FROM `proposal` WHERE `date` = '".$yesterday."')";
        $queryUpdateProposals = "UPDATE `proposal` SET `is_closed`='1' WHERE `date` = '".$yesterday."'";
        if ($sql = $this->sql_connect()) {
            $sql->query($queryUpdateRequests);
            $sql->query($queryUpdateProposals);
        }
    }
    */
    //Получение информации об одной смене
    public function getProposal($id_proposal)
    {
        if ($sql = $this->sql_connect()) {
            $getProposal = "SELECT `proposal`.`id`, `branch_manager`.`id_branch`, `title`,`description`,`date`,`wd_start`,`wd_end`,`lunch_start`,`lunch_end`,`rate_per_hour`, `is_closed` FROM `proposal` INNER JOIN `branch_manager` ON `proposal`.`id_branch_manager` = `branch_manager`.`id` WHERE `proposal`.`id` = $id_proposal";

            //если найдено
            if (($proposal = $sql->query($getProposal)) && ($proposal->num_rows)) {
                $proposal = $proposal->fetch_assoc();
                //получение информации о филиале
                $getBranch = "SELECT `address` FROM `branch` WHERE `id` = " .$proposal['id_branch'];
                if (($branch = $sql->query($getBranch)) && ($branch->num_rows)) {
                    $branch = $branch->fetch_assoc();

                }
                return $proposal + $branch;
            }
        } else {
            die();
        }
    }
    //Получение количества откликов не смену
    public function countRequests($id_proposal)
    {
        if ($sql = $this->sql_connect()) {
            $query = "SELECT COUNT(*) FROM `request` WHERE `id_proposal` = $id_proposal AND `id_status` NOT IN (3, 4, 1)";
            if (($result = $sql->query($query)) && ($result->num_rows)) {
                $result = $result->fetch_assoc();
                return $result['COUNT(*)'];
            }
        } else {
            die();
        }
    }
    //Получение полной информации о заявках
    public function getEmployeesRequests($id_proposal)
    {
        if ($sql = $this->sql_connect()) {
            $query = "SELECT `user`.`id`, `surname`, `name`, `patronymic`, `id_status` FROM `user` INNER JOIN `request` on `request`.`id_user` = `user`.`id` WHERE `request`.`id_proposal` = $id_proposal AND `request`.`id_status` NOT IN (3, 4, 1)";
           //если найдено
            if (($result = $sql->query($query)) && ($result->num_rows)) {
                while ($row = $result->fetch_assoc()) {
                    $users[] = [
                        'id' => $row['id'],
                        'surname' => $row['surname'],
                        'name' => $row['name'],
                        'patronymic' => $row['patronymic'],
                        'id_status' => $row['id_status'],
                    ];
                }
                return $users;
            }
        }
    }
    //Подтверждение заявки
    public function submitRequest($id_proposal, $id_user)
    {
        if ($sql = $this->sql_connect()) { 
            
            $queryUpdateRequest = "UPDATE `request` SET `id_status` = '1' WHERE `request`.`id_proposal` = $id_proposal AND `request`.`id_user` = $id_user"; //Изменение статуса выбранной заявки на "Подтверждена"
            $queryCancelRequests = "UPDATE `request` SET `id_status` = '4' WHERE `request`.`id_proposal` = $id_proposal AND `request`.`id_user` != $id_user"; //Изменение статуса остальных заявок на "Отменена по инициативе работодателя"
            $queryUpdateProposal = "UPDATE `proposal` SET `is_closed` = '1' WHERE `proposal`.`id` = $id_proposal"; //Изменение статуса смены на "Закрыта"
             //если все запросы выполнены
            if (($sql->query($queryUpdateRequest)) && ($sql->query($queryUpdateProposal)) && ($sql->query($queryCancelRequests))) {
                return true; 
            }
        } else {
            die();
        }
    }
    //Отмена заявки
    public function cancelRequest($id_proposal, $id_user, $id_status = "") {
        if ($sql = $this->sql_connect()) {
        //если статус не указан
            if(empty($id_status)) {
                $id_status = 4; //присваивается значение по умолчанию ("Отменена по инициативе работодателя")
            }
            //изменение статуса заявки, если ее статус не равен 3 или 4 (отменена)
            $queryUpdateRequest = "UPDATE `request` SET `id_status` = $id_status WHERE `request`.`id_proposal` = $id_proposal AND `request`.`id_user` = $id_user AND `id_status` IN (1, 2, 5)";

            if ($sql->query($queryUpdateRequest)) {
                return true;
            }
        } else {
            die();
        }
    }
    //Получить УИД записи о филиалах, к которым прикреплен менеджер
    public function getIdBranchManager($id_user)
    {
        if ($sql = $this->sql_connect()) {
            $getBranchManagerId = "SELECT `id` FROM `branch_manager` WHERE `id_user` = $id_user";

            if ($idBranchManager = $sql->query($getBranchManagerId)) {
                while ($row = $idBranchManager->fetch_assoc()) {
                    $idBranchManagerArray[] = $row['id'];
                }

                return implode(", ", $idBranchManagerArray);

            }
        }
    }
    //Получение смен в диапазоне от "сегодня" до "1 день след месяца" постранично 
    public function getAllProposals($page, $action = "", $id_manager = "")
    {
        if ($sql = $this->sql_connect()) {
            $proposalsPerPage = 10; //на странице
            $proposalsPerPageSql = ($page - 1) * $proposalsPerPage; //ограничение для LIMIT
            $today = date("Y-m-d", strtotime("now")); //дата
            $maxDate = date("Y-m-d", strtotime("first day of +1 month")); //максимальная дата
            $query = "SELECT `id`,`title`,`date`, `is_closed` FROM `proposal` WHERE `is_closed` = 0 AND `date` >= '".$today."' "; //запрос
            if ($action == "manager") { //если запрашиваются только филиалы одного менеджера
                $query.= " AND `id_branch_manager` IN ($id_manager) ";
            }
            $query .=   " ORDER BY `date`"; //сортировка по дате
            $queryLimit = $query . " LIMIT $proposalsPerPageSql, $proposalsPerPage"; //количество на странице
            //если запрос выполнен
            if ($result = $sql->query($queryLimit)) {
                if ($result->num_rows) { //если найдено
                   
                    while ($row = $result->fetch_assoc()) { //построчно
                        $date = strtotime($row['date']);
                        $proposal[] = [
                            'id' => $row['id'],
                            'title' => $row['title'],
                            'date' => date('d.m.Y', $date),
                        ];
                    }
                    $countProposals = $sql->query($query)->num_rows; //сколько смен всего
                    $countPage = ceil($countProposals / $proposalsPerPage); //округление в большую сторону
                    $pagination = array();
                    for ($i = 1; $i <= $countPage; $i++) { //пагинация
                        if ($i == $page) {
                            $pagination[] = ' <a href="/proposal/list/' . $i . '"  class="regular-pagination regular-pagination-active">' . $i . '</a>';
                        } else {
                            $pagination[] = ' <a href="/proposal/list/' . $i . '" class="regular-pagination">' . $i . '</a>';
                        }
                    }

                    $resultsToReturn = [
                        'proposal' => $proposal, //смены
                        'pagination' => $pagination, //пагинация
                        'countProposals' => $countProposals //счетчик
                    ];
                    return $resultsToReturn;
                }
            }
        } else {
            die();
        }
    }

    //удаление смены
    public function delete($id_proposal) {
        if ($sql = $this->sql_connect()) { //подключение к БД
            $queryRequest = "DELETE FROM `request` WHERE `request`.`id_proposal` = $id_proposal;"; //удаление всех заявок на смену
            $queryProposal = "DELETE FROM `proposal` WHERE `id` = $id_proposal"; //удаление смены

            if (($sql->query($queryRequest)) && ($sql->query($queryProposal))) { //если запросы выполнены
                return true;
            } else {
                return false;
            }


        }
    }
//Информация об исполнителе, которым закрыта смена
    public function getEmployee($id_proposal) {
        if ($sql = $this->sql_connect()) {
            $query = "SELECT `name`, `surname`, `patronymic` FROM `user` WHERE `id` IN (SELECT `id_user` FROM `request` WHERE `id_proposal` = $id_proposal AND `id_status` = 1)";
            if ($result = $sql->query($query)) {
                return $result->fetch_assoc(); //ассоцативный массив
            }
        }
    }
    //Пользователь авторизован под админом?
    public function isUserAuthorizedAsAdmin() {
        if (!empty($_SESSION["id"])) {
            if ((!empty($_SESSION["id_role"])) && ($_SESSION["id_role"] == 1)) {
                return true;
            }
            return false;
        } else {
            return false;
        }
    }
    //Пользователь авторизован под менеджером?
    public function isUserAuthorizedAsManager() {
        if (!empty($_SESSION["id"])) {
            if ((!empty($_SESSION["id_role"])) && ($_SESSION["id_role"] == 2)) {
                return true;
            }
            return false;
        } else {
            return false;
        }
    }
    //Пользователь авторизован под обычным сотрудником?
    public function isUserAuthorizedAsUser() {
        if (!empty($_SESSION["id"])) {
            if ((!empty($_SESSION["id_role"])) && ($_SESSION["id_role"] == 3)) {
                return true;
            }
            return false;
        } else {
            return false;
        }
    }
    //получение всех филиалов
    public function getAllBranches()
    {
        if ($sql = $this->sql_connect()) {
            $query = "SELECT `id`, `name`, `address` FROM `branch`";
            if (($result = $sql->query($query)) && ($result->num_rows)) {
                while ($row = $result->fetch_assoc()) {
                    $branches[] = [
                        'id' => $row['id'],
                        'name' => $row['name'],
                        'address' => $row['address'],
                    ];
                }
                return $branches;
            }
        }
    }

    public function getBranchesByUserId($id_user) {
        if ($sql = $this->sql_connect()) {
            $query = "SELECT `branch_manager`.`id`, `name`, `address` FROM `branch` INNER JOIN `branch_manager` ON `branch_manager`.`id_branch` = `branch`.`id` WHERE `branch_manager`.`id_user` = $id_user ORDER BY `branch`.`id`";
            if (($result = $sql->query($query)) && ($result->num_rows)) {
                while ($row = $result->fetch_assoc()) {
                    $branches[] =  [
                        'id' => $row['id'],
                        'name' => $row['name'],
                        'address' => $row['address'],
                    ];
                }

            }
            return $branches;
        } else {
            die();
        }
    }
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
    //Прикрепить администратора к филиалу
    public function insertAdminIntoBranchManager($id_user, $id_branch) {
        if ($sql = $this->sql_connect()) {
            $searchQuery = "SELECT `id` FROM `branch_manager` WHERE `id_user` = $id_user AND `id_branch` = $id_branch"; //есть ли такая запись в таблице
            if ($sql->query($searchQuery)->num_rows == 0) { //если нет
                $query = "INSERT INTO `branch_manager` (`id`, `id_branch`, `id_user`) VALUES (NULL, $id_branch, $id_user);"; //запрос на внесение
                if ($sql->query($query)) { //внесение
                    return $sql->insert_id; //возвращает УИД записи
                }
            } else {
                $res = $sql->query($searchQuery)->fetch_assoc();
                return $res['id'];

            }
        } else {
            die();
        }
    }
    //Проверка корректности заполнения формы
    public function checkProposal($array) {
        if ((!empty($array['wd_start'])) && (!empty($array['wd_end'])) && (!empty($array['lunch_start'])) && (!empty($array['lunch_end']))) {
            if ($array['wd_start'] > $array['wd_end']) {
                $error[] = "Начало рабочего дня не может быть позже окончания";
            }
            if ($array['wd_start'] == $array['wd_end']) {
                $error[] = "Начало рабочего дня не может быть равно окончанию";
            }

            if ($array['lunch_start'] > $array['lunch_end']) {
                $error[] = "Начало обеда не может быть позже окончания";
            }
            if ($array['lunch_start'] == $array['lunch_end']) {
                $error[] = "Начало обеда не может быть быть равно окончанию";
            }

            if ($array['lunch_start'] > $array['wd_end']) {
                $error[] = "Начало обеда не может быть позже окончания рабочего дня";
            }
            if ($array['lunch_end'] > $array['wd_end']) {
                $error[] = "Конец обеда не может быть позже окончания рабочего дня";
            }
            if ($array['lunch_start'] < $array['wd_start']) {
                $error[] = "Начало обеда не может быть раньше начала рабочего дня";
            }
            if (!empty($error))
                return $error;
        }
    }

    //Добавление смены
    public function add($data) {
        if ($sql = $this->sql_connect()) {
            $query = "INSERT INTO `proposal` (`id`, `id_branch_manager`, `title`, `description`, `date`, `wd_start`, `wd_end`, `lunch_start`, `lunch_end`, `rate_per_hour`, `is_closed`) VALUES (NULL, '" . $data['branch'] . "', '" . $data['title'] . "', '" . $data['description'] . "', '" . $data['date'] . "', '" . $data['wd_start'] . "', '" . $data['wd_end'] . "','" . $data['lunch_start'] . "', '" . $data['lunch_end'] . "', '" . $data['rate_per_hour'] . "', '0')";
            if ($sql->query($query)) {
                return true;
            } else {
                return false;
            }
        }
    }
    //Получить исполнителей, которые еще не оставили заявку на эту смену
    public function getEmployees($id_proposal) {
        if ($sql = $this->sql_connect()) {
            $query = "SELECT `id`, `patronymic`, `name`, `surname` FROM `user` WHERE `id_role` = 3 AND `id` NOT IN (SELECT `id_user` FROM `request` WHERE `id_proposal` = $id_proposal) ORDER BY `surname`";
            if (($result = $sql->query($query)) && ($result->num_rows)) { //если найдено
                while ($row = $result->fetch_assoc()) {
                    $employees[] =  [
                        'id' => $row['id'],
                        'surname' => $row['surname'],
                        'name' => $row['name'],
                        'patronymic' => $row['patronymic']
                    ];
                }

            }
            return $employees;
        } else {
            die();
        }
    }
    //Отправить заявку исполнителю на эл почту
    public function sendRequestOnEmail($id_user, $id_proposal) {
        if ($sql = $this->sql_connect()) {
            $getUserEmailQuery = "SElECT `email` FROM `user` WHERE `id` = $id_user"; //получить адрес почты исполнителя
            $getAdminEmailQuery = "SElECT `email` FROM `user` WHERE `id` = ".htmlspecialchars($_SESSION['id']); //получить адрес почты отправителя
            $proposalInfo = $this->getProposal($id_proposal); //получить информацию о смене
            if (($res = $sql->query($getUserEmailQuery)->fetch_assoc()) && ($res2 = $sql->query($getAdminEmailQuery)->fetch_assoc()) && !empty($proposalInfo)) { //если запросы выполнены
                $userEmail = $res['email']; //почта исполнителя
                $adminEmail = $res2['email']; //почта отправителя
                $addProposalQuery = "INSERT INTO `request` (`id`, `id_proposal`, `id_user`, `id_status`) VALUES (NULL, '".$id_proposal."', '".$id_user."', '5')"; //добавление информации о заявке в БД
                if ($sql->query($addProposalQuery)) {
                    $headers = "MIME-Version: 1.0\r\n";
                    $headers .= "Content-type: text/html; charset=utf-8\r\n";
                    $headers .= "To: <$userEmail>\r\n";
                    $headers .= "From: <$adminEmail>\r\n";

                    $message = "
                <html>
                <head>
                <title>Вам предложена заявка " .$proposalInfo['date'] . " с ".$proposalInfo['wd_start']." по ".$proposalInfo['wd_end']."</title>
                </head>
                <body>
                Для получения подробной информации перейдите по <a href='/proposal/view/".$id_proposal."'>ссылке</a>
                С уважением, руководство.
                </body>
                </html>
                ";
                    if (mail($userEmail, "Заявка от работодателя", $message, $headers)) { //отправка письма на почту
                        return "Заявка отправлена";
                    } else {
                        return "При отправке заявки произошла ошибка";
                    }
                }
            }
        }
    }
    //Отправлена ли заявка пользователем
    public function isRequestExists($id_user, $id_proposal)
    {
        if ($sql = $this->sql_connect()) {
            $query = "SELECT `id`, `id_status` FROM `request` WHERE `id_proposal` = $id_proposal AND `id_user` = $id_user"; //получение заявки на смену
            if (($res = $sql->query($query))&&($res->num_rows == 1)) { //если найдено
                $res = $res->fetch_assoc();
                return $res;     
            } else {
            return false; //если нет
            }
        } else {
            die();
        }
    }



}