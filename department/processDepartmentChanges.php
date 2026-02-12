<?php
require_once("data/db.php");

session_start();
session_regenerate_id();

$entryURL = $_SERVER['HTTP_REFERER'];

if($_POST && isset($_POST['clearChanges'])){
    $_SESSION['errors']['deptFullName'] = "";
    $_SESSION['errors']['deptShortName'] = "";
    $_SESSION['messages']['updateSuccess'] = "";
    $_SESSION['messages']['updateError'] = "";

    header("Location: $entryURL", true, 301);
}

if($_POST && isset($_POST['saveChanges'])){
    $deptID = $_POST['deptID'];
    $deptFullName = $_POST['deptFullName'];
    $deptShortName = $_POST['deptShortName'];

    $_SESSION['input']['deptFullName'] = $deptFullName;
    $_SESSION['input']['deptShortName'] = $deptShortName;

    if(isset($_SESSION['errors'])){
        $_SESSION['errors'] = [];
    }

    if(filter_input(INPUT_POST,'deptFullName', FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[A-z\s\-]+$/"]]) === false){
        $_SESSION['errors']['deptFullName'] = "Invalid Full Name entry. Reverting to original value";
    } else {
        $_SESSION['errors']['deptFullName'] = "";
    }

    if(filter_input(INPUT_POST,'deptShortName', FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[A-z\s\-]+$/"]]) === false){
        $_SESSION['errors']['deptShortName'] = "Invalid Short Name entry. Reverting to original value";
    } else {
        $_SESSION['errors']['deptShortName'] = "";
    }

    if(empty($_SESSION['errors']['deptFullName']) && empty($_SESSION['errors']['deptShortName'])){
        
        $dbStatement = $db->prepare('UPDATE departments SET deptfullname = ?, deptshortname = ? WHERE deptid = ?');
        $dbResult = $dbStatement->execute([
            $deptFullName,
            $deptShortName,
            $deptID
        ]);

        if($dbResult){
            $_SESSION['messages']['updateSuccess'] = "Department entry updated successfully";
            $_SESSION['messages']['updateError'] = "";
        } else {
            $_SESSION['messages']['updateError'] = "Failed to update department entry";
            $_SESSION['messages']['updateSuccess'] = "";
        }

        header("Location: $entryURL", true, 301);

    } else {
        header("Location: $entryURL", true, 301);
    }
}

?>