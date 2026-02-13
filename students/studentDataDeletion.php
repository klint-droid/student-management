<?php
require_once("data/db.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmDelete'])) {

    if (!isset($_POST['studId']) || !filter_var($_POST['studId'], FILTER_VALIDATE_INT)) {
        header("Location: index.php?section=students&page=studentList");
        exit();
    }

    $studId = $_POST['studId'];
    $progId = $_POST['progId']; 

    $stmt = $db->prepare("DELETE FROM students WHERE studid = :studId");
    $result = $stmt->execute(['studId' => $studId]);

    if ($result) {
        $_SESSION['messages']['deleteSuccess'] = "Student deleted successfully.";
    } else {
        $_SESSION['messages']['deleteError'] = "Failed to delete student.";
    }

    header("Location: index.php?section=students&page=studentsList&progid=$progId");
    exit();
}
