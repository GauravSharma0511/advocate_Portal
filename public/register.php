<?php
include '../db/connect.php';
include '../config/encryption.php';

function encryptData($data) {
    $iv_length = openssl_cipher_iv_length(CIPHER_METHOD);
    $iv = random_bytes($iv_length);
    $encrypted = openssl_encrypt($data, CIPHER_METHOD, SECRET_KEY, 0, $iv);
    return base64_encode($iv . $encrypted);
}

$registrationMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $enrollment_no = $_POST['enrollment_no'];
    $password = $_POST['password'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $pin_code = $_POST['pin_code'];

    // Server-side validation
    if (strlen($password) < 8) {
        $registrationMessage = "Password must be at least 8 characters long.";
    } elseif (!preg_match("/^[a-zA-Z]\d{4}[a-zA-Z]{2}\d{4}$/", $enrollment_no)) {
        $registrationMessage = "Invalid enrollment number format.";
    } elseif (!preg_match("/^[0-9]{10}$/", $mobile)) {
        $registrationMessage = "Invalid mobile number.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $registrationMessage = "Invalid email address.";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $mobile_encrypted = encryptData($mobile);
        $email_encrypted = encryptData($email);

        $query = "INSERT INTO advocate_users 
            (name, enrollment_no, password_hash, mobile_encrypted, email_encrypted, state, district, pin_code) 
            VALUES ('$name', '$enrollment_no', '$password_hash', '$mobile_encrypted', '$email_encrypted', '$state', '$district', '$pin_code')";

        if (mysqli_query($conn, $query)) {
            $registrationMessage = "Registration successful. <a href='login.php'>Login Now</a>";
        } else {
            $registrationMessage = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="../assets/style.css">
    <script>
        function validateForm() {
            const password = document.getElementById("password").value;
            if (password.length < 8) {
                alert("Password must be at least 8 characters long.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>

<h2>New Advocate Registration</h2>
<?php if (!empty($registrationMessage)) echo "<p>$registrationMessage</p>"; ?>

<form method="POST" onsubmit="return validateForm();">
    <label for="name">Full Name:</label><br>
    <input type="text" name="name" id="name" placeholder="Full Name" required><br><br>

    <label for="enrollment_no">Enrollment No:</label><br>
    <input type="text" name="enrollment_no" id="enrollment_no" placeholder="Enrollment No" required><br><br>

    <label for="password">Password (Min 8 characters):</label><br>
    <input type="password" name="password" id="password" placeholder="Password" required><br><br>

    <label for="mobile">Mobile:</label><br>
    <input type="tel" name="mobile" id="mobile" placeholder="Mobile" pattern="[0-9]{10}" required><br><br>

    <label for="email">Email:</label><br>
    <input type="email" name="email" id="email" placeholder="Email" required><br><br>

    <label for="state">State:</label><br>
    <input type="text" name="state" id="state" placeholder="State" required><br><br>

    <label for="district">District:</label><br>
    <input type="text" name="district" id="district" placeholder="District" required><br><br>

    <label for="pin_code">PIN Code:</label><br>
    <input type="text" name="pin_code" id="pin_code" placeholder="PIN Code" required><br><br>

    <button type="submit">Register</button>
</form>

</body>
</html>
