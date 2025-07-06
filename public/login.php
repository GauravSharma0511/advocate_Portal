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
    echo "<script>alert('Either Login ID or Password is wrong.');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container-box">
    <h2>Login</h2>

    <form method="POST">
        <label for="enrollment_no">Enrollment No:</label>
        <input type="text" name="enrollment_no" id="enrollment_no" placeholder="Enter your Enrollment No" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="Enter your Password" required>

        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>
