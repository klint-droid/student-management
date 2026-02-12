<?php

    require_once('data/db.php');
    session_start();
    session_regenerate_id();

    $departmentID = $_GET['deptid'];

    $dbStatement = $db->prepare("SELECT * FROM departments WHERE deptid = :departmentID");
    $dbStatement->execute(['departmentID' => $departmentID]);
    $department = $dbStatement->fetch(PDO::FETCH_ASSOC);
?>

<h1>Department Delete</h1>
<span>
    <?php echo $_SESSION['messages']['deleteSuccess'] ?? null; ?>
    <?php echo $_SESSION['messages']['deleteError'] ?? null; ?>
</span>

<form action="index.php?section=department&page=processDepartmentDeletion" method="post">
    <table>
        <tr>
            <td style="width: 10em;">Department ID:</td>
            <td style="width: 30em;"><input type="text" id="departmentID" name="departmentID" value="<?php echo $department['deptid']; ?>" readonly class="data-input"></td>
        </tr>
        <tr>
            <td>Department Full Name:</td>
            <td><input type="text" id="departmentFullName" name="departmentFullName" value="<?php echo $department['deptfullname'] ?>" class="data-input"></td>
            <td>
                <span>
                    <?php echo $_SESSION['errors']['departmentFullName'] ?? null; ?>
                </span>
            </td>
        </tr>
        <tr>
            <td>Department Short Name:</td>
            <td><input type="text" id="departmentShortName" name="departmentShortName" value="<?php echo $department['deptshortname'] ?>" class="data-input"></td>
            <td>
                <span>
                    <?php echo $_SESSION['errors']['departmentShortName'] ?? null; ?>
                </span>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <a href="index.php?section=department&page=departmentList&deptcollid=<?php echo $department['deptcollid'] ?>" class="btn btn-primary">
                    Cancel Operation
                </a>
                <button type="submit" name="confirmDelete" class="btn btn-danger" onclick="return confirm('Delete this department?')">
                    Confirm Operation
                </button>
            </td>
        </tr>
    </table>
</form>