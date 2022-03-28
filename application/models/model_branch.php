<?php
class Model_Branch extends Model
{
/*
     Генерация филиалов для добавления в БД
    Для использования раскомментировать эту функцию + action_autoadd в controller_branch

    public function autoAdd($quantity) {
        $addresses = [
            "ул. Миллионная", "1-й Верхний переулок", "1-я Никитинская улица", "пр-т Героев", "ул. Глухарская", "ул. Граничная", "ул. Дивенская"
        ];
        $descriptions = [
            " малая проходимость,", " хорошие руководители,", " приятный коллектив,"," отзывчивые сорудники," , " много места,", "большие окна,", " близко от метро,", " 15 минут на транспорте,"
            ];
        $queries = array();
        for ($i = 1; $i <= $quantity; $i++){
            $name = "Магазин №". random_int(1, 100);
            for ($j = 0; $j < 3; $j++) {
                $description .= $descriptions[random_int(0, count($descriptions))];

            }
            $description = substr($description, 0, -1);
            $address = $addresses[random_int(0, count($addresses))] . ", д. " . random_int(1, 100);
            $queries[] = "INSERT INTO `branch` (`id`, `name`, `description`, `address`) VALUES (NULL, '$name', '$description', '$address')";
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
    //Получить записи о филиалах менеджера
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

    //получить все филиалы постранично
    public function getAllBranches($page, $action = "", $id_manger = "")
    {
        if ($sql = $this->sql_connect()) {
            $branchesPerPage = 10;//на странице
            $branchesPerPageSql = ($page - 1) * $branchesPerPage;//ограничение для LIMIT
            $query = "SELECT `id`, `name`, `address` FROM `branch`";
            if ($action == "manager") {
                $query.= " WHERE `id` IN (SELECT `id_branch` FROM `branch_manager` WHERE `id` IN ($id_manger)) "; //если филиалы менеджера
            }
            $query .= " ORDER BY `name` "; //сотировка по названию
            $queryLimit = $query . " LIMIT $branchesPerPageSql, $branchesPerPage";
            if ($result = $sql->query($queryLimit)) {//если запрос выполнен
                if ($result->num_rows) { //если найдено
                    $branch = array();
                    while ($row = $result->fetch_assoc()) {//построчно
                        $branch[] = [
                            'id' => $row['id'],
                            'name' => $row['name'],
                            'address' => $row['address'],
                        ];
                    }
                    $countBranches = $sql->query($query)->num_rows //сколько филиалов всего
                    $countPage = ceil($countBranches / $branchesPerPage); //округление в большую сторону
                    $pagination = array();
                    for ($i = 1; $i <= $countPage; $i++) {//пагинация
                        if ($i == $page) {
                            $pagination[] = ' <a href="/branch/list/' . $i . '"  class="regular-pagination regular-pagination-active">' . $i . '</a>';
                        } else {
                            $pagination[] = ' <a href="/branch/list/' . $i . '"  class="regular-pagination"> ' . $i . '</a>';
                        }
                    }

                    $resultsToReturn = [
                        'branch' => $branch, //филиалы
                        'pagination' => $pagination, //пагинация
                        'countBranches' => $countBranches //счетчик
                    ];

                    return $resultsToReturn;
                }
            }
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
    //Добавить Филиал
    public function addBranch($branchData)
    {
        if ($sql = $this->sql_connect()) {
            $addBranchQuery = "INSERT INTO `branch` (`id`, `name`, `description`, `address`) VALUES (NULL, '" . $branchData['name'] . "', '" . $branchData['description'] . "', '" . $branchData['address'] . "');";
            if ($sql->query($addBranchQuery)) {
                $_SESSION['success'] = "Филиал добавлен";
            } else {
                die();
            }
        } else {
            die();
        }
    }
    //Получить несколько параметров филиала по УИД
    public function getMultiplyBranchParamsById($id, $params)
    {
        if ($sql = $this->sql_connect()) {
            $getParamsQuery = "SELECT $params FROM `branch` WHERE `id` = $id";
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
    //Вычитание массивов
    public function diffBetweenArrays($oldArray, $newArray)
    {
        return array_diff($newArray, $oldArray);
    }
    //Обновление информации о массиве
    public function updateBranchData($idBranch, $array)
    {
        if ($sql = $this->sql_connect()) {
            $updateBranchQuery = "UPDATE `branch` SET ";
            foreach ($array as $columnName => $newValue) { //перебирает все значения массива, добавляет в запрос
                $updateBranchQuery .= "`$columnName` = '" . $newValue . "', ";
            }
            $updateBranchQuery = substr($updateBranchQuery, 0, -2);
            $updateBranchQuery .= " WHERE `id` = '" . $idBranch . "'";
            if ($sql->query($updateBranchQuery)) { //если запрос выполнен
                return true;
            } else {
                return false;
            }
        } else {
            die();
        }
    }

    //Получить менеджеров по УИД филиала
    public function getManagersByBranchId($id_branch, $action = 1)
    {
        if ($sql = $this->sql_connect()) {
            $query = "SELECT `id`,`surname`,`name`,`patronymic` FROM `user` WHERE `id`";

            if ($action == "0") { //получить НЕ менеджеров этого филиала
                $query .= " NOT";
            }
            $query .= " IN (SELECT `id_user` FROM `branch_manager` WHERE `id_branch` = $id_branch) AND `id_role` = 2 ORDER BY `user`.`id`;";
            //если найдено
            if (($result = $sql->query($query)) && ($result->num_rows)) { 
                while ($row = $result->fetch_assoc()) {//построчно
                    $managers[] = [
                        'id' => $row['id'],
                        'surname' => $row['surname'],
                        'name' => $row['name'],
                        'patronymic' => $row['patronymic'],
                    ];
                }

            } else { //если не найдено
                $managers[] = [
                    'id' => 0,
                ];
            }
            return $managers;
        } else {
            die();
        }
    }
    //получить три смены филиала
    public function getProposalsLimit3($id)
    {
        if ($sql = $this->sql_connect()) {
            $query = "SELECT DISTINCT `proposal`.`id` as `id_proposal`, `proposal`.`title`,`proposal`.`date`, (SELECT COUNT(*) FROM `request` WHERE `proposal`.`id` = `request`.`id_proposal`) as `count`  FROM `proposal`INNER JOIN `request` ON `proposal`.`id` = `request`.`id_proposal` WHERE `id_branch_manager` IN (SELECT `id` FROM `branch_manager` WHERE `id_branch` = $id)  LIMIT 0, 3";
            if ($result = $sql->query($query)) {
                if ($result->num_rows) {
                    while ($row = $result->fetch_assoc()) {
                        $card[] = [
                            'id_proposal' => $row['id_proposal'],
                            'title' => $row['title'],
                            'date' => $row['date'],
                            'count' => $row['count'],
                        ];
                    }
                    return $card;
                }
            }

        } else {
            die;
        }
    }
    //Получить все смены филиала [в диапазоне дат]
    public function getProposalsByBranchId($id_branch, $is_closed = "0", $startDate = "", $endDate = "")
    {
        if ($sql = $this->sql_connect()) {
            $today = date("Y-m-d", strtotime("now"));
            $query = "SELECT `id`,`title`,`date`, `is_closed` FROM `proposal` WHERE `is_closed` IN ($is_closed)  AND `id_branch_manager` IN (SELECT `id` FROM `branch_manager` WHERE `id_branch` = $id_branch)";

            if (!empty($startDate)) { //если указана дата начала диапазона
                $query .= " AND `date` > '" . $startDate . "'"; //добавление в запрос
            }

            if (!empty($endDate)) { //если указан конец диапазона
                $query .= " AND `date` < '" . $endDate . "'"; //добавление в запрос
            }

            $query .= " ORDER BY `date`"; //сортировка по дате
            if (($res = $sql->query($query)) && ($res->num_rows)) {
                $return['countProposals'] = $res->num_rows; //счетчик
                while ($row = $res->fetch_assoc()) {
                    $proposal[] = [
                        'id' => $row['id'],
                        'title' => $row['title'],
                        'date' => date("d.m.Y", strtotime($row['date'])),
                        'is_closed' => $row['is_closed'],
                    ];
                }
                $return['proposal'] = $proposal;
                return $return;
            } else return false;

        }
    }
    //Пользователь авторизован под админом?
    public function isUserAuthorizedAsAdmin()
    {
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
    public function isUserAuthorizedAsManager()
    {
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
    public function isUserAuthorizedAsUser()
    {
        if (!empty($_SESSION["id"])) {
            if ((!empty($_SESSION["id_role"])) && ($_SESSION["id_role"] == 3)) {
                return true;
            }
            return false;
        } else {
            return false;
        }
    }
    //Удаление филиала
    public function delete($id_branch)
    {
        if ($sql = $this->sql_connect()) {
            $getBranchManagerId = "SELECT `id` FROM `branch_manager` WHERE `id_branch` = $id_branch"; //получение менеджеров филиала
            if($idBranchManager = $sql->query($getBranchManagerId)) {  //если найдено
                while ($row = $idBranchManager->fetch_assoc()) { //разбиение на массив
                    $idBranchManagerArray[] = $row['id']; 
                }

                $idBranchManager = implode(", ", $idBranchManagerArray); //строка с разделителем ,
                $deleteAllRequestQuery = "DELETE FROM  `request` WHERE `id_proposal` IN (SELECT `id` FROM `proposal` WHERE `id_branch_manager` IN ($idBranchManager))"; //удаление всех заявок на смены от руковводителей этого филиала
                $deleteAllProposalsQuery = "DELETE FROM `proposal` WHERE `id_branch_manager` IN ($idBranchManager)"; //удаление всех смен этого филиала
                $deleteBranchManagers = "DELETE FROM `branch_manager` WHERE `id` IN ($idBranchManager)"; //удаление всех руководителей
                $deleteBranch = "DELETE FROM `branch` WHERE `id` = $id_branch"; //удаление филиала
                //каскадное удаление
                if ($sql->query($deleteAllRequestQuery)) {  
                    if ($sql->query($deleteAllProposalsQuery)) {
                        if ($sql->query($deleteBranchManagers)) {
                            if ($sql->query($deleteBranch)) {
                                return true;
                            }
                        }
                    }
                }
            }
        }
    }
}
