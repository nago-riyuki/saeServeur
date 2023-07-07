<?php
include_once('./functions/login-user.php');
include_once('./components/header.php');
?>

<div class="w-[100%] min-h-screen flex flex-col justify-center items-center">
    <form method="post" class="w-[70%] sm:w-[400px] rounded px-8 pt-6 pb-8">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2 " for="email">
                Adresse e-mail
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline hover:border-[#666666] transition-all transition-200 focus:border-[#666666]" id="email" name="email" type="email" placeholder="mail@provider.com">
            <?php
            if (isset($error_mail)) {
            ?>
                <p class="text-[#FF7675]"><?= $error_mail ?></p>
            <?php
            }
            ?>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2 " for="password">
                Mot de passe
            </label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline hover:border-[#666666] transition-all transition-200 focus:border-[#666666]" id="password" name="password" type="password" placeholder="password">
            <?php
            if (isset($error_password)) {
            ?>
                <p class="text-[#FF7675]"><?= $error_password ?></p>
            <?php
            }
            ?>
        </div>
        <?php
        if (isset($error_id)) {
        ?>
            <div class="text-center my-auto text-red-600 font-bold">
                <?= $error_id ?>
            </div>
        <?php
        }
        ?>
        <div class="mb-4">
            <button type="submit" name="login-form" class="w-full rounded-md p-2 bg-[#FF7675] hover:bg-[#E06867] transition-all transition-200">
                <h1 class="text-white font-semibold">Connexion</h1>
            </button>
        </div>
    </form>
    <div class="mb-4">
        <p class="text-[#FF7675] text-center">J'ai oublié mon mot de passe</p>
    </div>
    <div class="mb-4">
        <p class="text-[#FF7675] text-center">Pas encore de compte ? <a href="register.php">Inscrivez-vous !</a></p>
    </div>
</div>

<?php
include_once('./components/footer.php');
?>