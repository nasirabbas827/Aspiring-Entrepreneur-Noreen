<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("location: index.php");
    exit;
}

include "config.php";

$userId = $_SESSION["user_id"];
$success = $error = "";

// Fetch user data
$stmt = $conn->prepare("SELECT username, email, phone, cnic FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User not found.");
}

// Update profile if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email    = trim($_POST["email"]);
    $phone    = trim($_POST["phone"]);
    $cnic     = trim($_POST["cnic"]);

    if (!empty($username) && !empty($email) && !empty($phone) && !empty($cnic)) {
        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, phone=?, cnic=? WHERE id=?");
        $stmt->bind_param("ssssi", $username, $email, $phone, $cnic, $userId);

        if ($stmt->execute()) {
            $success = "Profile updated successfully!";
            // Refresh local data
            $user["username"] = $username;
            $user["email"]    = $email;
            $user["phone"]    = $phone;
            $user["cnic"]     = $cnic;
        } else {
            $error = "Error updating profile: " . $conn->error;
        }
        $stmt->close();
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/style.css">
    <title>Update Profile</title>
</head>
<body>
<?php include('navbar.php'); ?>

<h2>Update Profile</h2>

<?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
<?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

<form method="POST">
    <label>Username:</label> 
    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>  

    <label>Email:</label> 
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>  

    <label>Phone:</label> 
    <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" required>  

    <label>CNIC:</label> 
    <input type="text" name="cnic" value="<?= htmlspecialchars($user['cnic']) ?>" required>  

    <button type="submit">Update Profile</button>
</form>

</body>
</html>

<?php mysqli_close($conn); ?>
