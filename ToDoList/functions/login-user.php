<?php
session_start();
include('./config/database.php');

if (isset($_SESSION['id'])) {
    header('Location: home.php');
    exit;
}

if (isset($_GET["confirm_token"])) {
    $confirm_token = $_GET["confirm_token"];

    $user = $database->query("SELECT * FROM users WHERE confirm_token = ?", array($confirm_token));
    $user = $user->fetch();

    if ($user) {
        $database->insert("UPDATE users SET status = ? WHERE confirm_token = ?", array(1, $confirm_token));
        header("Location: login.php");
    } else {
        header("Location: login.php");
        exit;
    }
}

if (!empty($_POST)) {
    extract($_POST);
    $valid = true;

    if (isset($_POST['login-form'])) {
        $email = htmlentities(strtolower(trim($email)));
        $password = trim($password);

        if (empty($email)) {
            $valid = false;
            $error_mail = "Veuillez renseigner une adresse e-mail";
        }

        if (empty($password)) {
            $valid = false;
            $error_password = "Veuillez renseigner un mot de passe";
        }

        if ($valid) {
            $req = $database->query(
                "SELECT * FROM users WHERE email = ?",
                array($email)
            );

            $user = $req->fetch();



            if ($user && password_verify($password, $user['password'])) {
                if ($user["status"] == null) {
                    $error_id = "Vous devez vérifier votre adresse e-mail";
                } else {
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['email'] = $user['email'];

                    header('Location: home.php');
                    exit;
                }
            } else {
                $valid = false;
                $error_id = "Identifiants incorrects";
            }
        }
    }
}
