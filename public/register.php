
    <!-- Your form here -->
     <?php
include '../db/connect.php';
include '../config/encryption.php';

function encryptData($data) {
    $iv_length = openssl_cipher_iv_length(CIPHER_METHOD);
    $iv = random_bytes($iv_length);
    $encrypted = openssl_encrypt($data, CIPHER_METHOD, SECRET_KEY, 0, $iv);
    return base64_encode($iv . $encrypted);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $enrollment_no = $_POST['enrollment_no'];
    $password = $_POST['password'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $pin_code = $_POST['pin_code'];

    // Basic validation
    if (!preg_match("/^[a-zA-Z]\d{4}[a-zA-Z]{2}\d{4}$/", $enrollment_no)) {
        echo "Invalid enrollment number format.";
        exit;
    }

    if (!preg_match("/^[0-9]{10}$/", $mobile)) {
        echo "Invalid mobile number.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $mobile_encrypted = encryptData($mobile);
    $email_encrypted = encryptData($email);

    $query = "INSERT INTO advocate_users (name, enrollment_no, password_hash, mobile_encrypted, email_encrypted, state, district, pin_code) 
              VALUES ('$name', '$enrollment_no', '$password_hash', '$mobile_encrypted', '$email_encrypted', '$state', '$district', '$pin_code')";

    if (mysqli_query($conn, $query)) {
        echo "Registration successful. <a href='login.php'>Login Now</a>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?><!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <h2>Advocate Registration</h2>
<?php if (!empty($registrationMessage)) echo "<p>$registrationMessage</p>"; ?>
<!-- HTML Form -->
<form method="POST">
    <input type="text" name="name" placeholder="Full Name" required><br>
    <input type="text" name="enrollment_no" placeholder="Enrollment No" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="tel" name="mobile" placeholder="Mobile" pattern="[0-9]{10}" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="text" name="state" placeholder="State" required><br>
    <input type="text" name="district" placeholder="District" required><br>
    <input type="text" name="pin_code" placeholder="PIN Code" required><br>
    <button type="submit">Register</button>
</form>

</body>
</html>

