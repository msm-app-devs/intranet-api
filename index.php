<?php

use Employees\Adapter\Database;
use Employees\Config\DbConfig;
use Employees\Config\DefaultParam;

//var_dump("TEST");
//exit;

session_start();
spl_autoload_register(function($class){
    $class = str_replace("Employees\\","", $class);
    $class = str_replace("\\",DIRECTORY_SEPARATOR, $class);
    //var_dump($class);
    require_once $class . '.php';

});

$uri = $_SERVER['REQUEST_URI'];
$self = $_SERVER['PHP_SELF'];
$self = str_replace("index.php","",$self);
$uri = str_replace($self, '', $uri);
// $uri = substr($uri, 1);

//var_dump("$uri");
//exit;



$args = explode("/",$uri);
$controllerName = array_shift($args);
$actionName = array_shift($args);
$dbInstanceName = 'default';

if ($controllerName == NULL || $actionName == NULL) {
    $controllerName = DefaultParam::DefaultController;
    $actionName = DefaultParam::DefaultAction;
}

Database::setInstance(
    DbConfig::DB_HOST,
    DbConfig::DB_USER,
    DbConfig::DB_PASSWORD,
    DbConfig::DB_NAME,
    $dbInstanceName
);

//var_dump($controllerName);
//exit;

$mvcContext = new \Employees\Core\MVC\MVCContext(
    $controllerName,
    $actionName,
    $self,
    $args
);



$app = new \Employees\Core\Application($mvcContext);


$app->addClass(
    \Employees\Core\MVC\MVCContextInterface::class,
    $mvcContext
);

$app->addclass(
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

//$app->registerDependency(
//    \SoftUni\Services\CategoryServiceInterface::class,
//    \SoftUni\Services\CategoryService::class
//);


$app->start();
