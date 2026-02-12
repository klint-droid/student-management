<?php
    require_once("data/db.php");
    session_start();
    session_destroy();
    
    if(!isset($_GET['deptid']) || empty($_GET['deptid'])) {
        die("Department not specified.");
    }

    $deptid = intval($_GET['deptid']);

    $limit = 5;

    $dbStatement = $db->prepare("SELECT * FROM departments  WHERE deptid = :deptid");
    $dbStatement->execute(['deptid' => $_GET['deptid']]);
    $department = $dbStatement->fetch();

    if(!$department) {
        die("Department not found.");
    }
    
    $dbStatement = $db->prepare("SELECT * from departments d JOIN programs p WHERE d.deptid = p.progcolldeptid AND deptid = :deptid");
    $dbStatement->execute(['deptid' => $_GET['deptid']]);
    $totalPrograms = $dbStatement->fetchColumn();

    $totalPages = ceil($totalPrograms/ $limit);

    if(!isset($_GET['pgSection']) || !is_numeric($_GET['pgSection'])){
        $currentPage = 1;
    } else {
        $currentPage = intval($_GET['pgSection']);
    }

    if($currentPage < 1){
        $currentPage = 1;
    }

    if($currentPage > $totalPages && $totalPages > 0){
        $currentPage = $totalPages;
    }

    $offset = ($currentPage - 1) * $limit; 
    $dbStatement = $db->prepare("SELECT * FROM departments d JOIN programs p WHERE d.deptid = p.progcolldeptid AND deptid = :deptid ORDER BY progid LIMIT :offset, :limit;");
    $dbStatement->bindParam('deptid', $_GET['deptid'], PDO::PARAM_INT);
    $dbStatement->bindParam('offset', $offset, PDO::PARAM_INT);
    $dbStatement->bindParam('limit', $limit, PDO::PARAM_INT);
    $dbStatement->execute();
    $programs = $dbStatement->fetchAll();
?>

<h1>Program List - <?php echo $department['deptfullname']; ?></h1>
<div>
    <h2><a href="index.php?section=programs&page=programsCreate&deptid=<?php echo $_GET['deptid']; ?>" class="btn btn-primary">Create Program</a></h2>
</div>
<table border="1" cellpadding="8" cellspacing="0" width="100%">
    <tr>
        <th>Program ID</th>
        <th>Program Full Name</th>
        <th>Program Short Name</th>
    </tr>
    <?php if (count($programs) > 0):?>
        <?php foreach ($programs as $program): ?>
            <tr>
                <td><?php echo $program['progid']; ?></td>
                <td><?php echo $program['progfullname']; ?></td>
                <td><?php echo $program['progshortname']; ?></td>
                <td>
                
                    <a href="index.php?section=programs&page=programsUpdate&deptid=<?php echo $program['deptid']; ?>&progid=<?php echo $program['progid']; ?>" class="btn btn-info">Update</a>
                    <a href="index.php?section=programs&page=programsDelete&progid=<?php echo $program['progid']; ?>" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else:?>
        <tr>
            <td colspan="4" style="text-align:center;">No programs found.</td>
        </tr>
    <?php endif; ?>
            <tr>
        <td colspan="2">
            <span>
                Total of: <?= $totalPrograms ?> 
                <?= ($totalPrograms == 1) ? 'programs' : 'programs' ?> in the database
            </span>
        </td>
        <td colspan="2">
          <?php if($totalPages > 1): ?>  
            <?php if ($currentPage > 1): ?>
                <a href="index.php?section=program&page=programsList&deptcollid=<?= $department['collid'] ?>&pgSection=<?= $currentPage - 1 ?>" class="btn btn-primary">Previous</a>
            <?php else: ?>
                <span>Previous</span>
            <?php endif; ?>
            <?php if ($currentPage < $totalPages): ?>
                <a href="index.php?section=program&page=programsList&deptcollid=<?= $department['collid'] ?>&pgSection=<?= $currentPage + 1 ?>" class="btn btn-primary">Next</a>
            <?php else: ?>
                <span>Next</span>
            <?php endif; ?>
          <?php endif; ?>  
        </td>
    </tr>
</table>