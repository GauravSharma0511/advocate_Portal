<?php
session_start();
include '../db/connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dob = $_POST['dob'];
    $photo = $_FILES['photo'];

    // Validate photo
    $file_type = pathinfo($photo['name'], PATHINFO_EXTENSION);
    $file_size = $photo['size'];

    if (strtolower($file_type) !== 'jpg') {
        echo "<script>alert('Only JPG images are allowed.'); window.history.back();</script>";
        exit;
    }

    if ($file_size < 20000 || $file_size > 500000) {
        echo "<script>alert('Image size must be between 20KB and 500KB.'); window.history.back();</script>";
        exit;
    }

    // Save file
    $photo_path = '../uploads/' . time() . '_' . basename($photo['name']);
    move_uploaded_file($photo['tmp_name'], $photo_path);

    // Save to database
    $user_id = $_SESSION['user_id'];
    $query = "INSERT INTO advocate_profile (user_id, dob, photo_path)
              VALUES ('$user_id', '$dob', '$photo_path')";

    if (mysqli_query($conn, $query)) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<script>alert('Database error: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }
}
$_SESSION['update_success'] = "Profile updated successfully.";
header("Location: dashboard.php");
exit();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Extra Information</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container-box">
    <h2>Add Extra Information</h2>

    <form method="POST" enctype="multipart/form-data">
        <label for="dob">Date of Birth:</label>
        <input type="date" name="dob" id="dob" required>

        <label for="photo">Upload Photo (JPG only, 20–500KB):</label>
        <input type="file" name="photo" id="photo" accept=".jpg" required>

        <button type="submit">Submit</button>
    </form>
</div>

</body>
</html>
