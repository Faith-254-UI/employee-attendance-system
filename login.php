<?php
session_start();
require_once "includes/connection.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT * FROM employees WHERE username = ? AND password = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {

        $employee = mysqli_fetch_assoc($result);

        $_SESSION["employee_id"] = $employee["employee_id"];
        $_SESSION["username"] = $employee["username"];
        $_SESSION["first_name"] = $employee["first_name"];
        $_SESSION["last_name"] = $employee["last_name"];

        header("Location: admin/dashboard.php");        exit();

    } else {

        $error = "Invalid username or password.";

    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Attendance System - Login</title>
</head>

<body>

    <h2>Employee Attendance System</h2>

    <?php if ($error != ""): ?>
        <p><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST" action="">

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <br><br>

        <button type="submit">Login</button>

    </form>

</body>

</html>