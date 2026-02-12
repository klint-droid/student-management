<?php

    require_once('data/db.php');
    session_start();
    session_regenerate_id();

    $deptID = $_GET['deptid'];
    $dbStatement = $db->prepare("SELECT * FROM departments WHERE deptid = :deptId");
    $dbStatement->execute(['deptId' => $deptID]);
    $department = $dbStatement->fetch();

?>
<h1>Department Update</h1>
<span>
    <?php echo $_SESSION['messages']['updateSuccess'] ?? null; ?>
    <?php echo $_SESSION['messages']['updateFailed'] ?? null; ?>
</span>
<form action="index.php?section=department&page=processDepartmentChanges" method="post">
    <table>
        <tr>
            <td style="width: 10em;">Department ID:</td>
            <td><input type="text" id="deptID" name="deptID" value="<?php echo $department['deptid']; ?>" readonly class="data-input"></td>
        </tr>
        <tr>
            <td style="width: 12em;">Department FullName:</td>
            <td><input type="text" id="deptFullName" name="deptFullName" value="<?php echo $department['deptfullname']; ?>" class="data-input"></td>
            <td>
                <span>
                    <?php echo $_SESSION['errors']['deptFullName'] ?? null; ?>
                </span>
            </td>
        </tr>
        <tr>
            <td>Department Short Name:</td>
            <td><input type="text" id="deptShortName" name="deptShortName" value="<?php echo $department['deptshortname']; ?>" class="data-input"></td>
            <td>
                <span>
                    <?php echo $_SESSION['errors']['deptShortName'] ?? null; ?>
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <button type="submit" name="saveChanges" class="btn btn-primary">
                    Update Department Entry
                </button>
                <button type="submit" name="clearChanges" class="btn">
                    Reset Form
                </button>
                <a href="index.php?section=department&page=departmentList&deptcollid=<?= $department['deptcollid'] ?>" class="btn btn-danger">
                        Exit
                </a>
            </td>
        </tr>
    </table>
</form>
