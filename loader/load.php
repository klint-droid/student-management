<?php

$page = 'home/home';

if (isset($_GET['section'])) {
    $section = $_GET['section'];
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

$allowed_pages = [
    'schoolList',
    'departmentList',
    'programList',
    'studentList',
    'schoolCreate',
    'schoolUpdate',
    'schoolDelete',
    'processSchoolData',
    'processDataChanges',
    'chooseSchool',
    'processSchoolChoice',
    '500',
    '404',
    'home.php',

    'departmentCreate',
    'departmentUpdate',
    'departmentDelete',
    'processDepartmentChanges',
    'processDepartmentData',
    'processDataDeletion',
    'processDepartmentDeletion',

    'choosePrograms',
    'processProgramChoice',
    'programsList',
    'programsCreate',
    'processProgramData',
    'programsDelete',
    'programsDataDeletion',
    'programsUpdate',
    'processProgramChanges',

    'chooseStudent',
    'processStudentChoice',
    'studentsList',
    'studentCreate',
    'processStudentData',
    'studentDelete',
    'studentUpdate',
    'studentDataDeletion',
    'studentDataChanges',
];

if (in_array($page, $allowed_pages)) {
    if(file_exists("{$section}/{$page}.php")) {
        $file = "{$section}/{$page}.php";
    } else {
        $file = "404/404.php";
    }
} else {
    $file = "home/home.php";
}


require_once $file;
