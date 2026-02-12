<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>USJR School Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #e6ac2f;
        }

        .header {
            background: #139e06;
            padding: 15px;
            color: white;
            font-size: 26px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .header img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            transition: 0.3s ease;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            text-decoration: none;
            color: inherit;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.2);
        }

        .icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .card h2 {
            margin: 10px 0;
            color: #2a5298;
        }

        .card p {
            font-size: 14px;
            color: #555;
            line-height: 1.5;
        }

        footer {
            text-align: center;
            padding: 15px;
            color: #777;
            font-size: 13px;
        }
    </style>
</head>
<body>

<div class="header">
    <img src="../favicon.ico" alt="USJR Logo">
    USJR Student Management System Dashboard
</div>

<div class="container">

    <a href="../index.php?section=school&page=schoolList" class="card">
        <div class="icon">🏫</div>
        <h2>School</h2>
        <p>
            Add and manage schools. Assign departments under schools
            and organize faculty structure.
        </p>
    </a>

    <a href="../index.php?section=department&page=chooseSchool" class="card">
        <div class="icon">🏢</div>
        <h2>Department</h2>
        <p>
            Create and manage academic departments. Assign programs under departments 
            and organize faculty structure.
        </p>
    </a>

    <a href="../index.php?section=programs&page=choosePrograms" class="card">
        <div class="icon">📘</div>
        <h2>Program</h2>
        <p>
            Manage academic programs or courses offered by each department, 
            including program codes and descriptions.
        </p>
    </a>

    <a href="../index.php?section=students&page=chooseStudent" class="card">
        <div class="icon">👩‍🎓</div>
        <h2>Students</h2>
        <p>
            Register and manage student records, personal details, program enrollment, 
            and academic information.
        </p>
    </a>

</div>

<footer>
    &copy; <?php echo date("Y"); ?> Student Management System | All Rights Reserved
</footer>

</body>
</html>
