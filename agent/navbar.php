<nav>
        <ul>
            <li><a href="dashboard.php">Aspiring Entrepreneurs</a></li>
            <?php
            if (isset($_SESSION["agent_id"]) && !empty($_SESSION["agent_id"])) {
                echo '<li><a href="dashboard.php">Home</a></li>';
                echo '<li><a href="manage_ideas.php">Ideas</a></li>';
                echo '<li><a href="manage_templates.php">Templates</a></li>';
                echo '<li><a href="manage_opportunities.php">Opportunities</a></li>';
                echo '<li><a href="request&messages.php">Messages</a></li>';
                echo '<li><a href="feedbacks.php">Feedbacks</a></li>';
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