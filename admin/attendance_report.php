<?php
session_start();

if (!isset($_SESSION["employee_id"])) {
    header("Location: ../login.php");
    exit();
}

require_once "../includes/connection.php";

/* Use Kenya time */
date_default_timezone_set("Africa/Nairobi");


/* Use today's date by default */
$selected_date = date("Y-m-d");


/* If the user selects a date, use that date */
if (isset($_GET["date"]) && $_GET["date"] !== "") {
    $selected_date = $_GET["date"];
}


/*
 * Get ALL employees and match their
 * attendance record for the selected date.
 */
$query = "
    SELECT
        employees.employee_id,
        employees.first_name,
        employees.last_name,
        employees.department,
        attendance.attendance_date,
        attendance.check_in,
        attendance.check_out
    FROM employees
    LEFT JOIN attendance
        ON employees.employee_id = attendance.employee_id
        AND attendance.attendance_date = ?
    ORDER BY employees.employee_id ASC
";


$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param(
    $stmt,
    "s",
    $selected_date
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);


if (!$result) {
    die("Error loading attendance report.");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Attendance Report</title>

    <link rel="stylesheet" href="../css/style.css?v=2">
</head>

<body>

    <?php include "../includes/sidebar.php"; ?>

    <div class="main-content">

        <h1>Attendance Report</h1>

        <p>
            Welcome,
            <?php
            echo htmlspecialchars(
                $_SESSION["first_name"] . " " . $_SESSION["last_name"]
            );
            ?>
        </p>

        <hr>

        <h2>Attendance History</h2>

        <form method="GET" action="attendance_report.php">

            <label for="date">Select Date:</label>

            <input
                type="date"
                id="date"
                name="date"
                value="<?php echo htmlspecialchars($selected_date); ?>"
                required
            >

            <button type="submit">
                View Report
            </button>

        </form>

        <br>

        <p>
            Showing attendance for:
            <strong><?php echo htmlspecialchars($selected_date); ?></strong>
        </p>

          <div class="table-container">

          <table class="data-table">
            <thead>

                <tr>
                    <th>Employee ID</th>
                    <th>Employee</th>
                    <th>Department</th>
                    <th>Date</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Status</th>
                </tr>

            </thead>

            <tbody>

                <?php if (mysqli_num_rows($result) > 0): ?>

                    <?php while ($row = mysqli_fetch_assoc($result)): ?>

                        <tr>

                            <td>
                                <?php echo $row["employee_id"]; ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $row["first_name"] . " " . $row["last_name"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row["department"]); ?>
                            </td>

                            <td>
                                <?php echo $row["attendance_date"]; ?>
                            </td>

                            <td>
                                <?php echo $row["check_in"] ?? "-"; ?>
                            </td>

                            <td>
                                <?php echo $row["check_out"] ?? "-"; ?>
                            </td>

                            <td>
                                <?php
                                echo !empty($row["check_in"])
                                    ? "Present"
                                    : "Absent";
                                ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="7">
                            No attendance records found for this date.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>
    </div>
</div>

</body>

</html>