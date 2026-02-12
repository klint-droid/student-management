<?php
require_once("data/db.php");
session_start();
session_destroy();

if (!isset($_GET['deptcollid']) || !is_numeric($_GET['deptcollid'])) {
    die("Invalid College ID.");
}

$deptcollid = intval($_GET['deptcollid']);

$limit = 5;

$dbStatement = $db->prepare("SELECT * FROM colleges WHERE collid = :collid");
$dbStatement->execute(['collid' => $deptcollid]);
$school = $dbStatement->fetch();

if (!$school) {
    die("College not found.");
}

$countStmt = $db->prepare("SELECT COUNT(*) FROM departments WHERE deptcollid = :deptcollid");
$countStmt->execute(['deptcollid' => $deptcollid]);
$totalDepartments = $countStmt->fetchColumn();

$totalPages = ceil($totalDepartments / $limit);


if (!isset($_GET['pgSection']) || !is_numeric($_GET['pgSection'])) {
    $currentPage = 1;
} else {
    $currentPage = intval($_GET['pgSection']);
}

if ($currentPage < 1) {
    $currentPage = 1;
}

if ($currentPage > $totalPages && $totalPages > 0) {
    $currentPage = $totalPages;
}

$offset = ($currentPage - 1) * $limit;
$dbStatement = $db->prepare("
    SELECT * FROM departments
    WHERE deptcollid = :deptcollid
    LIMIT :limit OFFSET :offset
");

$dbStatement->bindValue(':deptcollid', $deptcollid, PDO::PARAM_INT);
$dbStatement->bindValue(':limit', $limit, PDO::PARAM_INT);
$dbStatement->bindValue(':offset', $offset, PDO::PARAM_INT);

$dbStatement->execute();
$departments = $dbStatement->fetchAll();
?>

<h1>Department List - <?php echo htmlspecialchars($school['collfullname']); ?></h1>

<div>
    <h2>
        <a href="index.php?section=department&page=departmentCreate&collid=<?= $school['collid']; ?>" class="btn btn-primary">
            Create Department
        </a>
    </h2>
</div>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <th>Department ID</th>
        <th>Department Full Name</th>
        <th>Department Short Name</th>
        <th>Actions</th>
    </tr>

    <?php if (count($departments) > 0): ?>
        <?php foreach ($departments as $department): ?>
            <tr>
                <td><?= htmlspecialchars($department['deptid']); ?></td>
                <td><?= htmlspecialchars($department['deptfullname']); ?></td>
                <td><?= htmlspecialchars($department['deptshortname']); ?></td>
                <td>
                    <a href="index.php?section=department&page=departmentUpdate&deptid=<?= $department['deptid']; ?>" class="btn btn-info">
                        Update
                    </a>
                    <a href="index.php?section=department&page=departmentDelete&deptid=<?= $department['deptid']; ?>" class="btn btn-danger">
                        Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4" style="text-align:center;">No departments found.</td>
        </tr>
    <?php endif; ?>

    <tr>
        <td colspan="2">
            <span>
                Total of: <?= $totalDepartments ?> 
                <?= ($totalDepartments == 1) ? 'department' : 'departments' ?> in the database
            </span>
        </td>
        <td colspan="2">
          <?php if($totalPages > 1): ?>  
            <?php if ($currentPage > 1): ?>
                <a href="index.php?section=department&page=departmentList&deptcollid=<?= $school['collid'] ?>&pgSection=<?= $currentPage - 1 ?>" class="btn btn-primary">Previous</a>
            <?php else: ?>
                <span>Previous</span>
            <?php endif; ?>
            <?php if ($currentPage < $totalPages): ?>
                <a href="index.php?section=department&page=departmentList&deptcollid=<?= $school['collid'] ?>&pgSection=<?= $currentPage + 1 ?>" class="btn btn-primary">Next</a>
            <?php else: ?>
                <span>Next</span>
            <?php endif; ?>
          <?php endif; ?>  
        </td>
    </tr>
</table>

