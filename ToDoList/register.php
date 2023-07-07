<?php
include_once('./functions/create-user.php');
include_once('./components/header.php');
?>

<div class="w-[100%] min-h-screen flex justify-center items-center">
    <form method="post" class="w-[70%] sm:w-[400px] rounded px-8 pt-6 pb-8 mb-4">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2 " for="email">
                Adresse e-mail
            </label>
            <input required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline hover:border-[#666666] transition-all transition-200 focus:border-[#666666]" name="email" id="email" type="email" placeholder="mail@provider.com">
            <?php
            if (isset($error_email)) {
            ?>
                <p class="text-[#FF7675]"><?= $error_email ?></p>
            <?php
            }
            ?>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2 " for="password">
                Mot de passe
            </label>
            <input required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline hover:border-[#666666] transition-all transition-200 focus:border-[#666666]" name="password" id="password" type="password" placeholder="password">
            <?php
            if (isset($error_passwords)) {
            ?>
                <p class="text-[#FF7675]"><?= $error_passwords ?></p>
            <?php
            }
            ?>
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2 " for="password_confirmation">
                Répéter mot de passe
            </label>
            <input required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline hover:border-[#666666] transition-all transition-200 focus:border-[#666666]" name="password_confirmation" id="password_confirmation" type="password" placeholder="password">
            <?php
            if (isset($error_passwords)) {
            ?>
                <p class="text-[#FF7675]"><?= $error_passwords ?></p>
            <?php
            }
            ?>
        </div>
        <button name="register-form" type="submit" class="mb-3 w-full rounded-md p-2 bg-[#FF7675] hover:bg-[#E06867] transition-all transition-200">
            <h1 class="text-white font-semibold">Inscription</h1>
        </button>
        <div class="mb-4">
            <p class="text-[#FF7675] text-center">Déjà un compte ? <a href="login.php">Connectez-vous !</a></p>
        </div>
    </form>
</div>

<?php
include_once('./components/footer.php');
?>