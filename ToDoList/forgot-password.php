<?php

use PHPMailer\PHPMailer\PHPMailer as PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

include_once('./config/database.php');

if (isset($_POST)) {
    if (isset($_POST["forgot-form"])) {
        $valid = true;

        if (isset($_POST["forgot-email"])) {
            if (empty($_POST["forgot-email"])) {
                $valid = false;
                $error_email = "Veuillez renseignez une adresse e-mail";
            }

            if ($valid) {
                $user = $database->query("SELECT * FROM users WHERE email = ?", array($_POST["forgot-email"]));
                $user = $user->fetch();

                if (count($user) > 0) {
                    $token = bin2hex(random_bytes(16));
                    $database->insert("UPDATE users SET forgot_token = ? WHERE email = ?", array($token, $user["email"]));

                    $reset_link = "http://127.0.0.1/update-password.php?token=" . $token;

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
                    $mail->addAddress($user["email"]);
                    $mail->isHTML(true);
                    $mail->Subject = 'Récupération de mot de passe';
                    $mail->Body = "
                        <p>Bonjour " . $user["email"] . ",</p>
                        <p>Vous avez demandé la récupération de votre mot de passe. Pour réinitialiser votre mot de passe, cliquez sur le lien ci-dessous :<br>" . $reset_link .  "</p>
                        <p>Si vous n'avez pas demandé la récupération de mot de passe, ignorez simplement ce message.</p>
                        <p>Merci</p>";
                    $mail->setLanguage('fr', '/PHPMailer/language/directory/');

                    $mail->send();

                    $success_sent = "Le mot de passe a été modifié avec succès.<br><a href='login.php'>Cliquez ici pour vous connecter</a>";
                }
            } else {
                $valid = false;
                $error_email = "Veuillez renseigner une adresse e-mail";
            }
        }
    }
}

$page_title = "Mot de passe oublié";

include_once('./components/header.php');
?>

<div class="w-[100%] min-h-screen flex flex-col justify-center items-center">
    <form method="post" class="w-[70%] flex justify-center items-center flex-col sm:w-[400px] rounded px-8 pt-6 pb-8">
        <div class="mb-4 w-full">
            <label class="block text-gray-700 text-sm font-bold mb-2 " for="email">
                Adresse e-mail
            </label>
            <input name="forgot-email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline hover:border-[#666666] transition-all transition-200" id="email" type="email" placeholder="mail@provider.com">
            <?php
            if (isset($error_email)) {
            ?>
                <p class="text-[#FF7675]"><?= $error_email ?></p>
            <?php
            }
            ?>
        </div>
        <div class="mb-4 w-full">
            <button type="submit" name="forgot-form" class="w-full rounded-md p-2 bg-[#FF7675] hover:bg-[#E06867] transition-all transition-200">
                <h1 class="text-white font-semibold">Envoyer</h1>
            </button>
        </div>
        <?php
        if (isset($success_sent)) {
        ?>
            <p class="text-green-400 text-center"><?= $success_sent ?></p>
        <?php
        }
        ?>

    </form>
</div>

<?php
include_once('./components/footer.php');
?>