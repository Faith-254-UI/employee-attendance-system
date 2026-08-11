<?php
session_start();

if (!isset($_SESSION["employee_id"])) {
    header("Location: ../login.php");
    exit();
}

require_once "../includes/connection.php";

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $department = trim($_POST["department"]);
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (
        $first_name == "" ||
        $last_name == "" ||
        $email == "" ||
        $phone == "" ||
        $department == "" ||
        $username == "" ||
        $password == ""
    ) {

        $error = "Please fill in all fields.";

    } else {

        /*
         * Check whether the username already exists.
         */
        $check_query = "
            SELECT employee_id
            FROM employees
            WHERE username = ?
        ";

        $check_stmt = mysqli_prepare($conn, $check_query);

        mysqli_stmt_bind_param(
            $check_stmt,
            "s",
            $username
        );

        mysqli_stmt_execute($check_stmt);

        $check_result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($check_result) > 0) {

            $error = "Username already exists.";

        } else {

            /*
             * Add the new employee.
             */
            $insert_query = "
                INSERT INTO employees
                (
                    first_name,
                    last_name,
                    email,
                    phone,
                    department,
                    username,
                    password
                )
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ";

            $stmt = mysqli_prepare($conn, $insert_query);

            mysqli_stmt_bind_param(
                $stmt,
                "sssssss",
                $first_name,
                $last_name,
                $email,
                $phone,
                $department,
                $username,
                $password
            );

            if (mysqli_stmt_execute($stmt)) {

                $message = "Employee added successfully.";

            } else {

                $error = "Failed to add employee.";

            }

            mysqli_stmt_close($stmt);
        }

        mysqli_stmt_close($check_stmt);
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

    <title>Add Employee</title>

    <link rel="stylesheet" href="../css/style.css?v=2">

</head>

<body>

    <?php include "../includes/sidebar.php"; ?>

    <div class="main-content">

        <h1>Add Employee</h1>

        <p>
            Welcome,
            <?php
            echo htmlspecialchars(
                $_SESSION["first_name"] . " " . $_SESSION["last_name"]
            );
            ?>
        </p>

        <hr>

        <?php if ($message != ""): ?>

            <div class="success-message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php endif; ?>

        <?php if ($error != ""): ?>

            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>

        <?php endif; ?>


        <div class="form-container">

            <h2>Employee Information</h2>

            <form method="POST" action="">

                <div class="form-row">

                    <div class="form-group">

                        <label for="first_name">
                            First Name
                        </label>

                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="last_name">
                            Last Name
                        </label>

                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            required
                        >

                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group">

                        <label for="email">
                            Email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="phone">
                            Phone
                        </label>

                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            required
                        >

                    </div>

                </div>


                <div class="form-group">

                    <label for="department">
                        Department
                    </label>

                    <select
                        id="department"
                        name="department"
                        required
                    >

                        <option value="">
                            Select Department
                        </option>

                        <option value="ICT">
                            ICT
                        </option>

                        <option value="Finance">
                            Finance
                        </option>

                        <option value="Marketing">
                            Marketing
                        </option>

                        <option value="Procurement">
                            Procurement
                        </option>

                        <option value="Administration">
                            Administration
                        </option>

                        <option value="Human Resource">
                            Human Resource
                        </option>

                    </select>

                </div>


                <div class="form-row">

                    <div class="form-group">

                        <label for="username">
                            Username
                        </label>

                        <input
                            type="text"
                            id="username"
                            name="username"
                            required
                        >

                    </div>


                    <div class="form-group">

                        <label for="password">
                            Password
                        </label>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                        >

                    </div>

                </div>


                <button type="submit">
                    Add Employee
                </button>

            </form>

        </div>

    </div>

</body>

</html>