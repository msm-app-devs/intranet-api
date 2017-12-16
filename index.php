<?php

use Employees\Adapter\Database;
use Employees\Config\DbConfig;
use Employees\Config\DefaultParam;
use Employees\Adapter\Ember;
use Employees\Core\MVC\KeyHolder;

//TEST TEST TEST

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
//header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description, Origin');
header('Access-Control-Allow-Headers: Content-Type, Origin, Authorization');
//header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description, Origin, X-Auth-Token, authorization');
//header('X-Auth-Token: test1123');
//header('Content-Type: text/html; charset=utf-8');
header('Content-Type: application/json; charset=utf-8');



//var_dump($_FILES);
//exit;
//
//$post_vars = [];
//
//
//
//parse_str(file_get_contents("php://input"),$post_vars);
//var_dump(file_get_contents("php://input"));
//exit;
//
//var_dump($post_vars);
//exit;
//$arr = json_decode(file_get_contents("php://input"));
////$arr2 = fopen("php://input", "r");
////var_dump(stream_getli);
//var_dump($arr->employee->image->name);
//var_dump($_FILES);

//var_dump($_FILES);
//var_dump(file_get_contents("php://input"));
//
//move_uploaded_file($_FILES["file"]["tmp_name"], "webroot/images/".$_FILES["file"]["name"]);
//if(move_uploaded_file($arr->employee->image, "webroot/images/test.png")) {
//    var_dump("Uploaded");
//} else {
//    var_dump("NOT");
//}
//
//$test = file_get_contents("php://input", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
//$arr = array($test);
//var_dump($arr);
//var_dump($_POST);
//var_dump($_FILES);
//exit;

spl_autoload_register(function($class){
    $class = str_replace("Employees\\","", $class);
    $class = str_replace("\\",DIRECTORY_SEPARATOR, $class);

    require_once $class . '.php';
});

//$arr = [];
//
//parse_str(file_get_contents("php://input"), $arr);
//var_dump($arr);
//var_dump(file_get_contents("php://input"));
//exit;
//var_dump(json_decode(file_get_contents("php://input"),true));
//exit;


$uri = $_SERVER['REQUEST_URI']; // URI
$requestMethod = $_SERVER["REQUEST_METHOD"]; //requested method
$self = $_SERVER['PHP_SELF'];

$arguments = [];



$self = str_replace("index.php","",$self);

$uri = str_replace($self, '', $uri);

// $uri = substr($uri, 1);

//var_dump("$uri");
//exit;
/* PUT data comes in on the stdin stream */
//$putdata = fopen("php://input", "r");
//fclose(fopen("webroot/images/myputfile.png", "x"));
//
///* Open a file for writing */
//$fp = fopen("webroot/images/myputfile.png", "w");
//
///* Read the data 1 KB at a time
//   and write to the file */
//while ($data = fread($putdata, 1024))
//    fwrite($fp, $data);
//
///* Close the streams */
//fclose($fp);
//fclose($putdata);
//
////var_dump(fopen());
//exit;

$args = explode("/",$uri);

$theMethod = new Ember(array_shift($args), $requestMethod);

$controllerName = $theMethod->getController();
count($args) > 0 ? array_push($arguments,array_shift($args)) : $arguments ;
$actionName = $theMethod->getMethod();

$testArray = [];

//$testArray["tmpname"] = $_FILES["employee"]["tmp_name"]["image"];
//$testArray["imgname"] = $_FILES["employee"]["name"]["image"];
//
//if(move_uploaded_file($testArray["tmpname"], "webroot/images/".$testArray["imgname"])) {
//    var_dump("Uploaded");
//} else {
//    var_dump("NOT");
//}
//exit;

//$actionName = array_shift($args);
$dbInstanceName = 'default';
$headers = [];
$keyHolds = "";


if ($requestMethod != "OPTIONS") {

    $headers = getallheaders();

    if (array_key_exists("Authorization", $headers)) {
        $keyHolds = $headers["Authorization"];
    }

}


Database::setInstance(
    DbConfig::DB_HOST,
    DbConfig::DB_USER,
    DbConfig::DB_PASSWORD,
    DbConfig::DB_NAME,
    $dbInstanceName
);


$mvcContext = new \Employees\Core\MVC\MVCContext(
    $controllerName,
    $actionName,
    $self,
    $arguments
//    $args
);



$app = new \Employees\Core\Application($mvcContext);


$app->addClass(
    \Employees\Core\MVC\MVCContextInterface::class,
    $mvcContext
);

$app->addClass(
    \Employees\Core\MVC\SessionInterface::class,
        new \Employees\Core\MVC\Session($_SESSION)
);

    $app->addClass(\Employees\Core\MVC\KeyHolderInterface::class,
        new \Employees\Core\MVC\KeyHolder($keyHolds));

$app->addClass(
 \Employees\Adapter\DatabaseInterface::class,
    Database::getInstance($dbInstanceName)
);

$app->registerDependency(
    \Employees\Core\ViewInterface::class,
    \Employees\Core\View::class
);

$app->registerDependency(
    \Employees\Services\UserServiceInterface::class,
    \Employees\Services\UserService::class
);

$app->registerDependency(
    \Employees\Services\EmployeesServiceInterface::class,
    \Employees\Services\EmployeesService::class
);

$app->registerDependency(
    \Employees\Services\EncryptionServiceInterface::class,
    \Employees\Services\BCryptEncryptionService::class
);

$app->registerDependency(
    \Employees\Services\AuthenticationServiceInterface::class,
    \Employees\Services\AuthenticationService::class
);
$app->registerDependency(
    \Employees\Services\ResponseServiceInterface::class,
    \Employees\Services\ResponseService::class
);

$app->registerDependency(\Employees\Services\CreatingQueryServiceInterface::class,
    \Employees\Services\CreatingQueryService::class);

$app->registerDependency(\Employees\Services\NewsServiceInterface::class,
    \Employees\Services\NewsService::class
    );

$app->registerDependency(\Employees\Services\FileUploadServiceInterface::class,
    \Employees\Services\FileUploadService::class
);

$app->start();
