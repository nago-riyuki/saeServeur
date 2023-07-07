<?php
include('../config/database.php');

if (!isset($_GET["id"]) || !isset($_GET["list_id"])) {
    header("Location: ../home.php");
}

if ($_POST) {
    if (isset($_POST["edit-task-name"])) {
        if ($_POST["edit-task-name"] <> '') {
            $database->insert("UPDATE tasks SET name = ? WHERE id = ?", array($_POST["edit-task-name"], $_GET["id"]));
        }
    }

    if (isset($_POST["add-subtask-input"])) {
        if ($_POST["add-subtask-input"] <> '') {
            $isExist = $database->query("SELECT * FROM subtasks WHERE name LIKE ?", array('%' . $_POST["add-subtask-input"] . '%'));
            $isExist = $isExist->fetchAll();

            if ($isExist) {
                $count = count($isExist);
                $name = $_POST["add-subtask-input"] . $count;
                $database->insert("INSERT INTO subtasks (name, task_id) VALUES (?, ?)", array($name, $_GET["id"]));
            } else {
                $database->insert("INSERT INTO subtasks (name, task_id) VALUES (?, ?)", array($_POST["add-subtask-input"], $_GET["id"]));
            }
        }
    }

    if (isset($_POST["edit-task-echeance"])) {
        $date = $_POST["edit-task-echeance"];
        $format = 'Y-m-d';
        $datetime = DateTime::createFromFormat($format, $date);
        if ($datetime && $datetime->format($format) == $date) {
            $database->insert("UPDATE tasks SET echeance = ? WHERE id = ?", array($date, $_GET["id"]));
        } else {
            echo "Le format de la date n'est pas valide.";
        }
    }

    if (isset($_POST["edit-task-note"])) {
        if(empty($_POST["edit-task-note"])) {
            $database->insert("UPDATE tasks SET note = ? WHERE id = ?", array(null, $_GET["id"]));
        } else {
            $database->insert("UPDATE tasks SET note = ? WHERE id = ?", array($_POST["edit-task-note"], $_GET["id"]));
        }
    } 

    $editSubtasks = array();
    foreach ($_POST as $key => $value) {
        if (strpos($key, "edit-task-subtask-") === 0) {
            $subtaskId = substr($key, strlen("edit-task-subtask-"));
            $editSubtasks[$subtaskId] = $value;
        }
    }

    foreach ($editSubtasks as $subtaskId => $subtaskName) {
        $isExist = $database->query("SELECT * FROM subtasks WHERE name LIKE ?", array('%' . $subtaskName . '%'));
        $isExist = $isExist->fetchAll();

        if ($isExist) {
            echo 1;
        } else {
            $database->insert("UPDATE subtasks SET name = ? WHERE id = ?", array($subtaskName, $subtaskId));
        }
    }

    $deleteSubTasks = array();
    foreach ($_POST as $key => $value) {
        if (strpos($key, "edit-delete-subtask-") === 0) {
            $subtaskId = substr($key, strlen("edit-delete-subtask-"));
            $deleteSubTasks[$subtaskId] = $value;
        }
    }

    foreach ($deleteSubTasks as $subtaskId => $subtaskName) {
        $database->insert("DELETE FROM subtasks WHERE id = ?", array($subtaskId));
    }

    $editSubtasksStatus = array();
    foreach ($_POST as $key => $value) {
        if (strpos($key, "edit-status-subtask-") === 0) {
            $subtaskId = substr($key, strlen("edit-status-subtask-"));
            $editSubtasksStatus[$subtaskId] = $value;
        }
    }


    $database->insert("UPDATE subtasks SET status = ? WHERE task_id = ?", array(0, $_GET["id"]));

    foreach ($editSubtasksStatus as $subtaskId => $subtaskStatus) {
        echo $subtaskId;
        $database->insert("UPDATE subtasks SET status = ? WHERE id = ?", array(1, $subtaskId));
    }

    header("Location: ../list.php?token={$_GET["list_id"]}");
}
