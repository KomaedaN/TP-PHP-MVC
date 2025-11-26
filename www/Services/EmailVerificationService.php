<?php

namespace App\Service;
use App\Core\Database;
use App\Model\EmailVerification;

class EmailVerificationService
{

    public function createUserToken($data) {
        $emailVerification = new EmailVerification();
        $emailVerification->setUserID($data["user_id"]);
        $emailVerification->setToken($data["token"]);
        $pdo = Database::getInstance()->getConnection();
        $sql =  'INSERT INTO email_verification("user_id","token", "created_at")
                    VALUES (:user_id,:token,\''.date('Y-m-d').'\')';
        $queryPrepared = $pdo->prepare($sql);
        if ($queryPrepared->execute([
            "user_id"=> $emailVerification->getUserID(),
            "token"=> $emailVerification->getToken(),
        ]) 
        ) {
            echo"token ajouté";
        } 
    }
}