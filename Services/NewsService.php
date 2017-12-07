<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 26.11.2017 г.
 * Time: 12:46
 */

namespace Employees\Services;

use Employees\Adapter\DatabaseInterface;
use Employees\Models\Binding\News\NewsBindingModel;



class NewsService implements NewsServiceInterface
{
    private $db;

    public function __construct(DatabaseInterface $db)
    {
        $this->db = $db;
    }


    public function getAllNews($isActive)
    {
        $query = "SELECT * FROM news WHERE active = ?";

        $stmt = $this->db->prepare($query);

        $stmt->execute([$isActive]);

        $result = $stmt->fetchAll();

        return $result;
    }

    public function getNews($id)
    {
        $query = "SELECT
                 * FROM news
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);

        $stmt->execute([$id]);

        $result = $stmt->fetchAll();

        return $result;
    }

    public function getNewsByStrId($uniqueStr) : array {
        $query = "SELECT 
                  id,
                  title,
                  date,
                  body,
                  image 
                  FROM news WHERE unique_str_code = ? AND active = ?";

        $stmt = $this->db->prepare($query);

        $stmt->execute([$uniqueStr, "yes"]);
        $result = $stmt->fetch();

        return $result;
    }

    public function addNews(NewsBindingModel $newsBindingModel, $uniqueStr) : bool
    {

        $query = "INSERT INTO 
                  news (
                  admin_id,
                  active,
                  date,
                  author,
                  title,
                  body,
                  image, 
                  unique_str_code
                  ) 
                  VALUES (?,?,?,?,?,?,?,?)";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            $newsBindingModel->getAdminId(),
            "yes",
            $newsBindingModel->getDate(),
            $newsBindingModel->getAuthor(),
            $newsBindingModel->getTitle(),
            "body",
            //$newsBindingModel->getBody(),
            "TEST",
            //$newsBindingModel->getImage(),
            $uniqueStr
        ]);
    }

    public function updateNews(NewsBindingModel $bindingModel) : bool
    {
        $arr = [
            "author"=>$bindingModel->getAuthor(),
            "title"=>$bindingModel->getTitle(),
            "body"=>$bindingModel->getBody(),
            "image"=>$bindingModel->getImage()
        ];

        $createQuery = new CreatingQueryService();
        $createQuery->setValues($arr);
        $createQuery->setQueryUpdateEmp($bindingModel->getId());

        $query = "UPDATE news SET ".$createQuery->getQuery();

        $stmt = $this->db->prepare($query);

        return $stmt->execute($createQuery->getValues());
    }

    public function removeNews($id)
    {
        $query = "UPDATE 
                  news 
                  SET 
                  active = ?  
                  WHERE id = ?";

        $stmt = $this->db->prepare($query);

        return $stmt->execute(["no",$id]);
    }


}