<?php
require_once("data/db.php");
session_start();

$progid = filter_input(INPUT_GET, 'progid', FILTER_VALIDATE_INT);

if (!$progid) {
    die("Program not specified.");
}

$limit = 5;

$dbStatement = $db->prepare("
    SELECT * 
    FROM programs 
    WHERE progid = :progid
");
$dbStatement->execute(['progid' => $progid]);
$program = $dbStatement->fetch(PDO::FETCH_ASSOC);

if (!$program) {
    die("Program not found.");
}

$dbStatement = $db->prepare("
    SELECT COUNT(*) 
    FROM students 
    WHERE studprogid = :progid
");
$dbStatement->execute(['progid' => $progid]);
$totalStudents = (int) $dbStatement->fetchColumn();


$totalPages = ($totalStudents > 0)
    ? ceil($totalStudents / $limit)
    : 1;


$currentPage = filter_input(INPUT_GET, 'pg', FILTER_VALIDATE_INT);
$currentPage = $currentPage ? $currentPage : 1;


$currentPage = max(1, min($currentPage, $totalPages));

$offset = ($currentPage - 1) * $limit;


$sql = "
    SELECT * 
    FROM students
    WHERE studprogid = :progid
    ORDER BY studid
    LIMIT $offset, $limit
";

$dbStatement = $db->prepare($sql);
$dbStatement->execute(['progid' => $progid]);

$students = $dbStatement->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Student List - <?= htmlspecialchars($program['progfullname']); ?></h1>

<div>
    <h2>
        <a href="index.php?section=students&page=studentCreate&progid=<?= $progid; ?>" 
           class="btn btn-primary">
           Create Student
        </a>
    </h2>
</div>

<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <th>Student ID</th>
        <th>Student Name</th>
        <th>Actions</th>
    </tr>

    <?php if (!empty($students)): ?>
        <?php foreach ($students as $student): ?>
            <tr>
                <td><?= htmlspecialchars($student['studid']); ?></td>
                <td>
                    <?= htmlspecialchars($student['studfirstname'] . ' ' . $student['studlastname']); ?>
                </td>
                <td>
                    <a href="index.php?section=students&page=studentUpdate&progid=<?= $progid ?>&studid=<?= $student['studid']; ?>" 
                       class="btn btn-info">Edit</a>

                    <a href="index.php?section=students&page=studentDelete&progid=<?= $progid ?>&studid=<?= $student['studid']; ?>" 
                       class="btn btn-danger">
                       Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="2" style="text-align:center;">No students found.</td>
        </tr>
    <?php endif; ?>

    <tr>
        <td colspan="2">
            Total of: <?= $totalStudents ?>
            <?= ($totalStudents === 1) ? 'student' : 'students'; ?>
        </td>

        <td colspan="2">
            <?php if ($totalPages > 1): ?>

                <?php if ($currentPage > 1): ?>
                    <a href="index.php?section=students&page=studentsList&progid=<?= $progid ?>&pg=<?= $currentPage - 1 ?>" 
                       class="btn btn-primary">Previous</a>
                <?php else: ?>
                    <span>Previous</span>
                <?php endif; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="index.php?section=students&page=studentsList&progid=<?= $progid ?>&pg=<?= $currentPage + 1 ?>" 
                       class="btn btn-primary">Next</a>
                <?php else: ?>
                    <span>Next</span>
                <?php endif; ?>

            <?php endif; ?>
        </td>
    </tr>
</table>
