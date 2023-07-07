<?php
$page_title = "Paramètres";

include_once('./functions/settings.php');
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset='UTF-8' />
    <meta name='viewport' content='width=device-width, initial-scale=1.0' />
    <title>TodoList</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/dist/css/app.css">
    <script src='https://cdn.tailwindcss.com'></script>
</head>

<body class="bg-[#FDFDFD] flex flex-col lg:flex-row">

    <?php
    include_once('./components/navbar.php');
    ?>

    <div class="p-6">
        <h1 class="text-4xl font-bold mb-16">Paramètres</h1>

        <section class="e-mail">
            <h1 class="text-2xl font-bold mb-5">Adresse e-mail</h1>
            <p class="font-bold mb-8">Adresse e-mail actuelle: <?= $_SESSION["email"] ?>
                <?php if (isset($success_email)) { ?>
            <p class="text-green-400"><?= $success_email ?></p>
        <?php } ?>
        </p>


        <form method="post" action="" class="w-[70%] sm:w-[400px] rounded pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2 " for="new_email">
                    Nouvelle adresse e-mail
                </label>
                <input required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline hover:border-[#666666] transition-all transition-200 focus:border-[#666666]" name="new_email" id="new_email" type="email" placeholder="mail@provider.com" <?php if (isset($autofill_email)) { ?> value="<?= $autofill_email ?>" <?php } ?>>
                <?php
                if (isset($error_new_email)) {
                ?>
                    <p class="text-[#FF7675]"><?= $error_new_email ?></p>
                <?php
                }
                ?>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2 " for="new_email_confirmation">
                    Confirmer l'adresse e-mail
                </label>
                <input required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline hover:border-[#666666] transition-all transition-200 focus:border-[#666666]" name="new_email_confirmation" id="new_email_confirmation" type="email" placeholder="mail@provider.com" <?php if (isset($autofill_email)) { ?> value="<?= $autofill_email ?>" <?php } ?>>
                <?php
                if (isset($error_new_email_confirmation)) {
                ?>
                    <p class="text-[#FF7675]"><?= $error_new_email_confirmation ?></p>
                <?php
                }
                ?>
            </div>
            <button name="email-form" type="submit" class="w-full rounded-md p-2 bg-[#FF7675] hover:bg-[#E06867] transition-all transition-200">
                <h1 class="text-white font-semibold">Modifier l'adresse e-mail</h1>
            </button>
        </form>
        </section>

        <section class="password ">
            <h1 class="text-2xl font-bold mb-5">Mot de passe</h1>
            <p class="font-bold mb-8"> <?php if (isset($success_password)) { ?>
            <p class="text-green-400"><?= $success_password ?></p>
        <?php } ?></p>
        <form method="post" class="w-[70%] sm:w-[400px] rounded pt-6 pb-8 mb-4 ">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2 " for="current_password">
                    Mot de passe actuel
                </label>
                <input required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline hover:border-[#666666] transition-all transition-200 focus:border-[#666666]" name="current_password" id="current_password" type="password" placeholder="password">
                <?php
                if (isset($error_current_password)) {
                ?>
                    <p class="text-[#FF7675]"><?= $error_current_password ?></p>
                <?php
                }
                ?>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2 " for="new_password">
                    Nouveau mot de passe
                </label>
                <input required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline hover:border-[#666666] transition-all transition-200 focus:border-[#666666]" name="new_password" id="new_password" type="password" placeholder="password">
                <?php
                if (isset($error_new_password)) {
                ?>
                    <p class="text-[#FF7675]"><?= $error_new_password ?></p>
                <?php
                }
                ?>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-semibold mb-2 " for="new_password_confirmation">
                    Confirmer mot de passe
                </label>
                <input required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline hover:border-[#666666] transition-all transition-200 focus:border-[#666666]" name="new_password_confirmation" id="new_password_confirmation" type="password" placeholder="password">
                <?php
                if (isset($error_new_password_confirmation)) {
                ?>
                    <p class="text-[#FF7675]"><?= $error_new_password_confirmation ?></p>
                <?php
                }
                ?>
            </div>
            <button name="password-form" type="submit" class="w-full rounded-md p-2 bg-[#FF7675] hover:bg-[#E06867] transition-all transition-200">
                <h1 class="text-white font-semibold">Modifier mot de passe</h1>
            </button>
        </form>
        </section>
    </div>

    <?php
    include_once('./components/footer.php');
    ?>