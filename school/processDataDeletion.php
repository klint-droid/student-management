<?php
    require_once("data/db.php");
    session_start();
    session_regenerate_id();

    $entryURL = $_SERVER['HTTP_REFERER'];

    if($_POST && isset($_POST['confirmDelete'])){
        $schoolID = $_POST['schoolID'];
        $dbStatement = $db->prepare("SELECT * FROM departments WHERE deptcollid = :schoolID");
        $dbStatement->execute([":schoolID" => $schoolID]);
        $departments = $dbStatement->fetchAll(PDO::FETCH_ASSOC);

        if(count($departments) > 0){
            $_SESSION['messages']['deleteError'] = "Cannot delete school entry because it has associated department entries.";
            $_SESSION['messages']['deleteSuccess'] = "";
            header("Location: $entryURL", true, 301);
            exit();
        } 

        $dbStatement = $db->prepare("DELETE FROM colleges WHERE collid = ?");
        $dbResult = $dbStatement->execute([$schoolID]);

        if($dbResult){
            $_SESSION['messages']['deleteSuccess'] = "School entry deleted successfully";
            $_SESSION['messages']['deleteError'] = "";
        } else {
            $_SESSION['messages']['deleteError'] = "Failed to delete school entry";
            $_SESSION['messages']['deleteSuccess'] = "";
        }
    }

    header("Location: $entryURL", true, 301);
    exit();
?>