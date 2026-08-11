<?php
session_start();

if (!isset($_SESSION["employee_id"])) {
    header("Location: ../login.php");
    exit();
}

require_once "../includes/connection.php";

/* Check whether an employee ID was provided */
if (!isset($_GET["id"])) {
    header("Location: view_employees.php");
    exit();
}

$employee_id = intval($_GET["id"]);

/* Get employee information */
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
    WHERE employee_id = $employee_id
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error loading employee: " . mysqli_error($conn));
}

/* Check whether employee exists */
if (mysqli_num_rows($result) == 0) {
    die("Employee not found.");
}

$employee = mysqli_fetch_assoc($result);

$error = "";
$success = "";


/* Update employee */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $department = trim($_POST["department"]);
    $username = trim($_POST["username"]);

    if (
        $first_name == "" ||
        $last_name == "" ||
        $email == "" ||
        $phone == "" ||
        $department == "" ||
        $username == ""
    ) {

        $error = "Please fill in all fields.";

    } else {

        $update_query = "
            UPDATE employees
            SET
                first_name = ?,
                last_name = ?,
                email = ?,
                phone = ?,
                department = ?,
                username = ?
            WHERE employee_id = ?
        ";

        $stmt = mysqli_prepare($conn, $update_query);

        mysqli_stmt_bind_param(
            $stmt,
            "ssssssi",
            $first_name,
            $last_name,
            $email,
            $phone,
            $department,
            $username,
            $employee_id
        );

        if (mysqli_stmt_execute($stmt)) {

            header("Location: view_employees.php");
            exit();

        } else {

            $error = "Failed to update employee.";

        }

        mysqli_stmt_close($stmt);
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

    <title>Edit Employee</title>

    <link rel="stylesheet" href="../css/style.css?v=2">

</head>

<body>

    <?php include "../includes/sidebar.php"; ?>

    <div class="main-content">

        <h1>Edit Employee</h1>

        <p>
            Welcome,
            <?php
            echo htmlspecialchars(
                $_SESSION["first_name"] . " " . $_SESSION["last_name"]
            );
            ?>
        </p>

        <hr>

        <h2>Edit Employee Information</h2>

        <?php if ($error != ""): ?>

            <p style="color: red;">
                <?php echo htmlspecialchars($error); ?>
            </p>

        <?php endif; ?>

        <form method="POST">

            <p>
                <label for="first_name">First Name:</label><br>

                <input
                    type="text"
                    id="first_name"
                    name="first_name"
                    value="<?php echo htmlspecialchars($employee["first_name"]); ?>"
                    required
                >
            </p>

            <p>
                <label for="last_name">Last Name:</label><br>

                <input
                    type="text"
                    id="last_name"
                    name="last_name"
                    value="<?php echo htmlspecialchars($employee["last_name"]); ?>"
                    required
                >
            </p>

            <p>
                <label for="email">Email:</label><br>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?php echo htmlspecialchars($employee["email"]); ?>"
                    required
                >
            </p>

            <p>
                <label for="phone">Phone:</label><br>

                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="<?php echo htmlspecialchars($employee["phone"]); ?>"
                    required
                >
            </p>

            <p>
                <label for="department">Department:</label><br>

                <input
                    type="text"
                    id="department"
                    name="department"
                    value="<?php echo htmlspecialchars($employee["department"]); ?>"
                    required
                >
            </p>

            <p>
                <label for="username">Username:</label><br>

                <input
                    type="text"
                    id="username"
                    name="username"
                    value="<?php echo htmlspecialchars($employee["username"]); ?>"
                    required
                >
            </p>

            <button type="submit">
                Save Changes
            </button>

            <a href="view_employees.php">
                Cancel
            </a>

        </form>

    </div>

</body>

</html>