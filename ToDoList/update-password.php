<?php
session_start();
include_once('./config/database.php');

if (isset($_SESSION['id'])) {
    header('Location: home.php');
    exit;
}

if (!isset($_GET['token'])) {
    header('Location: login.php');
    exit;
}

$user_token = $_GET['token'];
$user = $database->query("SELECT * FROM users WHERE forgot_token = ?", array($user_token));
$user = $user->fetch();

if (!$user) {
    header('Location: login.php');
    exit;
}

if (isset($_POST)) {
    if (isset($_POST["password"]) and isset($_POST["password_confirmation"])) {
        if (empty($_POST["password"])) {
            $valid = false;
            $error_password = "Veuillez renseigner le mot de passe";
        }
        if (empty($_POST["password_confirmation"])) {
            $valid = false;
            $error_password_confirmation = "Veuillez renseigner le mot de passe à nouveau";
        }

        try {
            $options = [
                'cost' => 12,
            ];
            $password_hash = password_hash($_POST["password"], PASSWORD_BCRYPT, $options);
            $database->insert("UPDATE users SET password = ? WHERE forgot_token = ?", array($password_hash, $user_token));
            
            header("Location: login.php");
            exit();
        } catch (PDOException $e) {
            echo $e;
        }
    }
}


include_once('./components/header.php');
?>

<div class="w-[100%] min-h-screen flex flex-col justify-center items-center">
    <form action="" method="post" class="w-[70%] sm:w-[400px] rounded px-8 pt-6 pb-8">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2 " for="password">
                Nouveau mot de passe
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline hover:border-[#666666] transition-all transition-200" id="password" name="password" type="password" placeholder="password">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2 " for="password_confirmation">
                Confirmer le nouveau mot de passe
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline hover:border-[#666666] transition-all transition-200" id="password_confirmation" name="password_confirmation" type="password" placeholder="password">
        </div>
        <div class="mb-4">
            <button type="submit" class="w-full rounded-md p-2 bg-[#FF7675] hover:bg-[#E06867] transition-all transition-200">
                <h1 class="text-white font-semibold">Envoyer</h1>
            </button>
        </div>
    </form>
</div>

<?php
include_once('./components/footer.php');
?>