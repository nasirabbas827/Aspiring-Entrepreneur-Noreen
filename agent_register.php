<?php
include('config.php');

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $phone = trim($_POST["phone"]);
    $expertise_domain = trim($_POST["expertise_domain"]);
    $bio = trim($_POST["bio"]);
    $experience_years = intval($_POST["experience_years"]);

    // Check if email exists
    $sql_email = "SELECT agent_id FROM agents WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql_email);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $email_count = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);

    // Check if phone exists
    $sql_phone = "SELECT agent_id FROM agents WHERE phone = ?";
    $stmt = mysqli_prepare($conn, $sql_phone);
    mysqli_stmt_bind_param($stmt, "s", $phone);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $phone_count = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);

    if ($email_count > 0) {
        $message = "<div style='color:red;'>Email already registered.</div>";
    } elseif ($phone_count > 0) {
        $message = "<div style='color:red;'>Phone number already registered.</div>";
    } else {

        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $sql_insert = "INSERT INTO agents 
        (full_name, email, password, phone, expertise_domain, bio, experience_years) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql_insert);
        mysqli_stmt_bind_param(
            $stmt,
            "ssssssi",
            $full_name,
            $email,
            $hashed_password,
            $phone,
            $expertise_domain,
            $bio,
            $experience_years
        );

        if (mysqli_stmt_execute($stmt)) {
            $message = "<div style='color:green;'>Agent registration submitted. Waiting for admin approval.</div>";
        } else {
            $message = "<div style='color:red;'>Registration failed. Try again.</div>";
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Agent Registration</title>
<link rel="stylesheet" href="./CSS/style.css">
</head>

<body>

<?php include('navbar.php'); ?>

<div>
<h2>Agent / Mentor Registration</h2>

<?php if (!empty($message)) echo $message; ?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

<div>
<label>Full Name</label>
<input type="text" name="full_name" required>
</div>

<div>
<label>Email</label>
<input type="email" name="email" required>
</div>

<div>
<label>Phone</label>
<input type="number" name="phone" required>
</div>

<div>
<label>Password</label>
<input type="password" name="password" required>
</div>

<div>
<label>Expertise Domain</label>
<input type="text" name="expertise_domain" placeholder="e.g. Startup Funding, Marketing" required>
</div>

<div>
<label>Experience (Years)</label>
<input type="number" name="experience_years" required>
</div>

<div>
<label>Bio</label>
<textarea name="bio" rows="4" required></textarea>
</div>

<div>
<input type="submit" value="Register as Agent">
</div>

</form>

</div>

</body>
</html>