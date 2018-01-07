<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 18/08/2017
 * Time: 15:57
 */

namespace Employees\Services;


use Employees\Adapter\DatabaseInterface;
use Employees\Models\Binding\Emp\EmpBindingModel;
use Employees\Models\DB\Employee;


class EmployeesService implements EmployeesServiceInterface
{

    private $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }

    public function getList()
    {
        $query = "SELECT 
                  id, 
                  ext_id AS extId,
                  first_name AS firstName,
                  last_name AS lastName,
                  position,
                  team,
                  start_date AS dateStart,
                  birthday,
                  image 
                  FROM employees";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $result = $stmt->fetchAll();

        return $result;
    }


    public function getListStatus($active)
    {
        $query = "SELECT 
                  id, 
                  ext_id AS extId,
                  first_name AS firstName,
                  last_name AS lastName,
                  position,
                  team,
                  start_date AS dateStart,
                  birthday,
                  image 
                  FROM employees 
                  WHERE active = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$active]);

        $result = $stmt->fetchAll();

        return $result;
    }

    public function getEmp($id) {
        $query = "SELECT 
                  id,
                  ext_id AS extId,
                  first_name AS firstName,
                  last_name AS lastName,
                  position,
                  team,
                  start_date AS dateStart,
                  birthday,
                  image 
                  FROM employees WHERE id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        $result = $stmt->fetch();

        return $result;
    }



    public function getEmpByStrId($strId) {
        $query = "SELECT 
                  id,
                  ext_id AS extId,
                  first_name AS firstName,
                  last_name AS lastName,
                  position,
                  team,
                  start_date AS dateStart,
                  birthday,
                  image 
                  FROM employees WHERE unique_str_code = ? AND active = ?";

        $stmt = $this->db->prepare($query);

        $stmt->execute([$strId, "yes"]);
        $result = $stmt->fetch();

        return $result;
    }

    public function addEmp(EmpBindingModel $model, $uniqueStrId)
    {
        $query = "INSERT INTO 
                  employees (
                  first_name,
                  last_name,
                  position,
                  team,
                  start_date,
                  birthday,
                  image,
                  active,
                  unique_str_code
                  )
                  VALUES (?,?,?,?,?,?,?,?,?)";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $model->getFirstName(),
            $model->getLastName(),
            $model->getPosition(),
            $model->getTeam(),
            $model->getStartDate(),
            $model->getBirthday(),
            $model->getImage(),
            $model->getActive(),
            $uniqueStrId
        ]);

    }

//    public function updEmp(EmpBindingModel $model)
    public function updEmp(EmpBindingModel $empBindingModel)
    {

        $updatePropArray = [
            "first_name"=>$empBindingModel->getFirstName(),
            "last_name"=>$empBindingModel->getLastName(),
            "position"=>$empBindingModel->getPosition(),
            "team"=>$empBindingModel->getTeam(),
            "start_date"=>$empBindingModel->getStartDate(),
            "birthday"=>$empBindingModel->getBirthday(),
            "image" => $empBindingModel->getImage(),
            "active"=>$empBindingModel->getActive()
        ];


        $createQuery = new CreatingQueryService();
        $createQuery->setValues($updatePropArray);
        $createQuery->setQueryUpdateEmp($empBindingModel->getId());

        $query = "UPDATE employees SET ".$createQuery->getQuery();

        $stmt = $this->db->prepare($query);
        return $stmt->execute($createQuery->getValues());

    }

    public function removeEmp($empId) : bool {

        $query = "UPDATE 
                  employees 
                  SET 
                  active = ?  
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);

        return $stmt->execute(["no",$empId]);
    }


}