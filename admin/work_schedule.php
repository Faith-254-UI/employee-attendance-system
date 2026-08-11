<?php
session_start();

if (!isset($_SESSION["employee_id"])) {
    header("Location: ../login.php");
    exit();
}

require_once "../includes/connection.php";

$message = "";
$error = "";


/* ==============================
   GET CURRENT WORK SCHEDULE
   ============================== */

$query = "
    SELECT *
    FROM work_schedule
    ORDER BY id ASC
    LIMIT 1
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error loading work schedule: " . mysqli_error($conn));
}

$schedule = mysqli_fetch_assoc($result);


/* ==============================
   UPDATE WORK SCHEDULE
   ============================== */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $check_in_time = trim($_POST["check_in_time"] ?? "");
    $check_out_time = trim($_POST["check_out_time"] ?? "");


    /* Validate empty fields */

    if ($check_in_time === "" || $check_out_time === "") {

        $error = "Please enter both check-in and check-out times.";

    }

    /* Check that checkout is later */

    elseif ($check_in_time >= $check_out_time) {

        $error = "Check-out time must be later than check-in time.";

    }

    else {

        /* Existing schedule */

        if ($schedule) {

            $stmt = mysqli_prepare(
                $conn,
                "UPDATE work_schedule
                 SET check_in_time = ?,
                     check_out_time = ?
                 WHERE id = ?"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "ssi",
                $check_in_time,
                $check_out_time,
                $schedule["id"]
            );

        }

        /* No schedule exists yet */

        else {

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO work_schedule
                (check_in_time, check_out_time)
                VALUES (?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "ss",
                $check_in_time,
                $check_out_time
            );
        }


        /* Execute update/insert */

        if (mysqli_stmt_execute($stmt)) {

            $message = "Work schedule updated successfully.";

        } else {

            $error = "Error updating work schedule: "
                   . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);


        /* Reload the schedule */

        $result = mysqli_query(
            $conn,
            "SELECT *
             FROM work_schedule
             ORDER BY id ASC
             LIMIT 1"
        );

        if ($result) {
            $schedule = mysqli_fetch_assoc($result);
        }
    }
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

    <title>Work Schedule</title>

    <link rel="stylesheet" href="../css/style.css?v=2">

</head>

<body>

    <?php include "../includes/sidebar.php"; ?>

    <div class="main-content">

        <h1>Work Schedule</h1>

        <p>
            Welcome,
            <?php
            echo htmlspecialchars(
                $_SESSION["first_name"] . " " . $_SESSION["last_name"]
            );
            ?>
        </p>

        <hr>


        <?php if ($message !== ""): ?>

            <div class="success-message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php endif; ?>


        <?php if ($error !== ""): ?>

            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>

        <?php endif; ?>


        <div class="schedule-container">

            <h2>Current Work Schedule</h2>


            <?php if ($schedule): ?>

                <div class="schedule-card">

                    <div class="schedule-item">

                        <span class="schedule-label">
                            Check-in Time
                        </span>

                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $schedule["check_in_time"]
                            );
                            ?>
                        </strong>

                    </div>


                    <div class="schedule-item">

                        <span class="schedule-label">
                            Check-out Time
                        </span>

                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $schedule["check_out_time"]
                            );
                            ?>
                        </strong>

                    </div>

                </div>

            <?php else: ?>

                <p>
                    No work schedule has been set.
                </p>

            <?php endif; ?>


            <hr>


            <h2>Update Work Schedule</h2>


            <form method="POST" action="">

                <div class="form-group">

                    <label for="check_in_time">
                        Check-in Time
                    </label>

                    <input
                        type="time"
                        id="check_in_time"
                        name="check_in_time"
                        value="<?php
                            echo $schedule
                                ? htmlspecialchars(
                                    $schedule["check_in_time"]
                                )
                                : "";
                        ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="check_out_time">
                        Check-out Time
                    </label>

                    <input
                        type="time"
                        id="check_out_time"
                        name="check_out_time"
                        value="<?php
                            echo $schedule
                                ? htmlspecialchars(
                                    $schedule["check_out_time"]
                                )
                                : "";
                        ?>"
                        required
                    >

                </div>


                <button type="submit">
                    Update Schedule
                </button>

            </form>

        </div>

    </div>

</body>

</html>