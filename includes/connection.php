<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "attendance_system"
);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

?>