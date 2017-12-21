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
use Employees\Services\ImageFromBinServiceInterface;
use Employees\Services\NewsServiceInterface;
use Employees\Config\DefaultParam;

class NewsController
{
    private $newsService;
    private $encryptionService;
    private $createQuery;
    private $authenticationService;
    private $binaryImage;

    public function __construct(NewsServiceInterface $newsService,
                                EncryptionServiceInterface $encryptionService,
                                CreatingQueryServiceInterface $createQuery,
                                AuthenticationServiceInterface $authenticationService,
                                ImageFromBinServiceInterface $imageFromBinService)
    {
        $this->newsService = $newsService;
        $this->encryptionService = $encryptionService;
        $this->createQuery = $createQuery;
        $this->authenticationService = $authenticationService;
        $this->binaryImage = $imageFromBinService;
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

            if ($this->binaryImage->createImage($bindingModel->getImage(), DefaultParam::NewsImageContainer, $md5string, "png")) {

                $bindingModel->setImage($md5string . ".png");

                if ($this->newsService->addNews($bindingModel, $md5string)) {
                    $newsList = $this->newsService->getNewsByStrId($md5string);
                    $newsList["image"] = DefaultParam::ServerRoot.DefaultParam::NewsImageContainer.$newsList['image'];
                    print_r(json_encode(array("news" => $newsList)));

                } else {
                    $this->binaryImage->removeImage(DefaultParam::NewsImageContainer.$md5string.".png");
                    print_r("Add news failed");
                }
            } else {
                print_r("Image upload failed");
            }
        } else {
            http_response_code("404");
        }

    }

    public function updateNews($theId,NewsBindingModel $bindingModel)
    {
        $bindingModel->setId($theId);

        $this->newsService->updateNews($bindingModel);

        if ($this->newsService->updateNews($bindingModel)) {
            print_r(json_encode(array("news" => $this->newsService->getNews($theId))));
        } else {
            print_r("false");
        }
    }


    public function deleteNews($newsId)
    {
        if ($this->newsService->removeNews($newsId)) {
            print_r("true");
        }
        else {
            print_r("false");
        }
    }

}