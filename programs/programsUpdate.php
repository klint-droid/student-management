<?php
    require_once("data/db.php");
    session_start();
    session_regenerate_id();

    $schoolID = $_GET['progid'];
    $deptID = $_GET['deptid'];
    
    $dbStatement = $db->prepare("SELECT * FROM programs  WHERE progid = :progid");
    $dbStatement->execute(['progid' => $_GET['progid']]);
    $department = $dbStatement->fetch();
?>
<h1>Program Update</h1>
<span>
    <?php echo $_SESSION['messages']['updateSuccess'] ?? null; ?>
    <?php echo $_SESSION['messages']['updateError'] ?? null; ?>
</span>
<form action="index.php?section=programs&page=processProgramChanges" method="post">
    <table>
        <tr>
            <td style="width: 10em;">Program ID:</td>
            <td style="width: 30em;"><input type="text" id="progid" name="progid" value="<?php echo $department['progid']; ?>" readonly class="data-input"></td>
        </tr>
        <tr>
            <td>Program Full Name:</td>
            <td><input type="text" id="progFullName" name="progFullName" value="<?php echo $department['progfullname']; ?>" class="data-input"></td>
            <td>
                <span>
                    <?php echo $_SESSION['errors']['progFullName'] ?? null; ?>
                </span>
            </td>                
        </tr>
        <tr>
            <td>Program Short Name:</td>
            <td><input type="text" id="progShortName" name="progShortName" value="<?php echo $department['progshortname']; ?>" class="data-input"></td>
            <td>
                <span>
                    <?php echo $_SESSION['errors']['progShortName'] ?? null; ?>
                </span>
            </td>                
        </tr>
        <tr>
            <td colspan="2">
                <button type="submit" name="saveChanges" class="btn">
                    Update Program Entry
                </button>
                <button type="submit" name="clearChanges" class="btn">
                    Reset Form
                </button>
                <a href="index.php?section=programs&page=programsList&deptid=<?php echo $deptID; ?>" class="btn btn-danger">
                    Exit
                </a>
            </td>
        </tr>
    </table>
</form>    
