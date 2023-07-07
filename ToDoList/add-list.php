<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$page_title = "Créer une liste";

include_once('./functions/add-list.php');
include_once('./components/header.php');
include_once('./components/navbar.php');
?>

<div class="w-full flex justify-center items-center flex-col h-screen">
    <h1 class="text-4xl font-bold mb-5">Créer une liste</h1>
    <form method="post" class="w-[70%] sm:w-[400px] rounded pt-6 pb-8">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="list-name">
                Nom de la liste
            </label>
            <input required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline hover:border-[#666666] transition-all transition-200 focus:border-[#666666]" id="list-name" name="list-name" type="text" placeholder="Liste de courses">
            <?php
            if (isset($error_mail)) {
            ?>
                <p class="text-[#FF7675]"><?= $error_mail ?></p>
            <?php
            }
            ?>
        </div>
        <div class="mb-4">
            <button type="submit" name="add-list-form" class="w-full rounded-md p-2 bg-[#FF7675] hover:bg-[#E06867] transition-all transition-200">
                <h1 class="text-white font-semibold">Créer une liste</h1>
            </button>
        </div>
    </form>
</div>

<?php
include_once('./components/footer.php');
?>