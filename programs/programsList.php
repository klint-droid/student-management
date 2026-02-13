<?php
require_once("data/db.php");
session_start();

if (!isset($_GET['deptid']) || !is_numeric($_GET['deptid'])) {
    die("Department not specified.");
}

$deptid = intval($_GET['deptid']);
$limit = 5;

$dbStatement = $db->prepare("
    SELECT * 
    FROM departments 
    WHERE deptid = :deptid
");
$dbStatement->execute(['deptid' => $deptid]);
$department = $dbStatement->fetch(PDO::FETCH_ASSOC);

if (!$department) {
    die("Department not found.");
}

$dbStatement = $db->prepare("
    SELECT COUNT(*) 
    FROM programs 
    WHERE progcolldeptid = :deptid
");
$dbStatement->execute(['deptid' => $deptid]);
$totalPrograms = $dbStatement->fetchColumn();

$totalPages = ($totalPrograms > 0) ? ceil($totalPrograms / $limit) : 1;

$currentPage = (isset($_GET['pgSection']) && is_numeric($_GET['pgSection']))
    ? intval($_GET['pgSection'])
    : 1;

if ($currentPage < 1) {
    $currentPage = 1;
}

if ($currentPage > $totalPages) {
    $currentPage = $totalPages;
}

$offset = ($currentPage - 1) * $limit;

$dbStatement = $db->prepare("
    SELECT * 
    FROM programs 
    WHERE progcolldeptid = :deptid
    ORDER BY progid
    LIMIT :offset, :limit
");

$dbStatement->bindValue(':deptid', $deptid, PDO::PARAM_INT);
$dbStatement->bindValue(':offset', $offset, PDO::PARAM_INT);
$dbStatement->bindValue(':limit', $limit, PDO::PARAM_INT);
$dbStatement->execute();

$programs = $dbStatement->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Program List - <?= htmlspecialchars($department['deptfullname']); ?></h1>

<div>
    <h2>
        <a href="index.php?section=programs&page=programsCreate&deptid=<?= $deptid; ?>" 
           class="btn btn-primary">
           Create Program
        </a>
    </h2>
</div>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <th>Program ID</th>
        <th>Program Full Name</th>
        <th>Program Short Name</th>
        <th>Actions</th>
    </tr>

    <?php if (count($programs) > 0): ?>
        <?php foreach ($programs as $program): ?>
            <tr>
                <td><?= htmlspecialchars($program['progid']); ?></td>
                <td><?= htmlspecialchars($program['progfullname']); ?></td>
                <td><?= htmlspecialchars($program['progshortname']); ?></td>
                <td>
                    <a href="index.php?section=programs&page=programsUpdate&deptid=<?= $deptid; ?>&progid=<?= $program['progid']; ?>" 
                       class="btn btn-info">Update</a>

                    <a href="index.php?section=programs&page=programsDelete&deptid=<?= $deptid; ?>&progid=<?= $program['progid']; ?>" 
                       class="btn btn-danger">
                       Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="4" style="text-align:center;">No programs found.</td>
        </tr>
    <?php endif; ?>

    <tr>
        <td colspan="2">
            <span>
                Total of: <?= $totalPrograms ?>
                <?= ($totalPrograms == 1) ? 'program' : 'programs'; ?>
            </span>
        </td>

        <td colspan="2">
            <?php if ($totalPages > 1): ?>

                <?php if ($currentPage > 1): ?>
                    <a href="index.php?section=programs&page=programsList&deptid=<?= $deptid ?>&pgSection=<?= $currentPage - 1 ?>" 
                       class="btn btn-primary">Previous</a>
                <?php else: ?>
                    <span>Previous</span>
                <?php endif; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="index.php?section=programs&page=programsList&deptid=<?= $deptid ?>&pgSection=<?= $currentPage + 1 ?>" 
                       class="btn btn-primary">Next</a>
                <?php else: ?>
                    <span>Next</span>
                <?php endif; ?>

            <?php endif; ?>
        </td>
    </tr>
</table>
