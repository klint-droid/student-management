<?php
    session_start();
    session_regenerate_id();
    require_once("data/db.php");

    if(!isset($_GET['deptid']) || empty($_GET['deptid'])) {
        die("Department not specified.");
    }
    $deptid = $_GET['deptid'];

    $dbStatement = $db->prepare("SELECT deptid, deptfullname FROM departments d WHERE d.deptid = :deptid");
    $dbStatement->execute(['deptid' => $deptid]);
    $departmentData = $dbStatement->fetch();

    if(!$departmentData){
        die("Invalid department.");
    }

?>

<!--<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>-->
    <h1>Create Program - <?= htmlspecialchars($departmentData['deptfullname']); ?></h1>
    <span>
        <?php echo $_SESSION['messages']['createSuccess'] ?? null; ?>
        <?php echo $_SESSION['messages']['createError'] ?? null; ?>
    </span>
    <form action="index.php?section=programs&page=processProgramData&deptid=<?= $departmentData['deptid']; ?>" method="post">
        <table>
            <tr>
                <td>Program ID: </td>
                <td><input type="text" id="programID" name="programID" value="<?= $_SESSION['input']['programID'] ?? null; ?>" class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['programID'] ?? null; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Program Full Name: </td>
                <td><input type="text" id="programFullName" name="programFullName" value="<?= $_SESSION['input']['programFullName'] ?? null; ?>" class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['programFullName'] ?? null; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Program Short Name: </td>
                <td><input type="text" id="programShortName" name="programShortName" value="<?= $_SESSION['input']['programShortName'] ?? null; ?>" class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['programShortName'] ?? null; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit" name="saveNewProgramEntry" class="btn btn-primary">
                        Save Program Entry
                    </button>
                    <button type="submit" name="clearEntries" class="btn">
                        Clear Entries
                    </button>
                    <a href="index.php?section=programs&page=programsList&deptid=<?= $departmentData['deptid']; ?>" class="btn btn-danger">
                        Exit
                    </a>
                </td>
            </tr>
        </table>
    </form>
<!--</body>
</html>-->