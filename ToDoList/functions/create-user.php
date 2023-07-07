<?php

use PHPMailer\PHPMailer\PHPMailer as PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

session_start();
include('./config/database.php');

if (isset($_SESSION['id'])) {
    header('Location: index.php');
    exit;
}


if (!empty($_POST)) {
    extract($_POST);
    $valid = true;


    if (isset($_POST['register-form'])) {
        $email = htmlentities(strtolower(trim($email)));
        $password = trim($password);
        $password_confirmation = trim($password_confirmation);

        if (empty($email)) {
            $valid = false;
            $error_email = "Veuillez renseigner une adresse e-mail";
        } elseif (!preg_match("/^[a-z0-9\-_.]+@[a-z]+\.[a-z]{2,3}$/i", $email)) {
            $valid = false;
            $error_email = "Le mail n'est pas valide.";
        } else {
            $req_mail = $database->query(
                "SELECT email FROM users WHERE email = ?",
                array($email)
            );

            $req_mail = $req_mail->fetch();

            if (isset($req_mail) && !empty($req_mail) && count($req_mail) > 0) {
                $valid = false;
                $error_email = "Cette adresse e-mail est déjà utilisée";
            }
        }

        if (empty($password) || empty($password_confirmation)) {
            $valid = false;
            $error_passwords = "Veuillez renseigner un mot de passe";
        } elseif ($password != $password_confirmation) {
            $valid = false;
            $error_passwords = "Les mots de passes ne correspondent pas";
        }

        if ($valid) {
            $options = [
                'cost' => 12,
            ];

            $password_hash = password_hash($password, PASSWORD_BCRYPT, $options);

            $database->insert(
                "INSERT INTO users (email, password) VALUES
              (?, ?)",
                array($email, $password_hash)
            );

            $token = bin2hex(random_bytes(16));
            $database->insert("UPDATE users SET confirm_token = ? WHERE email = ?", array($token, $email));

            $confirm_link = "http://127.0.0.1/login.php?confirm_token=" . $token;

            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->SMTPDebug = false;
            $mail->SMTPSecure = 'ssl';
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'azlaksv3@gmail.com';
            $mail->Password = 'ydmfrrjarxdovemi';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom("Azlaks Skalza", 'azlaksv3@gmail.com');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Confirmation du compte';
            $mail->Body = "
                        <p>Bonjour " . $email . ",</p>
                        <p>Vous avez créé votre compte. Pour accéder à la plateforme, cliquez sur le lien ci-dessous :<br>" . $confirm_link .  "</p>
                        <p>Si vous n'avez pas demandé la création de votre compte, ignorez simplement ce message.</p>
                        <p>Merci</p>";
            $mail->setLanguage('fr', '/PHPMailer/language/directory/');

            $mail->send();

            header('Location: login.php');
            exit;
        }
    }
}
