<?php
session_start();

if (!isset($_SESSION["employee_id"])) {
    header("Location: ../login.php");
    exit();
}

require_once "../includes/connection.php";

/* Get all employees */
$query = "
    SELECT
        employee_id,
        first_name,
        last_name,
        email,
        phone,
        department,
        username
    FROM employees
    ORDER BY employee_id ASC
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error loading employees: " . mysqli_error($conn));
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

    <title>View Employees</title>

<link rel="stylesheet" href="../css/style.css?v=2">
</head>

<body>

    <?php include "../includes/sidebar.php"; ?>

    <div class="main-content">

        <h1>Employees</h1>

        <p>
            Welcome,
            <?php
            echo htmlspecialchars(
                $_SESSION["first_name"] . " " . $_SESSION["last_name"]
            );
            ?>
        </p>

        <hr>

        <h2>Employee List</h2>

        <div class="table-container">

            <table class="data-table">

                <thead>

                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Department</th>
                        <th>Username</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody>

                    <?php if (mysqli_num_rows($result) > 0): ?>

                        <?php while ($employee = mysqli_fetch_assoc($result)): ?>

                            <tr>

                                <td>
                                    <?php echo $employee["employee_id"]; ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $employee["first_name"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $employee["last_name"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $employee["email"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $employee["phone"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $employee["department"]
                                    );
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $employee["username"]
                                    );
                                    ?>
                                </td>

                                <td>

    <a
        href="edit_employee.php?id=<?php echo $employee['employee_id']; ?>"
        class="edit-btn"
    >
        Edit
    </a>

    <a
        href="delete_employee.php?id=<?php echo $employee['employee_id']; ?>"
        class="delete-btn"
        onclick="return confirm('Are you sure you want to delete this employee?');"
    >
        Delete
    </a>

</td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="8">
                                No employees found.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</body>

</html>