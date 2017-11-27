<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 26.11.2017 г.
 * Time: 12:14
 */

namespace Employees\Controllers;


use Employees\Models\Binding\News\NewsBindingModel;
use Employees\Services\AuthenticationServiceInterface;
use Employees\Services\CreatingQueryServiceInterface;
use Employees\Services\EncryptionServiceInterface;
use Employees\Services\NewsServiceInterface;

class NewsController
{
    private $newsService;
    private $encryptionService;
    private $createQuery;
    private $authenticationService;


    public function __construct(NewsServiceInterface $newsService,
                                EncryptionServiceInterface $encryptionService,
                                CreatingQueryServiceInterface $createQuery,
                                AuthenticationServiceInterface $authenticationService)
    {
        $this->newsService = $newsService;
        $this->encryptionService = $encryptionService;
        $this->createQuery = $createQuery;
        $this->authenticationService = $authenticationService;
    }

    public function getNews()
    {
        print_r(json_encode(array("news" => $this->newsService->getAllNews("yes"))));
    }

    public function addNews(NewsBindingModel $bindingModel)
    {
        $author = $this->authenticationService->getUserInfo();
        $now = date("d/m/y");

        $bindingModel->setDate($now);
        $bindingModel->setAuthor($author["first"]." ".$author["last"]);
        $bindingModel->setAdminId($author["id"]);


        if ($this->newsService->addNews($bindingModel)) {
            print_r("true");
        } else {
            print_r("false");
        }
    }

//    public function updateNews(NewsBindingModel $bindingModel)
//    {
////        var_dump($bindingModel);
//        foreach ($bindingModel as $key=>$value) {
//            var_dump($key);
//        }
//    }


    public function deleteNews()
    {

    }

}