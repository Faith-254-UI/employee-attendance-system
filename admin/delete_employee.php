<?php
session_start();

if (!isset($_SESSION["employee_id"])) {
    header("Location: ../login.php");
    exit();
}

require_once "../includes/connection.php";

if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: view_employees.php");
    exit();
}

$employee_id = (int) $_GET["id"];

/*
 * Delete attendance records first because attendance.employee_id
 * is linked to employees.employee_id by a foreign key.
 */
$deleteAttendance = mysqli_prepare(
    $conn,
    "DELETE FROM attendance WHERE employee_id = ?"
);

mysqli_stmt_bind_param($deleteAttendance, "i", $employee_id);
mysqli_stmt_execute($deleteAttendance);
mysqli_stmt_close($deleteAttendance);

/* Delete the employee */
$deleteEmployee = mysqli_prepare(
    $conn,
    "DELETE FROM employees WHERE employee_id = ?"
);

mysqli_stmt_bind_param($deleteEmployee, "i", $employee_id);
mysqli_stmt_execute($deleteEmployee);
mysqli_stmt_close($deleteEmployee);

header("Location: view_employees.php");
exit();
?>