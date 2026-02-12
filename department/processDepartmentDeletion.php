<?php

    require_once('data/db.php');
    session_start();
    session_regenerate_id();

    $entryURL = $_SERVER['HTTP_REFERER'];

    if($_POST && isset($_POST['confirmDelete'])){
        $departmentID = $_POST['departmentID'];

        $dbStatement = $db->prepare("SELECT * FROM programs WHERE progcolldeptid = :departmentID");
        $dbStatement->execute(['departmentID' => $departmentID]);
        $programs = $dbStatement->fetchAll(PDO::FETCH_ASSOC);

        if(count($programs) > 0){
            $_SESSION['messages']['deleteError'] = "Cannot delete department entry because it has associated program entries.";
            $_SESSION['messages']['deleteSuccess'] = "";
            header("Location: $entryURL", true, 301);
            exit();
        }

        $dbStatement = $db->prepare("DELETE FROM departments WHERE deptid = ?");
        $dbResult = $dbStatement->execute([$departmentID]);

        if($dbResult){
            $_SESSION['messages']['deleteSuccess'] = "Department entry deleted successfully";
            $_SESSION['messages']['deleteError'] = "";
        } else {
            $_SESSION['messages']['deleteError'] = "Failed to delete department entry";
            $_SESSION['messages']['deleteSuccess'] = "";
        }

        header("Location: $entryURL", true, 301);
    }
?>