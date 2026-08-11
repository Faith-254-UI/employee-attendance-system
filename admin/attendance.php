<?php
session_start();

date_default_timezone_set("Africa/Nairobi");

if (!isset($_SESSION["employee_id"])) {
    header("Location: ../login.php");
    exit();
}

require_once "../includes/connection.php";

/* Today's date */
$today = date("Y-m-d");

/*
 * Get all employees and their attendance for today.
 */
$query = "
    SELECT
        e.employee_id,
        e.first_name,
        e.last_name,
        e.department,
        a.attendance_id,
        a.check_in,
        a.check_out,
        a.status
    FROM employees e
    LEFT JOIN attendance a
        ON e.employee_id = a.employee_id
        AND a.attendance_date = ?
    ORDER BY e.employee_id ASC
";

$stmt = mysqli_prepare($conn, $query);

mysqli_stmt_bind_param(
    $stmt,
    "s",
    $today
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Attendance</title>

    <link rel="stylesheet" href="../css/style.css?v=2">

</head>

<body>

    <?php include "../includes/sidebar.php"; ?>

    <div class="main-content">

        <h1>Attendance</h1>

        <p>
            Date:
            <strong><?php echo htmlspecialchars($today); ?></strong>
        </p>

        <hr>

        <h2>Today's Attendance</h2>

        <div class="table-container">

            <table class="data-table">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody>

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
                                <?php
                                echo htmlspecialchars(
                                    $row["department"]
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo $row["check_in"] ?? "-";
                                ?>
                            </td>

                            <td>
                                <?php
                                echo $row["check_out"] ?? "-";
                                ?>
                            </td>

                            <td>

                                <?php
                                if ($row["attendance_id"] === null) {
                                    echo "Absent";
                                } else {
                                    echo htmlspecialchars(
                                        $row["status"] ?? "Present"
                                    );
                                }
                                ?>

                            </td>

                            <td>

                                <?php if ($row["attendance_id"] === null): ?>

                                    <a
                                        href="check_in.php?id=<?php echo $row["employee_id"]; ?>"
                                        class="checkin-btn"
                                    >
                                        Check In
                                    </a>

                                <?php elseif ($row["check_out"] === null): ?>

                                    <a
                                        href="check_out.php?id=<?php echo $row["employee_id"]; ?>"
                                        class="checkout-btn"
                                    >
                                        Check Out
                                    </a>

                                <?php else: ?>

                                    <span class="completed-status">
                                        Completed
                                    </span>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</body>

</html>

<?php
mysqli_stmt_close($stmt);
?>