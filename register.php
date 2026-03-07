<?php
include('config.php');

$message = ""; // Store feedback message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);

    // Check if username already exists
    $sql_username_check = "SELECT * FROM users WHERE username = ?";
    $stmt_username_check = mysqli_prepare($conn, $sql_username_check);
    mysqli_stmt_bind_param($stmt_username_check, "s", $username);
    mysqli_stmt_execute($stmt_username_check);
    mysqli_stmt_store_result($stmt_username_check);
    $username_count = mysqli_stmt_num_rows($stmt_username_check);
    mysqli_stmt_close($stmt_username_check);

    // Check if email already exists
    $sql_email_check = "SELECT * FROM users WHERE email = ?";
    $stmt_email_check = mysqli_prepare($conn, $sql_email_check);
    mysqli_stmt_bind_param($stmt_email_check, "s", $email);
    mysqli_stmt_execute($stmt_email_check);
    mysqli_stmt_store_result($stmt_email_check);
    $email_count = mysqli_stmt_num_rows($stmt_email_check);
    mysqli_stmt_close($stmt_email_check);

    // Check if phone number already exists
    $sql_phone_check = "SELECT * FROM users WHERE phone = ?";
    $stmt_phone_check = mysqli_prepare($conn, $sql_phone_check);
    mysqli_stmt_bind_param($stmt_phone_check, "s", $phone);
    mysqli_stmt_execute($stmt_phone_check);
    mysqli_stmt_store_result($stmt_phone_check);
    $phone_count = mysqli_stmt_num_rows($stmt_phone_check);
    mysqli_stmt_close($stmt_phone_check);

    if ($username_count > 0) {
        $message = "<div style='color:red;'>Username already exists. Please choose a different username.</div>";
    } elseif ($email_count > 0) {
        $message = "<div style='color:red;'>Email already exists. Please use a different email address.</div>";
    } elseif ($phone_count > 0) {
        $message = "<div style='color:red;'>Phone number already exists. Please use a different phone number.</div>";
    } else {
        // Hash the password before storing
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert new user into the database
        $sql_insert_user = "INSERT INTO users (username, password, email, phone) VALUES (?, ?, ?, ?)";
        $stmt_insert_user = mysqli_prepare($conn, $sql_insert_user);
        mysqli_stmt_bind_param($stmt_insert_user, "ssss", $username, $hashed_password, $email, $phone);

        if (mysqli_stmt_execute($stmt_insert_user)) {
            $message = "<div style='color:green;'>Registration successful.</div>";
        } else {
            $message = "<div style='color:red;'>Registration failed. Please try again later.</div>";
        }

        mysqli_stmt_close($stmt_insert_user);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User Registration</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>
    <?php include('navbar.php'); ?>

    <div>
        <h2>User Registration</h2>

        <?php if (!empty($message)) echo $message; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div>
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div>
                <label>Phone Number</label>
                <input type="number" name="phone" required>
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div>
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div>
                <input type="submit" value="Register">
            </div>
        </form>
    </div>
</body>
</html>
