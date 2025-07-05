<?php
session_start();
include '../db/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enrollment_no = $_POST['enrollment_no'];
    $password = $_POST['password'];

    $query = "SELECT * FROM advocate_users WHERE enrollment_no='$enrollment_no'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row['password_hash'])) {
            $_SESSION['user_id'] = $row['id'];
            header("Location: dashboard.php");
            exit();
        }
    }
    echo "Either Login ID or Password is wrong.";
}
?>

<!-- HTML Login Form -->

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Login </h2>
    <form method="POST">
    <input  type="text" name="enrollment_no" placeholder="Enrollment No" required><br>
    <input name="password" type="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>
    <!-- Your form here -->
</body>
</html>