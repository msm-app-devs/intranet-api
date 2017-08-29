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
        print_r(htmlspecialchars(json_encode($this->employeeService->getList())));
//        print_r(htmlspecialchars("<script>window.alert('ZZZZZZZ')</script>"));
    }

    public function addemployee(EmpBindingModel $employeeBindingModel)
    {
        if ($this->employeeService->addEmp($employeeBindingModel)) {
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
            print_r("true");
        } else {
            print_r("false");
        }
    }

}