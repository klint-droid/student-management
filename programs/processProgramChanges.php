<?php
require_once("data/db.php");

session_start();
session_regenerate_id();

$entryURL = $_SERVER['HTTP_REFERER'];

if($_POST && isset($_POST['clearChanges'])){
    $_SESSION['errors']['progFullName'] = "";
    $_SESSION['errors']['progShortName'] = "";
    $_SESSION['messages']['updateSuccess'] = "";
    $_SESSION['messages']['updateError'] = "";

    header("Location: $entryURL", true, 301);
}

if($_POST && isset($_POST['saveChanges'])){
    $progId = $_POST['progid'];
    $progFullName = $_POST['progFullName'];
    $progShortName = $_POST['progShortName'];

    $_SESSION['input']['progFullName'] = $progFullName;
    $_SESSION['input']['progShortName'] = $progShortName;

    if(isset($_SESSION['errors'])){
        $_SESSION['errors'] = [];
    }

    if(filter_input(INPUT_POST,'progFullName', FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[A-z\s\-]+$/"]]) === false){
        $_SESSION['errors']['progFullName'] = "Invalid Full Name entry. Reverting to original value";
    } else {
        $_SESSION['errors']['progFullName'] = "";
    }

      $dbStatement = $db->prepare('SELECT * FROM programs WHERE progfullname = ? AND progshortname = ?');
    $dbStatement->execute([
        $progFullName,
        $progShortName
    ]); 
      if($dbStatement->rowCount() > 0){
            $_SESSION['errors']['progFullName'] = "Same Data, Ignoring update to this field";
            $_SESSION['errors']['progShortName'] = "Same Data, Ignoring update to this field";
            header("Location: $entryURL", true, 301);
            exit();
        }


    if(filter_input(INPUT_POST,'progShortName', FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[A-z\s\-]+$/"]]) === false){
        $_SESSION['errors']['schoolShortName'] = "Invalid Short Name entry. Reverting to original value";
    } else {
        $_SESSION['errors']['schoolShortName'] = "";
    }
  

    if(empty($_SESSION['errors']['progFullName']) && empty($_SESSION['errors']['progShortName'])){
      
        $dbStatement = $db->prepare('UPDATE programs SET progfullname = ?, progshortname = ? WHERE progid = ?');
        $dbResult = $dbStatement->execute([
            $progFullName,
            $progShortName,
            $progId
        ]);

        if($dbResult){
            $_SESSION['messages']['updateSuccess'] = "Program entry updated successfully";
            $_SESSION['messages']['updateError'] = "";
        } else {
            $_SESSION['messages']['updateError'] = "Failed to update program entry";
            $_SESSION['messages']['updateSuccess'] = "";
        }

        header("Location: $entryURL", true, 301);

    } else {
        header("Location: $entryURL", true, 301);
    }
}

?>