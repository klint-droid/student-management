<?php
require_once("data/db.php");
session_start();

$studId = filter_input(INPUT_GET, 'studid', FILTER_VALIDATE_INT);

if (!$studId) {
    header("Location: index.php?section=students&page=studentList");
    exit();
}

$dbStatement = $db->prepare("SELECT * FROM students WHERE studid = :studId");
$dbStatement->execute(['studId' => $studId]);
$student = $dbStatement->fetch(PDO::FETCH_ASSOC);

if (!$student) {
    header("Location: index.php?section=students&page=studentList");
    exit();
}

$successMsg = $_SESSION['messages']['deleteSuccess'] ?? '';
$errorMsg   = $_SESSION['messages']['deleteError'] ?? '';
unset($_SESSION['messages']);
?>

<h1>Delete Student</h1>

<?php if ($successMsg): ?>
    <p style="color:green;"><?= htmlspecialchars($successMsg) ?></p>
<?php endif; ?>

<?php if ($errorMsg): ?>
    <p style="color:red;"><?= htmlspecialchars($errorMsg) ?></p>
<?php endif; ?>

<form action="index.php?section=students&page=studentDataDeletion" method="post">

    <input type="hidden" name="studId" value="<?= $student['studid']; ?>">
    <input type="hidden" name="progId" value="<?= $student['studprogid']; ?>">

    <table>
        <tr>
            <td>Student ID:</td>
            <td><?= htmlspecialchars($student['studid']); ?></td>
        </tr>
        <tr>
            <td>First Name:</td>
            <td><?= htmlspecialchars($student['studfirstname']); ?></td>
        </tr>
        <tr>
            <td>Middle Name:</td>
            <td><?= htmlspecialchars($student['studmidname']); ?></td>
        </tr>
        <tr>
            <td>Last Name:</td>
            <td><?= htmlspecialchars($student['studlastname']); ?></td>
        </tr>
        <tr>
            <td>Program ID:</td>
            <td><?= htmlspecialchars($student['studprogid']); ?></td>
        </tr>
        <tr>
            <td colspan="2">
                <a href="index.php?section=students&page=studentList&progid=<?= $student['studprogid']; ?>"
                   class="btn btn-primary">
                   Cancel
                </a>

                <button type="submit" name="confirmDelete"
                        class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this student?')">
                        Confirm Delete
                </button>
            </td>
        </tr>
    </table>
</form>
