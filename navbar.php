<nav>
        <ul>
            <li><a href="dashboard.php">Aspiring Entrepreneurs</a></li>
            <?php
            if (isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])) {
                echo '<li><a href="dashboard.php">Home</a></li>';
                echo '<li><a href="update_profile.php">Update Profile</a></li>';
                echo '<li><a href="forum.php">Forums</a></li>';
                echo '<li><a href="view_resources.php">Resources</a></li>';
                echo '<li><a href="logout.php">Logout</a></li>';
            } else {
                echo '<li><a href="agent_register.php">Agent Register</a></li>';
                echo '<li><a href="register.php">Register</a></li>';
                echo '<li><a href="login.php">Login</a></li>';
                echo '<li><a href="adminlogin.php">Admin Login</a></li>';
            }
            ?>
        </ul>
    </nav>