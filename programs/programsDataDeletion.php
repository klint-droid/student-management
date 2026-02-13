<?php
require_once("data/db.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmDelete'])) {

    if (!isset($_POST['programID']) || !filter_var($_POST['programID'], FILTER_VALIDATE_INT)) {
        header("Location: index.php?section=programs&page=programsList");
        exit();
    }

    $programID = $_POST['programID'];
    $stmt = $db->prepare("SELECT progcolldeptid FROM programs WHERE progid = :programID");
    $stmt->execute(['programID' => $programID]);
    $program = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$program) {
        header("Location: index.php?section=programs&page=programsList");
        exit();
    }

    $deptid = $program['progcolldeptid'];
    $checkStmt = $db->prepare("SELECT 1 FROM students WHERE studprogid = :programID LIMIT 1");
    $checkStmt->execute(['programID' => $programID]);

    if ($checkStmt->fetch()) {
        $_SESSION['messages']['deleteError'] =
            "Cannot delete program because it has associated student records.";
        header("Location: index.php?section=programs&page=programsDelete&progid=$programID");
        exit();
    }

    $deleteStmt = $db->prepare("DELETE FROM programs WHERE progid = :programID");
    $result = $deleteStmt->execute(['programID' => $programID]);

    if ($result) {
        $_SESSION['messages']['deleteSuccess'] = "Program deleted successfully.";
    } else {
        $_SESSION['messages']['deleteError'] = "Failed to delete program.";
    }

    header("Location: index.php?section=programs&page=programsList&deptid=$deptid");
    exit();
}
