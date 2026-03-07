<?php
include('config.php');
session_start();

$email = $password = "";
$email_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    if (empty($email)) {
        $email_err = "Please enter your email.";
    }

    if (empty($password)) {
        $password_err = "Please enter your password.";
    }

    if (empty($email_err) && empty($password_err)) {

        // ---------- CHECK USERS TABLE ----------
        $sql_user = "SELECT id, email, password FROM users WHERE email = ?";

        if ($stmt = mysqli_prepare($conn, $sql_user)) {

            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {

                mysqli_stmt_bind_result($stmt, $id, $email_db, $hashed_password);

                if (mysqli_stmt_fetch($stmt)) {

                    if (password_verify($password, $hashed_password)) {

                        $_SESSION["user_id"] = $id;
                        $_SESSION["email"] = $email_db;
                        $_SESSION["role"] = "user";

                        header("Location: dashboard.php");
                        exit();
                    } else {
                        $password_err = "Incorrect password.";
                    }
                }
            }

            mysqli_stmt_close($stmt);
        }

        // ---------- CHECK AGENTS TABLE ----------
        if (empty($password_err)) {

            $sql_agent = "SELECT agent_id, email, password, approval_status 
                          FROM agents 
                          WHERE email = ?";

            if ($stmt = mysqli_prepare($conn, $sql_agent)) {

                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {

                    mysqli_stmt_bind_result($stmt, $agent_id, $email_db, $hashed_password, $status);

                    if (mysqli_stmt_fetch($stmt)) {

                        if (password_verify($password, $hashed_password)) {

                            if ($status == "approved") {

                                $_SESSION["agent_id"] = $agent_id;
                                $_SESSION["email"] = $email_db;
                                $_SESSION["role"] = "agent";

                                header("Location: agent/dashboard.php");
                                exit();

                            } elseif ($status == "pending") {

                                $email_err = "Your agent account is waiting for admin approval.";

                            } else {

                                $email_err = "Your agent account has been rejected.";

                            }

                        } else {
                            $password_err = "Incorrect password.";
                        }
                    }

                } else {
                    if (empty($password_err)) {
                        $email_err = "No account found with that email.";
                    }
                }

                mysqli_stmt_close($stmt);
            }
        }
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Login</title>
<link rel="stylesheet" href="./CSS/style.css">
</head>

<body>

<?php include('navbar.php'); ?>

<div>
<div class="card-body">

<h2>Login</h2>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

<div>
<label>Email</label>
<input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
<span class="error"><?php echo $email_err; ?></span>
</div>

<div>
<label>Password</label>
<input type="password" name="password">
<span class="error"><?php echo $password_err; ?></span>
</div>

<div>
<input type="submit" value="Login">
</div>

</form>

</div>
</div>

</body>
</html>