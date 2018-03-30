<?php

namespace Employees\Controllers;



use Employees\Services\AuthenticationServiceInterface;
use Employees\Services\BenefitsServiceInterface;
use Employees\Services\EncryptionServiceInterface;
use Employees\Services\ImageFromBinServiceInterface;
use Employees\Core\DataReturnInterface;

class FilesController
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
//        data: {
//        type: 'file',
//     fileName: 'аааааааааа',
//     fileDate: '13/11/2017',
//     fileSize: '35kb',
//     fileType: 'doc',
//     id: 1,
//     relationships: {
//            benefit: {
//                data: { type: 'benefit', id: 1 }
//            }
//        }
//   }
        $testArr = array(array(
                "type"=>"file",
                "fileName"=>"Document 1",
                "fileDate"=>"13/11/2017",
                "fileSize"=>"61kb",
                "fileType"=>"word",
                "filePath" => "http://q1q1.eu/employees/webroot/files/benefits/benefit1/file1.docx",
                "id"=>"1"
            ), array(
                "type"=>"file",
                "fileName"=>"Document 2",
                "fileDate"=>"13/11/2017",
                "fileSize"=>"40kb",
                "fileType"=>"pdf",
                "filePath"=>"",
                "id"=>"2"
            ), array(
                "type"=>"file",
                "fileName"=>"Document 3",
                "fileDate"=>"13/11/2017",
                "fileSize"=>"600kb",
                "fileType"=>"powerpoint",
                "filePath" => "http://q1q1.eu/employees/webroot/files/benefits/benefit1/file3.pptx",
                "id"=>"3"
            ), array(
                "type"=>"file",
                "fileName"=>"Document 4",
                "fileDate"=>"13/11/2017",
                "fileSize"=>"600kb",
                "fileType"=>"excel",
                "filePath" => "http://q1q1.eu/employees/webroot/files/benefits/benefit1/file4.xlsx",
                "id"=>"4"
            ), array(
                "type"=>"file",
                "fileName"=>"Document 1",
                "fileDate"=>"27/01/2018",
                "fileSize"=>"61kb",
                "fileType"=>"excel",
                "filePath" => "http://q1q1.eu/employees/webroot/files/benefits/benefit1/file1.xlsx",
                "id"=>"5"
            ), array(
                "type"=>"file",
                "fileName"=>"Document 2",
                "fileDate"=>"27/01/2018",
                "fileSize"=>"40kb",
                "fileType"=>"pdf",
                "filePath"=>"",
                "id"=>"6"
            ), array(
                "type"=>"file",
                "fileName"=>"Document 3",
                "fileDate"=>"27/01/2018",
                "fileSize"=>"600kb",
                "fileType"=>"powerpoint",
                "filePath" => "http://q1q1.eu/employees/webroot/files/benefits/benefit1/file3.pptx",
                "id"=>"7"
            )
        );
        print_r(json_encode(array("files"=>$testArr)));
    }

}