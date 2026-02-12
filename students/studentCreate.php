<?php
session_start();
session_regenerate_id();

require_once("data/db.php");

if (!isset($_GET['progid']) || empty($_GET['progid'])) {
    die("Program not specified.");
}

$progid = $_GET['progid'];

$dbStatement = $db->prepare("SELECT progid, progfullname FROM programs p WHERE p.progid = :progid
");

$dbStatement->execute(['progid' => $progid]);
$programData = $dbStatement->fetch();

if (!$programData) {
    die("Invalid program.");
}
?>

<h1>Create Student - <?= htmlspecialchars($programData['progfullname']); ?></h1>
<span>
    <?php echo $_SESSION['messages']['createSuccess'] ?? null; ?>
    <?php echo $_SESSION['messages']['createError'] ?? null; ?>
</span>
<form action="index.php?section=students&page=processStudentData&progid=<?= $programData['progid']; ?>" method="post">
<table>

<tr>
    <td>Student ID:</td>
    <td><input type="text" name="studentID" id="studentID" value="<?= $_SESSION['input']['studentID'] ?? null; ?>" class="data-input"></td>
    <td>
        <span>
            <?php echo $_SESSION['errors']['studentID'] ?? null; ?>
        </span>
    </td>
</tr>

<tr>
    <td>First Name:</td>
    <td><input type="text" name="studentFirstName" id="studentFirstName" value="<?= $_SESSION['input']['studentFirstName'] ?? null; ?>" class="data-input"></td>
    <td>
        <span>
            <?php echo $_SESSION['errors']['studentFirstName'] ?? null; ?>
        </span>
    </td>
</tr>

<tr>
    <td>Middle Name:</td>
    <td><input type="text" name="studentMiddleName" id="studentMiddleName" value="<?= $_SESSION['input']['studentMiddleName'] ?? null; ?>" class="data-input"></td>
    <td>
        <span>
            <?php echo $_SESSION['errors']['studentMiddleName'] ?? null; ?>
        </span>
    </td>
</tr>

<tr>
    <td>Last Name:</td>
    <td><input type="text" name="studentLastName" id="studentLastName" value="<?= $_SESSION['input']['studentLastName'] ?? null; ?>" class="data-input"></td>
    <span>
        <?php echo $_SESSION['errors']['studentLastName'] ?? null; ?>
    </span>
</tr>
<tr>
    <td>Year:</td>
    <td><input type="text" name="studentYear" id="studentYear" value="<?= $_SESSION['input']['studentYear'] ?? null; ?>" class="data-input"></td>
    <td>
        <span>
            <?php echo $_SESSION['errors']['studentYear'] ?? null; ?>
        </span>
    </td>
</tr>
<tr>
    <td colspan="2">
        <button type="submit" name="saveNewStudentEntry" class="btn btn-primary">
            Save Student Entry
        </button>
        <button type="submit" name="clearEntries" class="btn">
            Clear Entries
        </button>
        <a href="index.php?section=students&page=studentsList&progid=<?= $programData['progid']; ?>" class="btn btn-danger">
            Exit
        </a>
    </td>
</tr>

</table>
</form>
