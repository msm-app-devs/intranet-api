<?php

use Employees\Adapter\Database;
use Employees\Config\DbConfig;
use Employees\Config\DefaultParam;
use Employees\Adapter\Ember;

//var_dump("TEST");
//exit;

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');
header('Content-Type: text/html; charset=utf-8');

session_start();
spl_autoload_register(function($class){
    $class = str_replace("Employees\\","", $class);
    $class = str_replace("\\",DIRECTORY_SEPARATOR, $class);
    //var_dump($class);
    require_once $class . '.php';

});


$uri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER["REQUEST_METHOD"];
$self = $_SERVER['PHP_SELF'];
$arguments = [];
$theMethod = new Ember($requestMethod);


$self = str_replace("index.php","",$self);
$uri = str_replace($self, '', $uri);
// $uri = substr($uri, 1);

//var_dump("$uri");
//exit;

$args = explode("/",$uri);

$controllerName = array_shift($args);
count($args) > 0 ? array_push($arguments,array_shift($args)) : $arguments ;
$actionName = $theMethod->getMethod();
//$actionName = array_shift($args);
$dbInstanceName = 'default';

//var_dump($args);
//exit;

//if ($controllerName == NULL || $actionName == NULL) {
//    $controllerName = DefaultParam::DefaultController;
//    $actionName = DefaultParam::DefaultAction;
//}

// $uri = substr($uri, 1);

//var_dump("$uri");
//exit;

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

//var_dump($arguments);
//exit;

$app = new \Employees\Core\Application($mvcContext);


$app->addClass(
    \Employees\Core\MVC\MVCContextInterface::class,
    $mvcContext
);

$app->addClass(
    \Employees\Core\MVC\SessionInterface::class,
    new \Employees\Core\MVC\Session($_SESSION)
);

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
    \Employees\Services\CreatingQuerySevice::class);

//$app->registerDependency(
//    \SoftUni\Services\CategoryServiceInterface::class,
//    \SoftUni\Services\CategoryService::class
//);


$app->start();
