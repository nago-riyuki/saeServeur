<?php
include('./config/database.php');

if (!empty($_POST)) {
    extract($_POST);
    $valid = true;

    if (isset($_POST['add-list-form'])) {
        $title = htmlspecialchars(trim($_POST["list-name"]));

        if (empty($title)) {
            $valid = false;
            $error_title = "Veuillez renseigner un nom.";
        } else if (strlen($title) > 25) {
            $valid = false;
            $error_title = "Maximum 25 caractères.";
        }

        if ($valid) {
            $database->insert(
                "INSERT INTO lists (name, author_id) VALUES
              (?, ?)",
                array($title, $_SESSION["id"])
            );

            header("Location: home.php");
        }
    }
}
