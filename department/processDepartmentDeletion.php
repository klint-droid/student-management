<?php
require_once('data/db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmDelete'])) {

    if (!isset($_POST['departmentID']) || !filter_var($_POST['departmentID'], FILTER_VALIDATE_INT)) {
        header("Location: index.php?section=department&page=departmentList");
        exit();
    }

    $departmentID = $_POST['departmentID'];

    // 🔹 Get deptcollid FIRST (needed for redirect)
    $stmt = $db->prepare("SELECT deptcollid FROM departments WHERE deptid = :deptid");
    $stmt->execute(['deptid' => $departmentID]);
    $department = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$department) {
        header("Location: index.php?section=department&page=departmentList");
        exit();
    }

    $deptcollid = $department['deptcollid'];

    // 🔹 Check if department has programs
    $stmt = $db->prepare("SELECT 1 FROM programs WHERE progcolldeptid = :departmentID LIMIT 1");
    $stmt->execute(['departmentID' => $departmentID]);

    if ($stmt->fetch()) {
        $_SESSION['messages']['deleteError'] =
            "Cannot delete department because it has associated program entries.";
        header("Location: index.php?section=department&page=departmentDelete&deptid=$departmentID");
        exit();
    }

    // 🔹 Delete department
    $stmt = $db->prepare("DELETE FROM departments WHERE deptid = :departmentID");
    $result = $stmt->execute(['departmentID' => $departmentID]);

    if ($result) {
        $_SESSION['messages']['deleteSuccess'] = "Department deleted successfully.";
    } else {
        $_SESSION['messages']['deleteError'] = "Failed to delete department.";
    }

    // 🔹 Correct redirect
    header("Location: index.php?section=department&page=departmentList&deptcollid=$deptcollid");
    exit();
}
