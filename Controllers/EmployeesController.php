<?php

namespace Employees\Controllers;



use Employees\Models\Binding\Emp\EmpBindingModel;
use Employees\Services\AuthenticationServiceInterface;
use Employees\Services\CreatingQueryServiceInterface;
use Employees\Services\EmployeesServiceInterface;
use Employees\Services\EncryptionServiceInterface;
use Employees\Services\ImageFromBinServiceInterface;
use Employees\Config\DefaultParam;
use Employees\Core\DataReturnInterface;

class EmployeesController
{

    private $employeeService;
    private $encryptionService;
    private $createQuery;
    private $authenticationService;
    private $binaryImage;
    private $dataReturn;

    public function __construct(EmployeesServiceInterface $employeesService,
                                EncryptionServiceInterface $encryptionService,
                                CreatingQueryServiceInterface $createQuery,
                                AuthenticationServiceInterface $authenticationService,
                                ImageFromBinServiceInterface $binService,
                                DataReturnInterface $dataReturn)
    {
        $this->employeeService = $employeesService;
        $this->encryptionService = $encryptionService;
        $this->createQuery = $createQuery;
        $this->authenticationService = $authenticationService;
        $this->binaryImage = $binService;
        $this->dataReturn = $dataReturn;
    }

    public function option()
    {

    }

    public function list($active = null)
    {

        //if ($this->authenticationService->isTokenCorrect()) {

            if ($active == null) {

                $list = $this->employeeService->getListStatus("yes");

            } else {

                $list =  $this->employeeService->getEmp($active);

            }
        if (is_array($list)) {

        foreach ($list as $key => $value) {

            if (array_key_exists("image", $list[$key])) {
                $list[$key]["image"] = DefaultParam::ServerRoot.DefaultParam::EmployeeContainer.$list[$key]["image"];
            }
        }
    }
//        print_r(json_encode(array("employee" => $list)));
        $this->dataReturn->jsonDataReturn($list);
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

            if($this->binaryImage->createImage($employeeBindingModel->getImage(), DefaultParam::EmployeeContainer, $md5string, "jpg")) {
                $employeeBindingModel->setImage($md5string.".jpg");
                if ($this->employeeService->addEmp($employeeBindingModel, $md5string)) {
                    $empArrray = $this->employeeService->getEmpByStrId($md5string);
                    $empArrray["image"] = DefaultParam::ServerRoot.DefaultParam::EmployeeContainer.$empArrray['image'];
                    print_r(json_encode(array("employees" =>$empArrray)));
                } else {
                    $this->binaryImage->removeImage(DefaultParam::EmployeeContainer.$md5string.".jpg");
                    print_r("Add new employee failed");
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
            $employee = $this->employeeService->getEmp($theid);
            $oldImage = $employee["image"];

            $isBinaryImage = preg_match("/^data:image\/(png|jpeg);base64,/",$empBindingModel->getImage()) > 0 ? true : false;

                if ($isBinaryImage) {

                    $md5string = $this->encryptionService->md5generator($empBindingModel->getFirstName().
                        $empBindingModel->getLastName().
                        $empBindingModel->getBirthday().time());

                        $this->binaryImage->createImage($empBindingModel->getImage(),DefaultParam::EmployeeContainer, $md5string, "jpg");
                        $empBindingModel->setImage($md5string.".jpg");
                }


            if ($this->employeeService->updEmp($empBindingModel)) {
                if ($isBinaryImage) {
                    $this->binaryImage->removeImage(DefaultParam::EmployeeContainer.$oldImage);
                }
                $updatedEmployee = $this->employeeService->getEmp($empBindingModel->getId());
                $updatedEmployee["image"] = DefaultParam::ServerRoot.DefaultParam::EmployeeContainer.$updatedEmployee["image"];
                print_r(json_encode(array("employees" => $updatedEmployee)));
            } else {
                print_r("false");
            }

           } else {
            http_response_code("404");
        }
    }

}