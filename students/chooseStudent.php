<?php
require_once("data/db.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    unset($_SESSION['school'], $_SESSION['department'], $_SESSION['program']);
}

$schools = [];
$departments = [];
$programs = [];

$dbStatement = $db->prepare("SELECT * FROM colleges");
$dbStatement->execute();
$schools = $dbStatement->fetchAll();

if (isset($_POST['selectSchool'])) {

    $newSchool = $_POST['schoolID'];

    if (!isset($_SESSION['school']) || $_SESSION['school'] != $newSchool) {
        $_SESSION['department'] = null;
        $_SESSION['program'] = null;
    }

    $_SESSION['school'] = $newSchool;
}

if (isset($_POST['selectDepartment'])) {

    $newDepartment = $_POST['departmentID'];

    if (!isset($_SESSION['department']) || $_SESSION['department'] != $newDepartment) {
        $_SESSION['program'] = null;
    }

    $_SESSION['department'] = $newDepartment;
}


if (isset($_POST['selectProgram'])) {

    $_SESSION['program'] = $_POST['programID'];

    header("Location: index.php?section=students&page=studentsList&progid={$_SESSION['program']}");
    exit;
}

$schoolID = $_SESSION['school'] ?? null;
$departmentID = $_SESSION['department'] ?? null;
$programID = $_SESSION['program'] ?? null;

if ($schoolID) {
    $dbStatement = $db->prepare("SELECT * FROM departments WHERE deptcollid = :schoolID");
    $dbStatement->bindParam(':schoolID', $schoolID);
    $dbStatement->execute();
    $departments = $dbStatement->fetchAll();
}

if ($departmentID) {
    $dbStatement = $db->prepare("SELECT * FROM programs WHERE progcolldeptid = :deptID");
    $dbStatement->bindParam(':deptID', $departmentID);
    $dbStatement->execute();
    $programs = $dbStatement->fetchAll();
}
?>

<h1>Select School</h1>
<form action="index.php?section=students&page=chooseStudent" method="post">
    <table>
        <tr>
            <td>
                <select name="schoolID" class="school-select">
                    <option value="" disabled>--Select School--</option>

                    <?php foreach ($schools as $school): ?>
                        <option value="<?= $school['collid']; ?>"
                            <?= ($schoolID == $school['collid']) ? 'selected' : ''; ?>>
                            <?= $school['collfullname']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="selectSchool" class="btn btn-info">Select School</button>
            </td>
        </tr>

        <tr>
            <td>
                <select name="departmentID"
                    <?= $schoolID ? '' : 'disabled'; ?> class="school-select">

                    <option value="" disabled
                        <?= !$departmentID ? 'selected' : ''; ?>>
                        <?= $schoolID ? 'Select Department' : 'Select School First'; ?>
                    </option>

                    <?php foreach ($departments as $department): ?>
                        <option value="<?= $department['deptid']; ?>"
                            <?= ($departmentID == $department['deptid']) ? 'selected' : ''; ?>>
                            <?= $department['deptfullname']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="selectDepartment" class="btn btn-info">Select Department</button>
            </td>
        </tr>

        <tr>
            <td>
               <select name="programID"
                    <?= $departmentID ? '' : 'disabled'; ?> class="school-select">

                    <option value="" disabled
                        <?= !$programID ? 'selected' : ''; ?>>
                        <?= $departmentID ? 'Select Program' : 'Select Department First'; ?>
                    </option>

                    <?php foreach ($programs as $program): ?>
                        <option value="<?= $program['progid']; ?>"
                            <?= ($programID == $program['progid']) ? 'selected' : ''; ?>>
                            <?= $program['progfullname']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="selectProgram" class="btn btn-info">Select Program</button>
            </td>
        </tr>
    </table>
</form>
