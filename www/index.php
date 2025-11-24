<?php 

namespace App;

use App\Controller\Auth;
use App\Model\User;

spl_autoload_register(function ($class){
        $namespaceArray = [
                            "namespace"=> ["App\\Controller\\", "App\\Core\\", "App\\Model\\"],
                            "path"=> ["Controllers/", "Core/", "Models/"],
                        ];
        $filname = str_ireplace($namespaceArray['namespace'],$namespaceArray['path'], $class  ). ".php";
        if(file_exists($filname)) {
            include $filname;
        }
    }
);


$newUser = new User();
