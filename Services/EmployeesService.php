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

    public function getEmp($id) {
        $query = "SELECT 
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
        $result = $stmt->fetchAll();

        return $result;
    }

    public function addEmp(EmpBindingModel $model)
    {
        $query = "INSERT INTO
                  employees (
                  ext_id,
                  first_name,
                  last_name,
                  position,
                  team,
                  start_date,
                  birthday
                  )
                  VALUES(?,?,?,?,?,?,?)";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $model->getExtId(),
            $model->getFirstName(),
            $model->getLastName(),
            $model->getPosition(),
            $model->getTeam(),
            $model->getStartDate(),
            $model->getBirthday()
        ]);

    }


}