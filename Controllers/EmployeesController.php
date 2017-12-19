<?php

namespace Employees\Controllers;



use Employees\Models\Binding\Emp\EmpBindingModel;
use Employees\Services\AuthenticationServiceInterface;
use Employees\Services\CreatingQueryServiceInterface;
use Employees\Services\EmployeesServiceInterface;
use Employees\Services\EncryptionServiceInterface;
use Employees\Services\ImageFromBinServiceInterface;

class EmployeesController
{

    private $employeeService;
    private $encryptionService;
    private $createQuery;
    private $authenticationService;
    private $binaryImage;

    public function __construct(EmployeesServiceInterface $employeesService,
                                EncryptionServiceInterface $encryptionService,
                                CreatingQueryServiceInterface $createQuery,
                                AuthenticationServiceInterface $authenticationService,
                                ImageFromBinServiceInterface $binService)
    {
        $this->employeeService = $employeesService;
        $this->encryptionService = $encryptionService;
        $this->createQuery = $createQuery;
        $this->authenticationService = $authenticationService;
        $this->binaryImage = $binService;
    }

    public function option()
    {

    }

    public function list($active = null)
    {

        //if ($this->authenticationService->isTokenCorrect()) {

            if ($active == null) {

                print_r(json_encode(array("employee" => $this->employeeService->getListStatus("yes"))));

            } else {

                print_r(json_encode(array("employee" => $this->employeeService->getEmp($active))));

            }

        //}

    }

    public function removeEmployee($id) {
         if ($this->authenticationService->isTokenCorrect()) {

            if ($this->employeeService->removeEmp($id)) {
                print_r("true");
            } else {
                print_r("false");
            }
         } else {
             http_response_code("404");
         }
    }


    public function addemployee(EmpBindingModel $employeeBindingModel)
    {

//        var_dump("TEST");
//        exit;
        if ($this->authenticationService->isTokenCorrect()) {


        $md5string = $this->encryptionService->md5generator($employeeBindingModel->getFirstName().
            $employeeBindingModel->getLastName().
            $employeeBindingModel->getBirthday().time());

            if($this->binaryImage->CreateImage($employeeBindingModel->getImage(), $md5string, "jpg")) {
                $employeeBindingModel->setImage("http://localhost:80/intranet-api/webroot/images/".$md5string.".jpg");
                if ($this->employeeService->addEmp($employeeBindingModel, $md5string)) {


//            print_r($employeeBindingModel->getPosition());

                    print_r(json_encode(array("employees" =>$this->employeeService->getEmpByStrId($md5string))));
                } else {
                    print_r("false");
                }
            } else {
                    print_r("Image upload failed");
            }
        } else {
            http_response_code("404");
        }
    }

    public function getemployee($id) {

        print_r(json_encode($this->employeeService->getEmp($id)));

    }

    public function updateemployee($theid, EmpBindingModel $empBindingModel)
    {

        if ($this->authenticationService->isTokenCorrect()) {

            $empBindingModel->setId($theid);

            //        if ($this->employeeService->updEmp($empBindingModel)) {
            if ($this->employeeService->updEmp($empBindingModel)) {
                print_r(json_encode(array("employees" => $this->employeeService->getEmp($empBindingModel->getId()))));
            } else {
                print_r("false");
            }

           } else {
            http_response_code("404");
        }
    }

}