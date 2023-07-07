<header class="hidden lg:flex min-w-[25%] min-h-screen bg-[#F5F5F5] flex-col p-5 border-r shadow-md">
    <section class="header flex flex-start justify-center items-center w-full">
        <div class="flex items-center">
            <a href="./home.php"><i class="fa-solid fa-house text-[#FF7675]"></i></a>
            <p class=" ml-[5%] not-italic font-bold text-base leading-5"><?= $_SESSION["email"] ?></p>
        </div>
    </section>
    <hr class="my-3">
    <section class="lists">
        <h1 class="font-bold mb-3">Mes listes</h1>
        <ul class="lists mb-3">
            <?php

            $req = $database->query(
                "SELECT * FROM lists WHERE author_id = ?",
                array($_SESSION["id"])
            );

            $lists = $req->fetchAll();


            foreach ($lists as $list) {
                $tasks = $database->query(
                    "SELECT * FROM tasks WHERE list_id = ? AND status = 0",
                    array($list["id"])
                );

                $tasks = $tasks->fetchAll();
            ?>
                <li class="list flex items-center justify-between mb-3">
                    <a class="list-title" href="/list.php?token=<?= $list['id'] ?>&open-edit=nothing"><?= $list["name"] ?></a>
                    <h3 class="list-item-count bg-[#FF7675] rounded-md px-3 py-1">
                        <?= count($tasks) ?>
                    </h3>
                </li>
            <?php } ?>
        </ul>
        <a class="flex items-center" href="/add-list.php">
            <i class="fa-solid fa-plus text-[#FF7675]"></i>
            <p class=" ml-[5%] not-italic text-base leading-5">Nouvelle liste</p>
        </a>
    </section>

    <section class="mt-auto">
        <div class="flex mb-3 items-center">
            <i class="fa-solid fa-gear text-[#FF7675]"></i>
            <a class=" ml-[5%] not-italic font-bold text-base leading-5" href="/settings.php">Paramètres</a>
        </div>
        <div class="flex mb-3 items-center">
            <i class="fa-solid fa-power-off text-[#FF7675]"></i>
            <a class=" ml-[5%] not-italic font-bold text-base leading-5" href="/functions/disconnect-user.php">Déconnexion</a>
        </div>
    </section>
</header>

<header id="header" class="flex lg:hidden w-screen min-h-[15%] bg-[#FF7675] flex-cpm p-4 border-r shadow-md">
    <div id="openMenu" class="w-full flex flex-row justify-between items-center">
        <button id="openMenuButton">
            <i class="fa-solid fa-bars text-white text-2xl"></i>
        </button>
        <h1 class="text-2xl font-bold text-white"><?= $page_title ?></h1>
    </div>
    <div id="menu" class="w-screen h-[100%] absolute z-50 hidden bg-white p-4">
        <div class="flex flex-col h-full">
            <section class="header flex flex-start justify-between items-center w-full">
                <div class="flex items-center">
                    <a href="./home.php"><i class="fa-solid fa-house text-[#FF7675]"></i></a>
                    <p class=" ml-[5%] not-italic font-bold text-base leading-5"><?= $_SESSION["email"] ?></p>
                </div>
                <button id="closeMenuButton">
                    <i class="fa-solid fa-xmark text-black text-3xl"></i>
                </button>
            </section>
            <hr class="my-3">
            <section class="lists">
                <h1 class="font-bold mb-3">Mes listes</h1>
                <ul class="lists mb-3">
                    <?php
                    $req = $database->query(
                        "SELECT * FROM lists WHERE author_id = ?",
                        array($_SESSION["id"])
                    );

                    $lists = $req->fetchAll();


                    foreach ($lists as $list) {
                        $tasks = $database->query(
                            "SELECT * FROM tasks WHERE list_id = ?",
                            array($list["id"])
                        );

                        $tasks = $tasks->fetchAll();
                    ?>
                        <li class="list flex items-center justify-between mb-3">
                            <a class="list-title" href="list.php?token=<?= $list["id"] ?>"><?= $list["name"] ?></a>
                            <h3 class="list-item-count bg-[#FF7675] rounded-md px-3 py-1">
                                <?= count($tasks) ?>
                            </h3>
                        </li>
                    <?php } ?>
                </ul>
                <a class="flex items-center" href="/add-list.php">
                    <i class="fa-solid fa-plus text-[#FF7675]"></i>
                    <p class=" ml-[5%] not-italic text-base leading-5">Nouvelle liste</p>
                </a>
            </section>
            <section class="absolute bottom-0 w-full mb-5">
                <div class="flex mb-3">
                    <a class=" ml-[5%] not-italic font-bold text-base leading-5" href="/settings.php"><i class="fa-solid fa-gear text-[#FF7675]"></i> Paramètres</a>
                </div>
                <div class="flex mb-3 items-start">
                    <a class=" ml-[5%] not-italic font-bold text-base leading-5" href="/functions/disconnect-user.php"><i class="fa-solid fa-power-off text-[#FF7675]"></i> Déconnexion</a>
                </div>
            </section>
        </div>
    </div>
</header>

<script>
    const openMenuButton = document.getElementById('openMenuButton');

    openMenuButton.addEventListener("click", (e) => {
        const menu = document.getElementById('menu');
        menu.classList.toggle("hidden");

        const openMenu = document.getElementById("openMenu")
        const header = document.getElementById("header")

        openMenu.classList.toggle("hidden");
        header.classList.toggle("p-4");
    })

    const closeMenuButton = document.getElementById('closeMenuButton');

    closeMenuButton.addEventListener("click", (e) => {
        const menu = document.getElementById('menu');
        menu.classList.toggle("hidden");

        const openMenu = document.getElementById("openMenu")
        const header = document.getElementById("header")

        openMenu.classList.toggle("hidden");
        header.classList.toggle("p-4");
    })
</script>