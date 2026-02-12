<?php
    session_start();
    session_regenerate_id();

    require_once('data/db.php');

    $deptid = $_GET['deptid'];
    $getDepartment = $db->prepare("SELECT deptid, deptcollid FROM departments d WHERE d.deptid = :deptid");
    $getDepartment->execute(['deptid' => $deptid]);
    $department = $getDepartment->fetch();

    $progcolldeptid = $department['deptid'];
    $progcollid = $department['deptcollid'];
    
    $entryUrl = $_SERVER['HTTP_REFERER'];

    if($_POST && isset($_POST['clearEntries'])){
        $_SESSION['input']['programID'] = null;
        $_SESSION['input']['programFullName'] = null;
        $_SESSION['input']['programShortName'] = null;
        $_SESSION['messages']['createSuccess'] = "";
        $_SESSION['messages']['createError'] = "";

        $_SESSION['errors']['programID'] = "";
        $_SESSION['errors']['programFullName'] = "";
        $_SESSION['errors']['programShortName'] = "";

        header("Location: $entryUrl", true, 301);
    }

    if($_POST && isset($_POST['saveNewProgramEntry'])){
        $programID = $_POST['programID'];
        $programName = $_POST['programFullName'];
        $programShortName = $_POST['programShortName'];

        $_SESSION['input']['programID'] = $programID;
        $_SESSION['input']['programFullName'] = $programName;
        $_SESSION['input']['programShortName'] = $programShortName;

        if($_SESSION['errors']){
            $_SESSION['errors'] = [];
        }

        if(filter_input(INPUT_POST, 'programID', FILTER_VALIDATE_INT) == false){
            $_SESSION['errors']['programID'] = "Invalid ID entry or format";
        } else {
            $_SESSION['errors']['programID'] = "";
        }

        if(filter_input(INPUT_POST, 'programFullName', FILTER_VALIDATE_REGEXP, ["options" => ["regexp"=>"/^[A-z\s\-]+$/"]]) == false){
            $_SESSION['errors']['programFullName'] = "Invalid Name entry or format";
        } else {
            $_SESSION['errors']['programFullName'] = "";   
        }

        if(filter_input(INPUT_POST, 'programShortName', FILTER_VALIDATE_REGEXP, ["options" => ["regexp"=>"/^[A-z\s\-]+$/"]]) == false){
            $_SESSION['errors']['programShortName'] = "Invalid Short Name entry or format";
        } else {
            $_SESSION['errors']['programShortName'] = "";   
        }

        if(empty($_SESSION['errors']['programID']) && empty($_SESSION['errors']['programFullName']) && empty($_SESSION['errors']['programShortName'])){
            $insertProgram = $db->prepare("INSERT INTO programs (progid, progfullname, progshortname, progcollid, progcolldeptid) VALUES (:progid, :progfullname, :progshortname, :progcollid, :progcolldeptid)");
            $dbResult = $insertProgram->execute([
                'progid' => $programID,
                'progfullname' => $programName,
                'progshortname' => $programShortName,
                'progcollid' => $progcollid,
                'progcolldeptid' => $progcolldeptid
            ]);
            if($dbResult){
                $_SESSION['messages']['createSuccess'] = "Program entry created successfully";
                $_SESSION['messages']['createError'] = "";
            } else {
                $_SESSION['messages']['createError'] = "Failed to create program entry";
                $_SESSION['messages']['createSuccess'] = "";
            }        
            header("Location: $entryUrl", true, 301);
        } else {
            header("Location: $entryUrl", true, 301);
        }
    }
?>