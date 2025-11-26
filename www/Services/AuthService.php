<?php

namespace App\Service;

use App\Core\Database;
use App\Model\User;

class AuthService
{  
    public function createUser($data) {
        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);

        $pdo = Database::getInstance()->getConnection();
        $sql =  'INSERT INTO "user"("name","email","password","created_at")
                    VALUES (:name,:email,:password,\''.date('Y-m-d').'\')';
        $queryPrepared = $pdo->prepare($sql);
        if ($queryPrepared->execute([

            "name" => $user->getName(),
            "email" => $user->getEmail(),
            "password" => $user->getPassword()
        ]) 
        ) {
            $id = $pdo->lastInsertId();
            echo"Utilisateur ajouté !";
            return $id;
        } 
    }

    public function verifyEmail($email){
        $pdo = Database::getInstance()->getConnection();
        $sql = 'SELECT "id" FROM "user" WHERE email=:email';
        $queryPrepared = $pdo->prepare($sql);
        $queryPrepared->execute(["email"=>$email]);
        $res = $queryPrepared->fetch();
        if($res){
            return true;
        }
        return false;
    }

    
    public function getUserIdFromMail($email){
        $pdo = Database::getInstance()->getConnection();
        $sql = 'SELECT "id" FROM "user" WHERE email=:email';
        $queryPrepared = $pdo->prepare($sql);
        $queryPrepared->execute(["email"=>$email]);
        $res = $queryPrepared->fetch();
        if($res){
            return $res;
        }
        return false;
    }

    public function verifyPassword($email, $password){
        $pdo = Database::getInstance()->getConnection();
        $sql = 'SELECT "password" FROM "user" WHERE email=:email';
        $queryPrepared = $pdo->prepare($sql);
        $queryPrepared->execute(['email'=>$email]);
        $res = $queryPrepared->fetch();
        if($res){
            return password_verify($password, $res['password']);
        }
        return false;
    }
    public function signin($data){

    }
    public function updateUser($data){
    }
}