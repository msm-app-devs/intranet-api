<?php

namespace Employees\Controllers;



use Employees\Models\Binding\Emp\EmpBindingModel;
use Employees\Services\AuthenticationServiceInterface;
use Employees\Services\CreatingQueryServiceInterface;
use Employees\Services\EmployeesServiceInterface;
use Employees\Services\EncryptionServiceInterface;
use Employees\Services\FileUploadService;
use Employees\Services\FileUploadServiceInterface;

class EmployeesController
{

    private $employeeService;
    private $encryptionService;
    private $createQuery;
    private $authenticationService;
    private $fileUploadService;

    public function __construct(EmployeesServiceInterface $employeesService,
                                EncryptionServiceInterface $encryptionService,
                                CreatingQueryServiceInterface $createQuery,
                                AuthenticationServiceInterface $authenticationService,
                                FileUploadServiceInterface $fileUploadService
                                )
    {
        $this->employeeService = $employeesService;
        $this->encryptionService = $encryptionService;
        $this->createQuery = $createQuery;
        $this->authenticationService = $authenticationService;
        $this->fileUploadService = $fileUploadService;
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

        if ($this->authenticationService->isTokenCorrect()) {

            $md5string = $this->encryptionService->md5generator($employeeBindingModel->getFirstName() .
                $employeeBindingModel->getLastName() .
                $employeeBindingModel->getBirthday() . time());

            $fileProp = $employeeBindingModel->getImageprop();

            $fileType = strtolower(pathinfo($fileProp["name"]["imageThumb"],PATHINFO_EXTENSION));

            $employeeBindingModel->setImageName($md5string.".".$fileType);

            if ($this->fileUploadService->uploadFile($fileProp["tmp_name"]["imageThumb"], "webroot/images", $md5string.".".$fileType)) {

                if ($this->employeeService->addEmp($employeeBindingModel, $md5string)) {

                    print_r(json_encode(array("employees" => $this->employeeService->getEmpByStrId($md5string))));

                } else {
                    $this->fileUploadService->removeFile($md5string.$fileType, "webroot/images");
                    print_r("false");
                }
            } else {
                print_r("NOT LOADED");
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

        $putdata = fopen("php://input", "r");

        /* Open a file for writing */
        $fp = fopen("webroot/images/rb.png", "w");

        /* Read the data 1 KB at a time
           and write to the file */
        while ($data = fread($putdata, 1024))
            fwrite($fp, $data);

        /* Close the streams */
        fclose($fp);
        fclose($putdata);
        exit;

        if ($this->authenticationService->isTokenCorrect()) {

            $empBindingModel->setId($theid);
            $fileType = end(explode($empBindingModel->getImageName(),"."));
            $imgTempObj = new FileUploadService("tmp".$empBindingModel->getImageName(), "webroot/images", end(explode($empBindingModel->getImageName(),".")));
            $imgObj = new FileUploadService($empBindingModel->getImageName(), "webroot/images", end(explode($empBindingModel->getImageName(),".")));

            if ($this->employeeService->updEmp($empBindingModel)) {
                print_r(json_encode(array("employees" => $this->employeeService->getEmp($empBindingModel->getId()))));
            } else {
                print_r("false");
            }

        } else {
            http_response_code("404");
        }
    }

    public function updateemployeeimage($empId, EmpBindingModel $empBindingModel)
    {
        if ($this->authenticationService->isTokenCorrect()) {

            $empBindingModel->setId($empId);
            $fileProp = $empBindingModel->getImageprop();
            $employeeUniqueStr = $this->employeeService->getEmpUniqueStr($empId);

            var_dump($fileProp);
            exit;
//            if ($fileObj->uploadFile( "webroot/images")) {
//                print_r(json_encode(array("temp_file_uploaded"=>"yes")));
//            } else {
//                print_r("FALSE");
//            }
        }
    }

}