<?php
require_once('data/db.php');
session_start();

$departmentID = filter_input(INPUT_GET, 'deptid', FILTER_VALIDATE_INT);

if (!$departmentID) {
    die("Invalid department ID.");
}


$dbStatement = $db->prepare("
    SELECT deptid, deptfullname, deptshortname, deptcollid
    FROM departments
    WHERE deptid = :deptid
");
$dbStatement->execute(['deptid' => $departmentID]);
$department = $dbStatement->fetch(PDO::FETCH_ASSOC);

if (!$department) {
    die("Department not found.");
}

$successMsg = $_SESSION['messages']['deleteSuccess'] ?? '';
$errorMsg   = $_SESSION['messages']['deleteError'] ?? '';

unset($_SESSION['messages']);
?>

<h1>Delete Department</h1>

<?php if ($successMsg): ?>
    <p style="color:green;"><?= htmlspecialchars($successMsg) ?></p>
<?php endif; ?>

<?php if ($errorMsg): ?>
    <p style="color:red;"><?= htmlspecialchars($errorMsg) ?></p>
<?php endif; ?>

<form action="index.php?section=department&page=processDepartmentDeletion" method="post">
    <input type="hidden" name="departmentID" value="<?= $department['deptid']; ?>">

    <table>
        <tr>
            <td>Department ID:</td>
            <td><?= htmlspecialchars($department['deptid']); ?></td>
        </tr>
        <tr>
            <td>Depertment Full Name:</td>
            <td><?= htmlspecialchars($department['deptfullname']); ?></td>
        </tr>
        <tr>
            <td>Department Short Name:</td>
            <td><?= htmlspecialchars($department['deptshortname']); ?></td>
        </tr>
        <tr>
            <td colspan="2">
                <a href="index.php?section=department&page=departmentList&deptcollid=<?= $department['deptcollid']; ?>" 
                   class="btn btn-primary">
                   Cancel
                </a>

                <button type="submit" name="confirmDelete"
                        class="btn btn-danger"
                        onclick="return confirm('Are you sure you want to delete this department?')">
                        Confirm Delete
                </button>
            </td>
        </tr>
    </table>
</form>
