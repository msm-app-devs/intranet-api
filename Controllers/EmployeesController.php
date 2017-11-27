<?php

namespace Employees\Controllers;



use Employees\Models\Binding\Emp\EmpBindingModel;
use Employees\Services\AuthenticationServiceInterface;
use Employees\Services\CreatingQueryServiceInterface;
use Employees\Services\EmployeesServiceInterface;
use Employees\Services\EncryptionServiceInterface;

class EmployeesController
{

    private $employeeService;
    private $encryptionService;
    private $createQuery;
    private $authenticationService;

    public function __construct(EmployeesServiceInterface $employeesService,
                                EncryptionServiceInterface $encryptionService,
                                CreatingQueryServiceInterface $createQuery,
                                AuthenticationServiceInterface $authenticationService)
    {
        $this->employeeService = $employeesService;
        $this->encryptionService = $encryptionService;
        $this->createQuery = $createQuery;
        $this->authenticationService = $authenticationService;
    }

    public function option()
    {

    }

    public function list($active = null)
    {

//        if ($this->authenticationService->isTokenCorrect()) {

            if ($active == null) {

                print_r(json_encode(array("employee" => $this->employeeService->getListStatus("yes"))));

            } else {

                print_r(json_encode(array("employee" => $this->employeeService->getEmp($active))));

            }

//        }

    }

    public function removeEmployee($id) {
         if ($this->authenticationService->isTokenCorrect()) {

            if ($this->employeeService->removeEmp($id)) {
                print_r("true");
            } else {
                print_r("false");
            }
         }
    }


    public function addemployee(EmpBindingModel $employeeBindingModel)
    {

//        var_dump("TEST");
//        exit;
        if ($this->authenticationService->isTokenCorrect()) {


        $md5string = $this->encryptionService->md5generator($employeeBindingModel->getFirstName().
            $employeeBindingModel->getLastName().
            $employeeBindingModel->getBirthday());

//        var_dump($this->employeeService->getEmpByStrId("7702b7559a2ac1acf907eab6d2f091d5"));
//        $this->employeeService->getEmpByStrId("4e48ba9aeff940707008150a1bc641b2");
//        var_dump(json_encode($this->employeeService->getEmpByStrId("4e48ba9aeff940707008150a1bc641b2"), JSON_FORCE_OBJECT));
//        print_r(json_encode(array("employees" => $this->employeeService->getEmpByStrId("4e48ba9aeff940707008150a1bc641b2"))));
//        exit;


        if ($this->employeeService->addEmp($employeeBindingModel, $md5string)) {
//            print_r($employeeBindingModel->getPosition());
            print_r(json_encode(array("employees" =>$this->employeeService->getEmpByStrId($md5string))));
        } else {
            print_r("false");
        }

        }
    }

    public function getemployee($id) {

        print_r(json_encode($this->employeeService->getEmp($id)));

    }

    public function updateemployee($theid, EmpBindingModel $empBindingModel)
    {

        if ($this->authenticationService->isTokenCorrect()) {

            $empBindingModel->setId($theid);

            $this->createQuery->setQueryUpdateEmp($empBindingModel);

            //        if ($this->employeeService->updEmp($empBindingModel)) {
            if ($this->employeeService->updEmp($this->createQuery->getQuery(), $this->createQuery->getValues())) {
                print_r(json_encode(array("employees" => $this->employeeService->getEmp($empBindingModel->getId()))));
            } else {
                print_r("false");
            }

           } else {
            http_response_code("404");
        }
    }

}