<?php
require_once("data/db.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $_SESSION['errors'] = [];

    $studId = filter_input(INPUT_POST, 'studId', FILTER_VALIDATE_INT);
    $studFirstName  = trim($_POST['studFirstName'] ?? '');
    $studMiddleName = trim($_POST['studMiddleName'] ?? '');
    $studLastName   = trim($_POST['studLastName'] ?? '');
    $progId         = filter_input(INPUT_POST, 'studProg', FILTER_VALIDATE_INT);

    if (!$studId || !$progId) {
        header("Location: index.php?section=students&page=studentsList");
        exit();
    }

    if ($studFirstName === '') {
        $_SESSION['errors']['studFirstName'] = "First name is required.";
    }

    if ($studLastName === '') {
        $_SESSION['errors']['studLastName'] = "Last name is required.";
    }

    if (!empty($_SESSION['errors'])) {
        $_SESSION['messages']['updateError'] = "Please fix the errors below.";
        header("Location: index.php?section=students&page=studentUpdate&progid=$progId&studid=$studId");
        exit();
    }

    $stmt = $db->prepare("
        UPDATE students
        SET studfirstname = :studFirstName,
            studmidname   = :studMiddleName,
            studlastname  = :studLastName
        WHERE studid = :studId
    ");

    $result = $stmt->execute([
        'studFirstName'  => $studFirstName,
        'studMiddleName' => $studMiddleName,
        'studLastName'   => $studLastName,
        'studId'         => $studId
    ]);

    if ($result) {
        $_SESSION['messages']['updateSuccess'] = "Student updated successfully.";
    } else {
        $_SESSION['messages']['updateError'] = "Failed to update student.";
    }
    header("Location: index.php?section=students&page=studentsList&progid=$progId&pg=1");
    exit();
}
