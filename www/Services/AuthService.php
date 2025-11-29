<?php

namespace App\Service;

use App\Model\User;
use App\Service\BaseService;

class AuthService extends BaseService
{  
    
    public function createUser($data) {
        $user = new User();
        $user->setName($data['name']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);

        $sql =  'INSERT INTO "user"("name","email","password","created_at")
                    VALUES (:name,:email,:password,\''.date('Y-m-d').'\')';
        $queryPrepared = $this->pdo->prepare($sql);
        if ($queryPrepared->execute([
            "name" => $user->getName(),
            "email" => $user->getEmail(),
            "password" => $user->getPassword()
        ]) 
        ) {
            $id = $this->pdo->lastInsertId();
            echo"Utilisateur ajouté !";
            return $id;
        } 
    }


    public function getUserDataFromId($userId){
        $sql = 'SELECT email, name, is_active, id FROM "user" WHERE id=:id';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(["id" => (int)$userId]);
        $user = $queryPrepared->fetch();
        return $user;
    }

    public function updateUserEmail($email, $userId){
        $user = new User();
        $user->setEmail($email);
        $sql = 'UPDATE "user" SET email=:email WHERE id = :id';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(
        [
            "email" => $user->getEmail(),
            "id" => (int)$userId
        ]
        );
    }


    public function getAllUser(){
        $sql = 'SELECT id, name, email FROM "user"';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute();
        $allUser = $queryPrepared->fetchall();
        return $allUser;
    }

    public function updateUserName($name, $userId){
        $user = new User();
        $user->setName($name);
        $sql = 'UPDATE "user" SET name=:name WHERE id = :id';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(
        [
            "name" => $user->getName(),
            "id" => (int)$userId
        ]
        );
    }

    public function verifyEmail($email){
        $sql = 'SELECT "id" FROM "user" WHERE email=:email';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(["email"=>$email]);
        $res = $queryPrepared->fetch();
        if($res){
            return true;
        }
        return false;
    }

    
    public function getUserIdFromMail($email){
        $sql = 'SELECT "id" FROM "user" WHERE email=:email';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(["email"=>$email]);
        $res = $queryPrepared->fetch();
        if($res){
            return $res;
        }
        return false;
    }

    public function verifyPassword($email, $password){
        $sql = 'SELECT "password" FROM "user" WHERE email=:email';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(['email'=>$email]);
        $res = $queryPrepared->fetch();
        if($res){
            return password_verify($password, $res['password']);
        }
        return false;
    }

    public function getIsActiveFromId($userId){
        $sql = 'SELECT "is_active" FROM "user" WHERE id=:id';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(["id"=>(int)$userId['id']]);
        $res = $queryPrepared->fetchColumn();
        if($res){
            return $res;
        }
        return false;
    } 
    
    public function deleteUserByID($userId){
        $sql = 'DELETE FROM "user" WHERE id=:id';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute(["id"=>(int)$userId]);
    }

    public function updateUserPasswordFromId($userId, $newPassword){
        $user = new User();
        $user->setPassword($newPassword);
        $sql = 'UPDATE "user" SET password=:password WHERE id=:id';
        $queryPrepared = $this->pdo->prepare($sql);
        $queryPrepared->execute([
            "id" => (int)$userId,
            "password" => $newPassword
        ]);
    }

}