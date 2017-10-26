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
                  birthday 
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
                  birthday 
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
                  birthday 
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
                  birthday 
                  FROM employees WHERE unique_str_code = ?";

        $stmt = $this->db->prepare($query);

        $stmt->execute([$strId]);
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
                  active,
                  unique_str_code
                  )
                  VALUES (?,?,?,?,?,?,?,?)";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $model->getFirstName(),
            $model->getLastName(),
            $model->getPosition(),
            $model->getTeam(),
            $model->getStartDate(),
            $model->getBirthday(),
            $model->getActive(),
            $uniqueStrId
        ]);

    }

//    public function updEmp(EmpBindingModel $model)
    public function updEmp($columns, $values)
    {

        $query = "UPDATE employees SET ".$columns;

        $stmt = $this->db->prepare($query);
        return $stmt->execute($values);
//                $query = "UPDATE
//                 employees
//                 SET
//                 first_name = ?,
//                 last_name = ?,
//                 position = ?,
//                 team = ?,
//                 start_date = ?,
//                 birthday = ?
//                 WHERE id = ?";
//
//        $stmt = $this->db->prepare($query);
//        return $stmt->execute(
//            [
////            $model->getFirstName(),
//            $model->getLastName(),
//            $model->getPosition(),
//            $model->getTeam(),
//            $model->getStartDate(),
//            $model->getBirthday(),
//            $model->getId()
//        ]
//        );
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