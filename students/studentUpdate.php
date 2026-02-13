<?php
    require_once("data/db.php");
    session_start();
    session_regenerate_id();

    $studId = $_GET['studid'];
    $progId = $_GET['progid'];


    $dbStatement = $db->prepare("SELECT * FROM students WHERE studid = :studId");
    $dbStatement->execute(['studId' => $studId]);
    $student = $dbStatement->fetch();
?>
<h1>Student Update</h1>
<span>
    <?php echo $_SESSION['messages']['updateSuccess'] ?? null; ?>
    <?php echo $_SESSION['messages']['updateError'] ?? null; ?>
</span>
<form action="index.php?section=students&page=studentDataChanges" method="post">
    <table>
        <tr>
            <td style="width: 10em;">Student ID:</td>
            <td style="width: 30em;"><input type="text" id="studId" name="studId" value="<?php echo $student['studid']; ?>" readonly class="data-input"></td>
        </tr>
        <tr>
            <td>Student First Name:</td>
            <td><input type="text" id="studFirstName" name="studFirstName" value="<?php echo $student['studfirstname']; ?>" class="data-input"></td>
            <td>
                <span>
                    <?php echo $_SESSION['errors']['studFirstName'] ?? null; ?>
                </span>
            </td>                
        </tr>
        <tr>
            <td>Student Middle Name:</td>
            <td><input type="text" id="studMiddleName" name="studMiddleName" value="<?php echo $student['studmidname']; ?>" class="data-input"></td>
            <td>
                <span>
                    <?php echo $_SESSION['errors']['studMiddleName'] ?? null; ?>
                </span>
            </td>                
        </tr>
          <tr>
            <td>Student Last Name:</td>
            <td><input type="text" id="studLastName" name="studLastName" value="<?php echo $student['studlastname']; ?>" class="data-input"></td>
            <td>
                <span>
                    <?php echo $_SESSION['errors']['studLastName'] ?? null; ?>
                </span>
            </td>                
        </tr>
        <tr>
            <td>Student Program:</td>
            <td><input readonly type="text" id="studProg" name="studProg" value="<?php echo $student['studprogid']; ?>" class="data-input"></td>
            <td>
                <span>
                    <?php echo $_SESSION['errors']['studProg'] ?? null; ?>
                </span>
            </td>                
        </tr>
        <tr>
            <td colspan="2">
                <button type="submit" name="saveChanges" class="btn">
                    Update Student Entry
                </button>
                <button type="submit" name="clearChanges" class="btn">
                    Reset Form
                </button>
                <a href="index.php?section=students&page=studentList&progid=<?php echo $student['studprogid']; ?>" class="btn btn-danger">
                    Exit
                </a>
            </td>
        </tr>
    </table>
</form>    
