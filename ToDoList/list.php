<?php
session_start();
include_once('./config/database.php');

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['token'])) {
    header("Location: home.php");
} else if (empty($_GET['token'])) {
    header("Location: home.php");
}

if (isset($_POST["add-item"])) {
    $valid = true;

    if (empty($_POST["task-name"])) {
        $valid = false;
        $error_task_name = "Nom de tâche requis.";
    }

    $isListItem = $database->query('SELECT * FROM tasks WHERE name = ? AND list_id = ?', array($_POST["task-name"], $_GET["token"]));
    $isListItem = $isListItem->fetch();

    if ($isListItem) {
        $valid = false;
        $error_task_name = "Cette liste contient déjà une tâche avec ce nom.";
    }

    if ($valid) {
        $database->insert(
            "INSERT INTO tasks (name, list_id, author_id) VALUES
          (?, ?, ?)",
            array($_POST["task-name"], $_GET["token"], $_SESSION["id"])
        );

        $success_add_task = "Tâche bien ajoutée.";
    }
}

if (isset($_GET["delete-list"])) {
    $valid = true;

    $list = $database->query('SELECT * FROM lists WHERE id = ? AND author_id = ?', array($_GET["token"], $_SESSION["id"]));
    $list = $list->fetch();

    if (empty($_POST["delete-list"])) {
        $valid = false;
    } else if ($_POST["delete-list"] != $list["name"]) {
        $valid = false;
    }

    $isListExist = $database->query('SELECT * FROM lists WHERE name = ? AND author_id = ?', array($_POST["delete-list"], $_SESSION["id"]));
    $isListExist = $isListExist->fetch();

    if (!$isListExist) {
        $valid = false;
    }

    if ($valid) {
        $database->insert("DELETE FROM lists WHERE name = ? AND author_id = ?", array($_POST["delete-list"], $_SESSION["id"]));
        $database->insert("DELETE FROM tasks WHERE list_id = ?", array($_GET["token"]));
        header("Location: home.php");
    }
} else if(isset($_GET["delete"])) {
    $database->insert("DELETE FROM tasks WHERE id = ?", array($_GET["delete"]));
    header("Location: home.php");
}

$page_title = "Liste";

$lists = $database->query("SELECT * FROM lists WHERE id = ?", array($_GET["token"]));
$lists = $lists->fetchAll();

$tasks = $database->query('SELECT * FROM tasks WHERE list_id = ? AND author_id = ? ORDER BY echeance DESC', array($_GET["token"], $_SESSION["id"]));
$tasks = $tasks->fetchAll();

foreach ($tasks as $task) {
    if (isset($_POST["check-form-" . $task["id"]])) {
        $checkedValue = $_POST["check-form-" . $task["id"]];
        $database->insert("UPDATE tasks SET status = ? WHERE id = ?", array($checkedValue, $task["id"]));

        $token = htmlspecialchars($_GET["token"], ENT_QUOTES, "UTF-8");

        header("Location: ./list.php?token=" . $token);
    }
}

if (!$lists) {
    header("Location: home.php");
}

include_once('./components/header.php');
include_once('./components/navbar.php');
?>

