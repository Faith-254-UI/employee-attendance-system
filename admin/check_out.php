<?php
session_start();

/* Use Kenya time */
date_default_timezone_set("Africa/Nairobi");

if (!isset($_SESSION["employee_id"])) {
    header("Location: ../login.php");
    exit();
}

require_once "../includes/connection.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: attendance.php");
    exit();
}

$employee_id = (int) $_GET["id"];

$today = date("Y-m-d");
$current_time = date("H:i:s");


/* Find today's attendance record */
$check = mysqli_prepare(
    $conn,
    "SELECT attendance_id
     FROM attendance
     WHERE employee_id = ?
     AND attendance_date = ?
     AND check_out IS NULL"
);

mysqli_stmt_bind_param(
    $check,
    "is",
    $employee_id,
    $today
);

mysqli_stmt_execute($check);

mysqli_stmt_store_result($check);


if (mysqli_stmt_num_rows($check) > 0) {

    mysqli_stmt_bind_result(
        $check,
        $attendance_id
    );

    mysqli_stmt_fetch($check);


    /* Record the check-out time */
    $update = mysqli_prepare(
        $conn,
        "UPDATE attendance
         SET check_out = ?
         WHERE attendance_id = ?"
    );

    mysqli_stmt_bind_param(
        $update,
        "si",
        $current_time,
        $attendance_id
    );

    mysqli_stmt_execute($update);

    mysqli_stmt_close($update);
}


mysqli_stmt_close($check);

header("Location: attendance.php");
exit();
?>