<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 18/08/2017
 * Time: 16:11
 */

namespace Employees\Services;


use Employees\Models\Binding\Emp\EmpBindingModel;

interface EmployeesServiceInterface
{

    public function getList();

    public  function getListStatus($active);

    public function addEmp(EmpBindingModel $model, $uniqueStrId);

    public function getEmpByStrId($strId);

    public function getEmp($id);

    public function getEmpUniqueStr($id);

//    public function updEmp(EmpBindingModel $model);
    public function updEmp(EmpBindingModel $empBindingModel);

    public function removeEmp($empId) : bool;
}