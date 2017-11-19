<?php
/**
 * Created by PhpStorm.
 * User: svetoslav.bozhinov
 * Date: 18.11.2017 г.
 * Time: 18:41
 */

namespace Employees\Controllers;



use Employees\Core\MVC\KeyHolder;
use Employees\Models\Binding\Users\UserLoginBindingModel;
use Employees\Services\AuthenticationServiceInterface;
use Employees\Services\ResponseServiceInterface;
use Employees\Services\UserServiceInterface;

class AdminController
{

    private $userService;
    private $authenticationService;
    private $responseService;

    public function __construct(UserServiceInterface $userService,
                                AuthenticationServiceInterface $authenticationService,
                                ResponseServiceInterface $responseService)
    {
        $this->authenticationService = $authenticationService;
        $this->userService = $userService;
        $this->responseService = $responseService;
    }

    public function token(UserLoginBindingModel $bindingModel) {

            $username = $bindingModel->getUsername();
            $password = $bindingModel->getPassword();

            $admin = $this->userService->login($username, $password);

            if ($admin != null) {

                $token = bin2hex(openssl_random_pseudo_bytes(64));

                if ($this->userService->userToken(1, $token)) {

                    print_r(json_encode(array("access_token" => $token)));

                }



            } else {
                print_r(json_encode(array("access_token" => "")));
            }



        //throw new \Exception();
     }

     public function addNews() {

     }




}