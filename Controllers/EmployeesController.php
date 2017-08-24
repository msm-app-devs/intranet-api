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

    public function list()
    {
        echo(json_encode($this->employeeService->getList()));
    }

    public function addemployee(EmpBindingModel $employeeBindingModel)
    {
        if  ($this->employeeService->addEmp($employeeBindingModel)) {
            echo "true";
        } else {
            echo "false";
        }
    }

    public function getemployee($id) {

        echo json_encode($this->employeeService->getEmp($id));

    }

    public function updateEmp(EmpBindingModel $empBindingModel) {

    }

}