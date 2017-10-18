<?php

namespace Employees\Controllers;


use Employees\Models\Binding\Emp\EmpBindingModel;
use Employees\Services\CreatingQueryServiceInterface;
use Employees\Services\EmployeesServiceInterface;
use Employees\Services\EncryptionServiceInterface;
use Employees\Services\CreatingQuerySevice;

class EmployeesController
{

    private $employeeService;
    private $encryptionService;
    private $createQuery;

    public function __construct(EmployeesServiceInterface $employeesService, EncryptionServiceInterface $encryptionService, CreatingQueryServiceInterface $createQuery)
    {
        $this->employeeService = $employeesService;
        $this->encryptionService = $encryptionService;
        $this->createQuery = $createQuery;
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

        $md5string = $this->encryptionService->md5generator($employeeBindingModel->getFirstName().
            $employeeBindingModel->getLastName().
            $employeeBindingModel->getBirthday());

        if ($this->employeeService->addEmp($employeeBindingModel, $md5string)) {
//            print_r($employeeBindingModel->getPosition());
            print_r(json_encode(array("employee" => $this->employeeService->getEmpByStrId($md5string))));
        } else {
            print_r("false");
        }
    }

    public function getemployee($id) {

        print_r(json_encode($this->employeeService->getEmp($id)));

    }

    public function updateemployee(EmpBindingModel $empBindingModel) {


        $this->createQuery->setQueryUpdateEmp($empBindingModel);

//        if ($this->employeeService->updEmp($empBindingModel)) {
        if ($this->employeeService->updEmp($this->createQuery->getQuery(), $this->createQuery->getValues())) {
            print_r(json_encode(array("employee" => $this->employeeService->getEmp($empBindingModel->getId()))));
        } else {
            print_r("false");
        }
    }

}