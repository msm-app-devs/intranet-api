<?php

namespace Employees\Controllers;


use Employees\Models\Binding\Emp\EmpBindingModel;
use Employees\Services\EmployeesServiceInterface;

class EmployeesController
{

    public function __construct(EmployeesServiceInterface $employeesService)
    {
        $this->employeeService = $employeesService;
    }

    public function list($active = null)
    {
        if ($active == null) {

            print_r(json_encode(array("employee" => $this->employeeService->getList())));

        } else {

            print_r(json_encode(array("employee" => $this->employeeService->getListStatus($active))));

        }

    }

    public function removeEmployee($id) {

        if ($this->employeeService->removeEmp($id)) {
            print_r("true");
        } else {
            print_r("false");
        }

    }


    public function addemployee(EmpBindingModel $employeeBindingModel)
    {

        if ($this->employeeService->addEmp($employeeBindingModel)) {
//            print_r($employeeBindingModel->getPosition());
            print_r("true");
        } else {
            print_r("false");
        }
    }

    public function getemployee($id) {

        print_r(json_encode($this->employeeService->getEmp($id)));

    }

    public function updateemployee(EmpBindingModel $empBindingModel) {
        if ($this->employeeService->updEmp($empBindingModel)) {
            print_r(json_encode(array("employee" => $this->employeeService->getEmp($empBindingModel->getId()))));
        } else {
            print_r("false");
        }
    }

}