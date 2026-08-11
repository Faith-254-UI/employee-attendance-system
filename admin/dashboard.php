<?php
session_start();

if (!isset($_SESSION["employee_id"])) {
    header("Location: ../login.php");
    exit();
}

require_once "../includes/connection.php";

/* Use Kenya time */
date_default_timezone_set("Africa/Nairobi");

$today = date("Y-m-d");


/* ==============================
   TOTAL EMPLOYEES
   ============================== */

$totalEmployeesQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM employees"
);

if (!$totalEmployeesQuery) {
    die("Error counting employees: " . mysqli_error($conn));
}

$totalEmployeesData = mysqli_fetch_assoc($totalEmployeesQuery);

$totalEmployees = (int) $totalEmployeesData["total"];


/* ==============================
   PRESENT TODAY
   ============================== */

$presentTodayQuery = mysqli_prepare(
    $conn,
    "SELECT COUNT(DISTINCT employee_id) AS total
     FROM attendance
     WHERE attendance_date = ?
     AND status = 'Present'"
);

mysqli_stmt_bind_param(
    $presentTodayQuery,
    "s",
    $today
);

mysqli_stmt_execute($presentTodayQuery);

$presentTodayResult = mysqli_stmt_get_result(
    $presentTodayQuery
);

$presentTodayData = mysqli_fetch_assoc(
    $presentTodayResult
);

$presentToday = (int) $presentTodayData["total"];

mysqli_stmt_close($presentTodayQuery);


/* ==============================
   ABSENT TODAY
   ============================== */

$absentToday = $totalEmployees - $presentToday;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <?php include "../includes/sidebar.php"; ?>

    <div class="main-content">

        <h1>Admin Dashboard</h1>

        <p>
            Welcome,
            <?php echo $_SESSION["first_name"] . " " . $_SESSION["last_name"]; ?>
        </p>

        <hr>

        <h2>Dashboard Summary</h2>

<div class="dashboard-cards">

    <div class="dashboard-card">
        <h3>Total Employees</h3>
        <p class="card-number">
            <?php echo $totalEmployees; ?>
        </p>
    </div>

    <div class="dashboard-card">
        <h3>Present Today</h3>
        <p class="card-number">
            <?php echo $presentToday; ?>
        </p>
    </div>

    <div class="dashboard-card">
        <h3>Absent Today</h3>
        <p class="card-number">
            <?php echo $absentToday; ?>
        </p>
    </div>

</div>

    </div>

</body>

</html>