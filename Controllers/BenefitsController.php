<?php

namespace Employees\Controllers;



use Employees\Services\AuthenticationServiceInterface;
use Employees\Services\BenefitsServiceInterface;
use Employees\Services\EncryptionServiceInterface;
use Employees\Services\ImageFromBinServiceInterface;
use Employees\Core\DataReturnInterface;

class BenefitsController
{

    private $benefitsService;
    private $encryptionService;
    private $authenticationService;
    private $binaryImage;
    private $dataReturn;

    public function __construct(
        BenefitsServiceInterface $benefitsService,
        EncryptionServiceInterface $encryptionService,
                                AuthenticationServiceInterface $authenticationService,
                                ImageFromBinServiceInterface $binService,
                                DataReturnInterface $dataReturn)
    {
        $this->benefitsService = $benefitsService;
        $this->encryptionService = $encryptionService;
        $this->authenticationService = $authenticationService;
        $this->binaryImage = $binService;
        $this->dataReturn = $dataReturn;
    }

    public function option()
    {

    }

    public function list()
    {
        $result  = $this->benefitsService->listbenefits();
        $newArr = [];

        foreach($result as $res) {
          $temparr = $res;
          $temparr["type"] = "benefit";
          array_push($newArr, $temparr);
        }
        $newArr[0]["files"] = [1,2,3,4];
        $newArr[1]["files"] = [5,6,7];

        return $this->dataReturn->jsonData($newArr);
    }

//    public function listTEST()
//    {
//        $result  = $this->benefitsService->listbenefitstest();
//        var_dump($result);
//        exit;
//        $newArr = [];
//
//        foreach($result as $res) {
//            $temparr = $res;
//            $temparr["type"] = "benefit";
//            array_push($newArr, $temparr);
//        }
//        $newArr[0]["relationships"] = array("files" => array("data"=>array(array("type"=>"file", "id" => 1))));
//        $newArr[1]["relationships"] = array("files" => array("data"=>array(array("type"=>"file", "id" => 2))));
//
//        return $this->dataReturn->jsonData($newArr);
//    }

}