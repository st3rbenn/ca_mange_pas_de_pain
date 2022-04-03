<?php

namespace Class;

use Database\Database;
use PDO;

class SendInfoToBDD
{

    public function sendInscription(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){
            require 'vendor/autoload.php';

            $database = new Database();
            $db = $database->getConnection();

            $inscription = new Inscription($db);
            $stmt = $inscription->checkIfAccountExist();

            $result = '';
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $result = $mail;
            }

            if ($result === $inscription->getMail()){
                return false;
            }else $inscription->sendForm();
        }
        return true;
    }

    public function sendLogIn(): bool
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            require 'vendor/autoload.php';

            $database = new Database();
            $db = $database->getConnection();
            $checkAccount = new Inscription($db);
            $connexion = new Connexion($db);
            $stmt = $checkAccount->checkIfAccountExist();
            $eMail = html_entity_decode(htmlspecialchars($_POST['mail']));
            $mdp = html_entity_decode(htmlspecialchars($_POST['mdp']));

            $motDePasseDataBase = '';
            $eMailDataBase = '';
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $eMailDataBase = $mail;
                $motDePasseDataBase = $password;
            }

            if ($eMailDataBase == $eMail && password_verify($mdp, $motDePasseDataBase)){
                    $connexion->sendInfoForConnection();
            }else return false;
        }
        return true;
    }


}