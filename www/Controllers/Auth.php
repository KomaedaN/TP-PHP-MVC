<?php

namespace App\Controller;

use App\Core\Render;
use App\Controller\Base;
use App\Service\AuthService;
use App\Service\EmailVerificationService;
class Auth extends Base
{

    public function signin(): void{
        $errors = [];
        
        if(
            !empty($_POST["email"]) &&
            !empty($_POST["pwd"]) &&
            count($_POST) == 2
        ){
            $email = strtolower(trim($_POST['email']));

            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors[]="Votre email n'est pas correct";
            $this->renderPage("login", "frontoffice", $errors);
            }else{
                $auth = new AuthService();
                $userId = $auth->getUserIdFromMail($email);
                if($userId){
                    $password = $_POST["pwd"];
                    $passwordMatch = $auth->verifyPassword($email, $password);
                    if($passwordMatch){
                        $_SESSION['user_id'] = $userId;
                        $this->renderPage("dashboard", "frontoffice");
                    } else {
                         $errors[]="Mot de passe incorrect";
                        $this->renderPage("login", "frontoffice", ["errors" => $errors]);
                    }
                } else {
                    $errors[]= "L'email n'existe pas en bdd";
                   $this->renderPage("login", "frontoffice", ["errors" => $errors]);
                }
            }   
        } else {
            echo "Tentative de XSS";
            $this->renderPage("login", "frontoffice");
        }
    }

    public function signup(): void{
        $errors = [];
         if(
        isset($_POST['name']) &&
        !empty($_POST['email']) &&
        !empty($_POST['pwd']) &&
        !empty($_POST['pwdConfirm']) &&
        count($_POST) == 4
    ){
        //Nettoyage de la donnée
        $name = ucwords(strtolower(trim($_POST['name'])));
        $email = strtolower(trim($_POST['email']));

        if(!empty($name) && strlen($name)<2){
            $errors[]="Votre prénom doit faire au minimum 2 caractères";
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors[]="Votre email n'est pas correct";
        }else{
            $verifiyMail = new AuthService();
            if($verifiyMail->verifyEmail($email)){
                $errors[]= "L'email existe deja en bdd";
            }
        }
        if(strlen($_POST["pwd"]) < 8 ||
            !preg_match('/[a-z]/', $_POST["pwd"] ) ||
            !preg_match('/[A-Z]/', $_POST["pwd"]) ||
            !preg_match('/[0-9]/', $_POST["pwd"])
        ){
            $errors[]="Votre mot de passe doit faire au minimum 8 caractères avec min, maj, chiffres";
        }
        if($_POST["pwd"] != $_POST["pwdConfirm"]){
            $errors[]="Votre mot de passe de confirmation ne correspond pas";
        }  
        if(empty($errors)){
            $pwdHashed = password_hash($_POST["pwd"], PASSWORD_DEFAULT );
            $data = [
                "name"=> $name,
                "email"=> $email,
                "password"=> $pwdHashed
            ];
            $createUser = new AuthService();
            $userId = $createUser->createUser($data);
            if(!empty($userId)){
                $_SESSION['user_id'] = $userId;
                $token = hash("sha256", bin2hex(random_bytes(32)));
                $data = [
                "user_id"=> $userId,
                "token"=> $token,
            ];
                $emailService = new EmailVerificationService();
                $emailService->createUserToken($data);    
            }
            $this->renderPage("dashboard", "frontoffice");
        } else {
            $this->renderPage("signup", "frontoffice", $errors);
        }
    }else{
        echo "Tentative de XSS";
        $this->renderPage("signup", "frontoffice");
    }
    }
    public function renderSignup(): void{
        $this->renderPage("signup", "frontoffice");
    }

    public function renderLogin(): void{
         $this->renderPage("login", "frontoffice");
    }

    
}

