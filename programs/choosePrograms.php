<?php
require_once("data/db.php");
session_start();


$dbStatement = $db->prepare("SELECT * FROM colleges");
$dbStatement->execute();
$schools = $dbStatement->fetchAll();

$departments = [];
$schoolID = $_SESSION['school'] ?? null;
$departmentID = $_SESSION['department'] ?? null;


if (isset($_POST['selectSchool'])) {

    $newSchoolID = $_POST['schoolID'];

    if (!isset($_SESSION['school']) || $_SESSION['school'] != $newSchoolID) {
        $_SESSION['department'] = null;
    }

    $_SESSION['school'] = $newSchoolID;
    $schoolID = $newSchoolID;


    $dbStatement = $db->prepare("SELECT * FROM departments WHERE deptcollid = :schoolID");
    $dbStatement->execute(['schoolID' => $schoolID]);
    $departments = $dbStatement->fetchAll();
}

if (isset($_POST['selectDepartment'])) {

    if (!empty($_SESSION['school'])) {

        $departmentID = $_POST['departmentID'];
        $_SESSION['department'] = $departmentID;

        header("Location: index.php?section=programs&page=programsList&deptid={$departmentID}");
        exit();

    } else {
        $_SESSION['error'] = "Select school first.";
    }
}
?>
<h1>Select School</h1>
<form action="index.php?section=programs&page=choosePrograms" method="post">
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
                <button type="submit" name="selectDepartment" class="btn btn-info"
                    <?= $schoolID ? '' : 'disabled'; ?>>Select Department</button>
            </td>
        </tr>
    </table>
</form>