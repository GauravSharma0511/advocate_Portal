<?php
session_start();
include '../db/connect.php';
include '../config/encryption.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

function decryptData($data) {
    $decoded = base64_decode($data);
    $iv_length = openssl_cipher_iv_length(CIPHER_METHOD);
    $iv = substr($decoded, 0, $iv_length);
    $encrypted_data = substr($decoded, $iv_length);
    return openssl_decrypt($encrypted_data, CIPHER_METHOD, SECRET_KEY, 0, $iv);
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM advocate_users WHERE id=$user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    
  <h2>Welcome, <?php echo $user['name']; ?></h2>
<p>Enrollment No: <?php echo $user['enrollment_no']; ?></p>
<p>Email: <?php echo decryptData($user['email_encrypted']); ?></p>
<p>Mobile: <?php echo decryptData($user['mobile_encrypted']); ?></p>
<p>State: <?php echo $user['state']; ?></p>
<p>District: <?php echo $user['district']; ?></p>
<p>PIN Code: <?php echo $user['pin_code']; ?></p>
<a href="add_extra.php">Add More Info</a> | <a href="logout.php">Logout</a>


    <!-- Your form here -->
</body>
</html>