<div class="w-full p-6">
    <div class="w-[100%] flex flex-col">
        <section class="mb-8 header-section w-full flex justify-between items-center">
            <h1 class="text-4xl font-bold"><?= $lists[0]["name"] ?></h1>
            <button id="delete-list-<?= $list["name"] ?>" class="rounded-md w-[200px] h-[50px] bg-[#D63031] text-white flex justify-evenly items-center">
                <i class="fa-solid fa-trash"></i>
                <p>Supprimer la liste</p>
            </button>
        </section>
        <?php
        if (isset($success_add_task)) {
        ?>
            <div class="text-green-400 mb-3"><?= $success_add_task ?></div>
        <?php
        }
        ?>
        <section class="tasks-section">
            <ul class="flex flex-col overflow-y-scroll h-[40%]">
                <?php
                $tasks = $database->query('SELECT * FROM tasks WHERE list_id = ?', array($_GET["token"]));
                $tasks = $tasks->fetchAll();

                foreach ($tasks as $task) {
                    if (isset($_POST["check-form-" . $task["id"]])) {
                        $checkedValue = $_POST["check-form-" . $task["id"]];
                        $database->insert("UPDATE tasks SET status = ? WHERE id = ?", array($checkedValue, $task["id"]));

                        header("Location: " . $_SERVER["REQUEST_URI"]);
                        exit();
                    }
                ?>
                    <li class="task border-t-2 py-2 cursor-pointer" id="task-<?= $task["name"] ?>">
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
                                            <?= ($task["note"] != null) ? '• Note: ' . $task["note"] : '• Note: non définie' ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="ml-auto mr-[1%] flex justify-center items-center z-10"  id="delete-<?= $task["id"] ?>">
                                <i class="fa-solid fa-trash text-xl"></i>
                            </div>
                        </form>
                    </li>
                <?php } ?>
            </ul>
            <div class="border-y-2 py-2">
                <form method="post" class="flex items-center">
                    <button name="add-item" type="submit" class="ml-[1%]"><i class="fa-solid fa-plus font-bold text-xl"></i></button>
                    <div class="relative z-0 w-full">
                        <input type="text" name="task-name" placeholder="Ajouter une tâche..." class="ml-[1%] block py-2.5 px-0 w-full text-sm bg-transparent border-0  appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " />
                    </div>
                </form>
            </div>
            <?php
            if (isset($error_task_name)) {
            ?>
                <p class="text-[#FF7675]"><?= $error_task_name ?></p>
            <?php
            }
            ?>
        </section>

        <div id="modal" class="fixed z-50 top-0 bottom-0 left-0 right-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
            <div class="bg-white p-8 rounded shadow-lg max-w-[380px]">
                <h1 class="text-4xl font-bold">Supprimer la liste ?</h1>
                <p class="mb-4">Après avoir été supprimée, une liste ne peut pas être récupérée. Êtes-vous certain(e) de vouloir supprimer la liste “<?= $list["name"] ?>” ?</p>
                <div class="grid grid-cols-2 gap-8">
                    <button class="rounded-md w-full h-[50px] bg-[#D63031] text-white flex justify-center items-center">
                        <i class="ml-auto fa-solid fa-trash"></i>
                        <p class="ml-[5px] mr-auto text-sm">Supprimer la liste</p>
                    </button>
                    <button class="rounded-md w-full h-[50px] bg-[#C4C4C4] text-white flex justify-center items-center">
                        <i class="ml-auto fa-solid fa-xmark"></i>
                        <p class="ml-[5px] mr-auto">Annuler</p>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
