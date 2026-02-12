<?php
require_once("data/db.php");

session_start();
session_regenerate_id();

$schoolData = $_GET['collid'] ?? null;
if(!$schoolData){
    die("School not specified.");
}

$getSchool = $db->prepare("SELECT collid FROM colleges WHERE collid = :schoolID");
$getSchool->execute(['schoolID' => $schoolData]);
$school = $getSchool->fetch();

if(!$school){
    die("Invalid department.");
}

$deptcollid = $school['collid'];


$entryURL = $_SERVER['HTTP_REFERER'];

if($_POST && isset($_POST['clearEntries'])){
    $_SESSION['input']['deptID'] = null;
    $_SESSION['input']['departmentFullName'] = null;
    $_SESSION['input']['departmentShortName'] = null;
    $_SESSION['messages']['createSuccess'] = "";
    $_SESSION['messages']['createError'] = "";    

    $_SESSION['errors']['deptID'] = "";
    $_SESSION['errors']['departmentFullName'] = "";
    $_SESSION['errors']['departmentShortName'] = "";

    header("Location: $entryURL", true, 301);
}

if($_POST && isset($_POST['saveNewDepartmentEntry'])){
    $deptID = $_POST['deptID'];
    $departmentFullName = $_POST['departmentFullName'];
    $departmentShortName = $_POST['departmentShortName'];

    $_SESSION['input']['deptID'] = $deptID;
    $_SESSION['input']['departmentFullName'] = $departmentFullName;
    $_SESSION['input']['departmentShortName'] = $departmentShortName;

    if(!$_SESSION['errors']){
        $_SESSION['errors'] = [];
    }

    if(filter_input(INPUT_POST,'deptID', FILTER_VALIDATE_INT) === false){
        $_SESSION['errors']['deptID'] = "Invalid ID entry or format";
    } else {
        $_SESSION['errors']['deptID'] = "";
    } 

    if(filter_input(INPUT_POST,'departmentFullName', FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[A-z\s\-]+$/"]]) === false){
        $_SESSION['errors']['departmentFullName'] = "Invalid Full Name entry or format";
    } else {
        $_SESSION['errors']['departmentFullName'] = "";
    }

    if(filter_input(INPUT_POST,'departmentShortName', FILTER_VALIDATE_REGEXP, ["options"=>["regexp"=>"/^[A-z\s\-]+$/"]]) === false){
        $_SESSION['errors']['departmentShortName'] = "Invalid Short Name entry or format";
    } else {
        $_SESSION['errors']['departmentShortName'] = "";
    }

    if(empty($_SESSION['errors']['deptID']) && empty($_SESSION['errors']['departmentFullName']) && empty($_SESSION['errors']['departmentShortName'])){
        $dbStatement = $db->prepare("INSERT INTO departments (deptid, deptfullname, deptshortname, deptcollid) VALUES (:deptid, :deptfullname, :deptshortname, :deptcollid)");
        $dbResult = $dbStatement->execute([
            'deptid' => $deptID,
            'deptfullname' => $departmentFullName,
            'deptshortname' => $departmentShortName,
            'deptcollid' => $deptcollid
        ]);

        if($dbResult){
            $_SESSION['messages']['createSuccess'] = "School entry created successfully";
            $_SESSION['messages']['createError'] = "";
        } else {
            $_SESSION['messages']['createError'] = "Failed to create school entry";
            $_SESSION['messages']['createSuccess'] = "";
        }        

        header("Location: $entryURL", true, 301);
    } else {
        header("Location: $entryURL", true, 301);
    }
}
?>