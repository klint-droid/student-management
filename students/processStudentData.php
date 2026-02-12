<?php
    require_once("data/db.php");

    session_start();
    session_regenerate_id();

    $progid = $_GET['progid'];
    $getProgram = $db->prepare("SELECT progcollid, progcolldeptid FROM programs WHERE progid = :progid");

    $getProgram->execute(['progid' => $progid]);
    $programData = $getProgram->fetch();

    $studcollid = $programData['progcollid'];
    $studcolldeptid = $programData['progcolldeptid'];

    $entryUrl = $_SERVER['HTTP_REFERER'];

    if($_POST && isset($_POST['clearEntries'])){
        $_SESSION['input']['studentID'] = null;
        $_SESSION['input']['studentFirstName'] = null;
        $_SESSION['input']['studentMiddleName'] = null;
        $_SESSION['input']['studentLastName'] = null;
        $_SESSION['input']['studentYear'] = null;
        $_SESSION['messages']['createSuccess'] = "";
        $_SESSION['messages']['createError'] = "";

        $_SESSION['errors']['studentID'] = "";
        $_SESSION['errors']['studentFirstName'] = "";
        $_SESSION['errors']['studentMiddleName'] = "";
        $_SESSION['errors']['studentLastName'] = "";
        $_SESSION['errors']['studentYear'] = "";

        header("Location: $entryUrl", true, 301);
    }

    if($_POST && isset($_POST['saveNewStudentEntry'])){
        $studentID = $_POST['studentID'];
        $studentFirstName = $_POST['studentFirstName'];
        $studentMiddleName = $_POST['studentMiddleName'];
        $studentLastName = $_POST['studentLastName'];
        $studentYear = $_POST['studentYear'];

        $_SESSION['input']['studentID'] = $studentID;
        $_SESSION['input']['studentFirstName'] = $studentFirstName;
        $_SESSION['input']['studentMiddleName'] = $studentMiddleName;
        $_SESSION['input']['studentLastName'] = $studentLastName;
        $_SESSION['input']['studentYear'] = $studentYear;

        if($_SESSION['errors']){
            $_SESSION['errors'] = [];
        }

        $studentID = filter_input(INPUT_POST, 'studentID', FILTER_VALIDATE_INT);

        if(!ctype_digit($studentID)){
            $_SESSION['errors']['studentID'] = "Invalid ID entry or format";
        } else if(strlen($studentID) > 8 || strlen($studentID) < 8){
            $_SESSION['errors']['studentID'] = "ID must be 8 digits";
        } else {
            $_SESSION['errors']['studentID'] = "";
        }
        
        if(filter_input(INPUT_POST, 'studentFirstName', FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[A-z\s\-]+$/"]]) == false){
            $_SESSION['errors']['studentFirstName'] = "Invalid Name entry or format";
        } else {
            $_SESSION['errors']['studentFirstName'] = "";
        }

        if(filter_input(INPUT_POST, 'studentMiddleName', FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[A-z\s\-]+$/"]]) == false){
            $_SESSION['errors']['studentMiddleName'] = "Invalid Middle Name entry or format";
        } else {
            $_SESSION['errors']['studentMiddleName'] = "";
        }

        if(filter_input(INPUT_POST, 'studentLastName', FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[A-z\s\-]+$/"]]) == false){
            $_SESSION['errors']['studentLastName'] = "Invalid Last Name entry or format";
        } else {
            $_SESSION['errors']['studentLastName'] = "";
        }

        if(filter_input(INPUT_POST, 'studentYear', FILTER_VALIDATE_INT) == false){
            $_SESSION['errors']['studentYear'] = "Invalid Year entry or format";
        } else {
            $_SESSION['errors']['studentYear'] = "";
        }

        if(empty($_SESSION['errors']['studentID']) && empty($_SESSION['errors']['studentFirstName']) && empty($_SESSION['errors']['studentMiddleName']) && empty($_SESSION['errors']['studentLastName']) && empty($_SESSION['errors']['studentYear'])){
            $dbStatement = $db->prepare("INSERT INTO students (studid, studfirstname, studmidname, studlastname, studcollid, studcolldeptid, studprogid, studyear) VALUES (:studid, :studfirstname, :studmidname, :studlastname, :studcollid, :studcolldeptid, :studprogid, :studyear)");
            $dbResult = $dbStatement->execute([
                'studid' => $studentID,
                'studfirstname' => $studentFirstName,
                'studmidname' => $studentMiddleName,
                'studlastname' =>  $studentLastName,
                'studcollid' => $studcollid,
                'studcolldeptid' => $studcolldeptid,
                'studprogid' => $progid,
                'studyear' => $studentYear
            ]);
            if($dbResult){
                $_SESSION['messages']['createSuccess'] = "Student entry created successfully";
                $_SESSION['messages']['createError'] = "";
            } else {
                $_SESSION['messages']['createError'] = "Failed to create student entry";
                $_SESSION['messages']['createSuccess'] = "";
            }        
            header("Location: $entryUrl", true, 301);
        }
        else{
            header("Location: $entryUrl", true, 301);
        }
    }
?>