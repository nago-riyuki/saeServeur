<?php
session_start();
include('./config/database.php');

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

if (!empty($_POST)) {
    extract($_POST);
    $valid = true;

    if (isset($_POST["email-form"])) {
        if (empty($_POST["new_email"])) {
            $valid = false;
            $error_new_email = "Adresse e-mail requise.";
        } else if (empty($_POST["new_email_confirmation"])) {
            $valid = false;
            $error_new_email_confirmation = "Nouvelle adresse e-mail requise.";
        } else if ($_POST["new_email"] != $_POST["new_email_confirmation"]) {
            $valid = false;
            $autofill_email = $_POST["new_email"];
            $error_new_email = "Adresse mail similaire requis.";
            $error_new_email_confirmation = "Adresse mail similaire requis.";
        }

        if ($valid) {
            $req = $database->query(
                "SELECT * FROM users WHERE email = ? AND id <> ?",
                array($_POST["new_email"], $_SESSION["id"])
            );

            $user = $req->fetch();

            if ($user) {
                $error_new_email = "Adresse mail déjà utilisée.";
                $error_new_email_confirmation = "Adresse mail déjà utilisée.";
            } else {
                $database->insert("UPDATE users SET email = ? WHERE email = ?", array($_POST["new_email"], $_SESSION["email"]));
                $_SESSION["email"] = $_POST["new_email"];
                $success_email = "Adresse e-mail changée.";
            }
        } else {
        }
    } else if (isset($_POST["password-form"])) {
        if (empty($_POST["current_password"])) {
            $valid = false;
            $error_current_password = "Mot de passe requis.";
        } else if (empty($_POST["new_password"])) {
            $valid = false;
            $error_new_password = "Nouveau mot de passe requis.";
        } else if (empty($_POST["new_password_confirmation"])) {
            $valid = false;
            $error_new_password_confirmation = "Confirmation requise.";
        } else if ($_POST["new_password_confirmation"] != $_POST["new_password"]) {
            $valid = false;
            $error_current_password = "Mot de passe similaire requis.";
            $error_new_password_confirmation = "Mot de passe similaire requis.";
        }

        $req = $database->query(
            "SELECT * FROM users WHERE id = ? AND email = ?",
            array($_SESSION["id"], $_SESSION["email"])
        );
        
        $user = $req->fetch();
       
        $options = [
            'cost' => 12,
        ];

        $password = password_hash($_POST['new_password'], PASSWORD_BCRYPT, $options);

        if ($user && password_verify($_POST["current_password"], $user['password'])) {
            $database->insert("UPDATE users SET password = ? WHERE email = ? AND id = ?", array($password, $user["email"], $_SESSION["id"]));
            $success_password = "Mot de passe changé.";
        } else {
            $valid = false;
            $error_current_password = "Mot de passe actuel incorrect.";
        }
    }
}
