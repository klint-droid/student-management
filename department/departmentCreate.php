<?php

    session_start();
    session_regenerate_id();
    require_once("data/db.php");

    if(!isset($_GET['collid']) || empty($_GET['collid'])) {
        die("College not specified.");
    }
    $collid = $_GET['collid'];

    $dbStatement = $db->prepare("SELECT collid FROM colleges c WHERE c.collid = :collid");
    $dbStatement->execute(['collid' => $collid]);
    $collegeData = $dbStatement->fetch();

    if(!$collegeData){
        die("Invalid college.");
    }
?>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Create</title>
    <link rel="stylesheet" href="../styles/styles.css">
</head>
<body> -->
    <h1>School Create</h1>
    <span>
        <?php echo $_SESSION['messages']['createSuccess'] ?? null; ?>
        <?php echo $_SESSION['messages']['createError'] ?? null; ?>
    </span>
    <form action="index.php?section=department&page=processDepartmentData&collid=<?= $collegeData['collid']; ?>" method="post">
        <table>
            <tr>
                <td style="width: 10em;">Department ID:</td>
                <td style="width: 30em;"><input type="text" id="deptID" name="deptID" value="<?= $_SESSION['input']['deptID'] ?? null; ?>" class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['deptID'] ?? null; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td style="width: 12em;">Department Full Name:</td>
                <td><input type="text" id="departmentFullName" name="departmentFullName" value="<?= $_SESSION['input']['departmentFullName'] ?? null; ?>" class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['departmentFullName'] ?? null; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td>Department Short Name:</td>
                <td><input type="text" id="departmentShortName" name="departmentShortName" value="<?= $_SESSION['input']['departmentShortName'] ?? null; ?>" class="data-input"></td>
                <td>
                    <span>
                        <?php echo $_SESSION['errors']['departmentShortName'] ?? null; ?>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit" name="saveNewDepartmentEntry" class="btn btn-primary">
                        Save New Department Entry
                    </button>
                    <button type="submit" name="clearEntries" class="btn">
                        Reset Form
                    </button>
                    <a href="index.php?section=department&page=departmentList&deptcollid=<?= $collegeData['collid'] ?>" class="btn btn-danger">
                        Exit
                    </a>
                </td>
            </tr>
        </table>
    </form>    
<!-- </body>
</html> -->