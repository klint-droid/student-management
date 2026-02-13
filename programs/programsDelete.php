<?php
require_once('data/db.php');
session_start();

if (!isset($_GET['progid']) || !filter_var($_GET['progid'], FILTER_VALIDATE_INT)) {
    header("Location: index.php?section=programs&page=programsList");
    exit();
}

$programID = $_GET['progid'];

$dbStatement = $db->prepare("SELECT * FROM programs WHERE progid = :programID");
$dbStatement->execute(['programID' => $programID]);
$program = $dbStatement->fetch(PDO::FETCH_ASSOC);

if (!$program) {
    $_SESSION['messages']['deleteError'] = "Program not found.";
    header("Location: index.php?section=programs&page=programsList");
    exit();
}
?>

<h1>Program Delete</h1>

<span style="color:green;">
    <?= $_SESSION['messages']['deleteSuccess'] ?? ''; ?>
</span>
<span style="color:red;">
    <?= $_SESSION['messages']['deleteError'] ?? ''; ?>
</span>

<?php
unset($_SESSION['messages']);
?>

<form action="index.php?section=programs&page=programsDataDeletion" method="post">

    <input type="hidden" name="programID" value="<?= htmlspecialchars($program['progid']); ?>">

    <table>
        <tr>
            <td style="width: 10em;">Program ID:</td>
            <td><?= htmlspecialchars($program['progid']); ?></td>
        </tr>
        <tr>
            <td>Program Full Name:</td>
            <td><?= htmlspecialchars($program['progfullname']); ?></td>
        </tr>
        <tr>
            <td>Program Short Name:</td>
            <td><?= htmlspecialchars($program['progshortname']); ?></td>
        </tr>
        <tr>
            <td colspan="2">
                <a href="index.php?section=programs&page=programsList&deptid=<?= $program['progcolldeptid']; ?>" class="btn btn-primary">
                    Cancel Operation
                </a>

                <button type="submit" name="confirmDelete"
                        class="btn btn-danger">
                    Confirm Operation
                </button>
            </td>
        </tr>
    </table>
</form>
