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

    public function option() {

    }

    public function getNews()
    {
        print_r(json_encode(array("news" => $this->newsService->getAllNews("yes"))));
    }

    public function addNews(NewsBindingModel $bindingModel)
    {
        if ($this->authenticationService->isTokenCorrect()) {
            $author = $this->authenticationService->getUserInfo();
            $now = date("d/m/y");

            $bindingModel->setDate($now);
            $bindingModel->setAuthor($author["first"] . " " . $author["last"]);
            $bindingModel->setAdminId($author["id"]);


            $md5string = $this->encryptionService->md5generator(time() . $bindingModel->getTitle() . $bindingModel->getBody());

            if ($this->newsService->addNews($bindingModel, $md5string)) {
                print_r(json_encode(array("news" => $this->newsService->getNewsByStrId($md5string))));
            } else {
                print_r("false");
            }
        } else {
            http_response_code("404");
        }
    }

    public function updateNews($theId,NewsBindingModel $bindingModel)
    {
        if ($this->authenticationService->isTokenCorrect()) {
            $bindingModel->setId($theId);

            $this->newsService->updateNews($bindingModel);

            if ($this->newsService->updateNews($bindingModel)) {
                print_r(json_encode(array("news" => $this->newsService->getNews($theId))));
            } else {
                print_r("false");
            }
        } else {
            http_response_code("404");
        }
    }


    public function deleteNews($newsId)
    {
        if ($this->authenticationService->isTokenCorrect()) {
            if ($this->newsService->removeNews($newsId)) {
                print_r("true");
            } else {
                print_r("false");
            }
        } else {
            http_response_code("404");
        }
    }

}