<?php
session_start();
include_once('./config/database.php');

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$page_title = "Accueil";

$tasks = $database->query('SELECT * FROM tasks WHERE author_id = ? ORDER BY echeance DESC', array($_SESSION["id"]));
$tasks = $tasks->fetchAll();

foreach ($tasks as $task) {
    if (isset($_POST["check-form-" . $task["id"]])) {
        $checkedValue = $_POST["check-form-" . $task["id"]];
        $database->insert("UPDATE tasks SET status = ? WHERE id = ?", array($checkedValue, $task["id"]));

        $token = htmlspecialchars($_GET["token"], ENT_QUOTES, "UTF-8");

        header("Location: ./home.php");
    }
}

include_once('./components/header.php');
include_once('./components/navbar.php');
?>
<div class="w-full p-6">
    <div class="w-[100%] flex flex-col">
        <h1 class="text-4xl font-bold mb-8">Prochaines tâches</h1>
        <section class="tasks-section">
            <ul class="flex flex-col">
                <div class="w-full flex-col">
                    <?php
                    foreach ($tasks as $task) : ?>
                        <li class="task border-t-2 py-2 cursor-pointer" id="task-<?= $task["name"] ?>">
                            <a href="./list.php?token=<?= $task["list_id"] ?>">

                                <form method="post" class="flex" id="form-<?= $task['id'] ?>">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="check-form-<?= $task['id'] ?>" <?php if ($task['status'] == 1) {
                                                                                                        echo 'checked';
                                                                                                    } ?> class="relative transition-all transition-200 ml-auto pl-3 pr-4 py-3 flex justify-center items-center checked:bg-[#FF7675] float-left -ml-[1.5rem] mr-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-full border-[0.125rem] border-solid border-neutral-300 outline-none before:pointer-events-none before:absolute before:h-[0.875rem] before:w-[0.875rem] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:shadow-[0px_0px_0px_13px_transparent] before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:-mt-px checked:after:ml-[0.25rem] checked:after:block checked:after:h-[0.8125rem] checked:after:w-[0.375rem] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] hover:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:shadow-none focus:transition-[border-color_0.2s] focus:before:scale-100 focus:before:opacity-[0.12] focus:before:shadow-[0px_0px_0px_13px_rgba(0,0,0,0.6)] focus:before:transition-[box-shadow_0.2s,transform_0.2s] focus:after:absolute focus:after:z-[1] focus:after:block focus:after:h-[0.875rem] focus:after:w-[0.875rem] focus:after:rounded-[0.125rem] focus:after:content-[''] checked:focus:before:scale-100 checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] checked:focus:before:transition-[box-shadow_0.2s,transform_0.2s] checked:focus:after:-mt-px checked:focus:after:ml-[0.25rem] checked:focus:after:h-[0.8125rem] checked:focus:after:w-[0.375rem] checked:focus:after:rotate-45 checked:focus:after:rounded-none checked:focus:after:border-[0.125rem] checked:focus:after:border-l-0 checked:focus:after:border-t-0 checked:focus:after:border-solid checked:focus:after:border-white checked:focus:after:bg-transparent dark:border-neutral-600 dark:checked:border-primary dark:checked:bg-primary dark:focus:before:shadow-[0px_0px_0px_13px_rgba(255,255,255,0.4)] dark:checked:focus:before:shadow-[0px_0px_0px_13px_#3b71ca] <?php if ($task['status'] == 1) : ?>line-trought<?php endif ?>">
                                    </div>
                                    <div class="informations ml-[1%] w-full flex flex-col">
                                        <h1 class="font-semibold <?php if ($task["status"] == 1) : ?> line-through <?php endif ?>"><?= $task["name"] ?></h1>
                                        <div class="bottom-informations">
                                            <p class="italic">
                                                <?php
                                                $subtasks = $database->query("SELECT * FROM subtasks WHERE task_id = ?", array($task["id"]));
                                                $subtasks = $subtasks->fetchAll();

                                                $ended_subtasks = $database->query("SELECT * FROM subtasks WHERE task_id = ? AND status = 1", array($task["id"]));
                                                $ended_subtasks = $ended_subtasks->fetchAll();
                                                ?>
                                            <p class="task">
                                                <span class="task-status">
                                                    <?= count($ended_subtasks) ?> / <?= count($subtasks) ?> sous-tâches terminées
                                                </span>
                                                <span class="task-due-date">
                                                    <?= ($task["echeance"] != null) ? '• Echéance : ' . date('d/m/Y', strtotime($task["echeance"])) : '• Echéance : non définie' ?>
                                                </span>
                                                <span class="task-note">
                                                    <?= ($task["note"] != null) ? '• Note : ' . $task["note"] : '' ?>
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="ml-auto mr-[1%] flex justify-center items-center">
                                        <i class="fa-solid fa-trash text-xl" id="delete-<?= $task["name"] ?>"></i>
                                    </div>
                                </form>
                            </a>
                        </li>
                    <?php endforeach ?>
                </div>
            </ul>
        </section>
    </div>
</div>

<script>
    const checkboxes = document.querySelectorAll("[name^='check-form-']");

    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener("change", function() {
            const form = document.createElement('form');
            form.method = 'post';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = checkbox.name;
            input.value = checkbox.checked ? "1" : "0";

            form.appendChild(input);
            document.body.appendChild(form);

            form.submit()
        });
    });
</script>
<?php
include_once('./components/footer.php');
?>