<?php
foreach ($tasks as $task) {
?>
    <div class="overflow-y-scroll bg-[#F5F5F5] p-4 absolute w-screen md:w-[35%] lg:w-[20%] right-0 h-screen lg:min-w-[25%] hidden z-50" id="edit-task-form-<?= $task["name"] ?>">
        <div class="w-full flex">
            <button class="ml-auto text-2xl" id="close-edit-task-<?= $task["name"] ?>">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="/functions/edit-task.php?id=<?= $task["id"] ?>&list_id=<?= $list["id"] ?>" class="p-4" method="post">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="edit-task-name">
                    Titre
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline hover:border-[#666666] transition-all transition-200 focus:border-[#666666]" type="text" id="edit-task-name" name="edit-task-name" placeholder="<?= $task["name"] ?>">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="edit-task-subtask">
                    Etapes
                </label>
                <ul id="edit-task-subtasks">
                    <?php
                    $subtasks = $database->query("SELECT * FROM subtasks WHERE task_id = ?", array($task["id"]));
                    $subtasks = $subtasks->fetchAll();

                    if (count($subtasks) > 0) :
                    ?>
                        <li class="mb-3 flex justify-between">
                            <h1><i class="fa-solid fa-check text-green-600"></i></h1>
                            <h1 class="ml-[5%] mr-auto">Nom</h1>
                            <h1><i class="fa-solid fa-trash text-red-600"></i></h1>
                        </li>
                    <?php
                    endif;
                    foreach ($subtasks as $subtask) :
                    ?>
                        <li class="mb-3 flex">
                            <input type="checkbox" <?php if ($subtask["status"] === 1) { ?> checked <?php } ?> name="edit-status-subtask-<?= $subtask["id"] ?>" id="">
                            <input class="w-[80%] mx-auto px-3 py-2 border rounded" type="text" name="edit-task-subtask-<?= $subtask["id"] ?>" value="<?= $subtask["name"] ?>">
                            <div class="flex flex-col items-center justify-center">
                                <input type="checkbox" name="edit-delete-subtask-<?= $subtask["id"] ?>" class="mx-auto" id="">
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <div class="list-none mb-3 flex justify-center">
                    <input class="w-[90%] px-3 py-2 border rounded" type="text" id="add-task-subtask" name="add-subtask-input" placeholder="Nouvelle étape...">
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="edit-task-echeance">
                    Echéance
                </label>
                <input class="w-full px-3 py-2 border rounded" type="text" id="edit-task-echeance" name="edit-task-echeance" value="<?php if ($task["echeance"] != null) : ?> <?= $task["echeance"] ?> <?php endif ?>">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="edit-task-note">
                    Note
                </label>
                <textarea class="resize-none w-full h-[300px] md:h-[150px] px-3 py-2 border rounded" id="edit-task-note" name="edit-task-note"><?= ($task["note"] != null) ? $task["note"] : '' ?></textarea>
            </div>
            <div class="buttons grid grid-cols-2 gap-8">
                <button class="bg-[#FF7675] p-4 rounded-md text-white" type="submit" name="edit-submit">Enregistrer</button>
                <button class="bg-[#CCCCCC] p-4 rounded-md text-white">Annuler</button>
            </div>
        </form>
    </div>
<?php
} ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    // window.onload = location.reload();

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

    const deletes = document.querySelectorAll("[id^='delete-']");
    deletes.forEach(function(deletes) {
        deletes.addEventListener("click", function() {
            console.log("delete")
            location.href = "/list.php?delete=<?= $task["id"] ?>"
        });
    });

    const tasks = document.querySelectorAll("[id^='task-']");
    let selectedTask = null;

    tasks.forEach(function(task) {
        const taskId = task.getAttribute("id");
        let taskName = taskId.substring(5);

        const taskEditModal = document.getElementById(`edit-task-form-${taskName}`);

        const addSubTask = document.getElementById("add-subtask")
        const addSubTaskInput = document.getElementById("add-subtask-input")

        task.addEventListener("click", function(event) {
            if (event.target.type !== 'checkbox') {
                if (selectedTask !== null) {
                    const previousTaskEditModal = document.getElementById(`edit-task-form-${selectedTask}`);
                    previousTaskEditModal.classList.add("hidden");
                }

                if (selectedTask === taskName) {
                    taskEditModal.classList.add("hidden");
                    selectedTask = null;
                } else {
                    taskEditModal.classList.remove("hidden");
                    selectedTask = taskName;
                }

                const dateInput = document.querySelectorAll(`#edit-task-echeance`);
                flatpickr(dateInput, {
                    dateFormat: "Y-m-d",
                    defaultDate: "<?php echo ($task['echeance'] != null) ? $task['echeance'] : 'today' ?>",
                    minDate: "today",
                    enableTime: false,
                });

                const closeEditTaskButton = document.getElementById(`close-edit-task-${selectedTask}`);

                closeEditTaskButton.addEventListener('click', (e) => {
                    const taskEditModal = closeEditTaskButton.parentNode.parentNode;
                    taskEditModal.classList.add('hidden');
                    selectedTask = null;
                });
            }
        });
    });


    const buttonDeleteList = document.getElementById("delete-list-<?= $list["name"] ?>")

    const modal = document.getElementById("modal");
    const buttonDelete = document.querySelector("#modal button:first-child");
    const buttonCancel = document.querySelector("#modal button:last-child");

    buttonDeleteList.addEventListener("click", () => {
        modal.classList.toggle("hidden");
    });

    buttonDelete.addEventListener("click", () => {
        modal.classList.toggle("hidden");

        const form = document.createElement('form');
        form.method = 'post';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = "delete-list";
        input.value = "<?= $list["name"] ?>"

        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    });

    buttonCancel.addEventListener("click", () => {
        modal.classList.toggle("hidden");
    });
</script>

<?php
include_once('./components/footer.php');
?>