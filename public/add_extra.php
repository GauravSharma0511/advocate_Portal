<?php
session_start();
include '../db/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dob = $_POST['dob'];
    $enrollment_date = $_POST['enrollment_date'];
    $photo = $_FILES['photo'];

    // Validate photo
    $file_type = pathinfo($photo['name'], PATHINFO_EXTENSION);
    $file_size = $photo['size'];

    if ($file_type !== 'jpg') {
        echo "Only JPG images are allowed.";
        exit;
    }

    if ($file_size < 20000 || $file_size > 500000) {
        echo "Image size must be between 20KB and 500KB.";
        exit;
    }

    $photo_path = '../uploads/' . time() . '_' . basename($photo['name']);
    move_uploaded_file($photo['tmp_name'], $photo_path);

    $user_id = $_SESSION['user_id'];
    $query = "INSERT INTO advocate_profiles (user_id, dob, enrollment_date, photo_path)
              VALUES ('$user_id', '$dob', '$enrollment_date', '$photo_path')";

    if (mysqli_query($conn, $query)) {
        echo "Profile updated successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
<!-- HTML Form -->
<form method="POST" enctype="multipart/form-data">
    <input type="date" name="dob" required><br>
    <input type="date" name="enrollment_date" required><br>
    <input type="file" name="photo" accept=".jpg" required><br>
    <button type="submit">Submit</button>
</form>
</body>
</html>