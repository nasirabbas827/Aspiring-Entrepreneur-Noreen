<?php
session_start();
include "../config.php";

if(!isset($_SESSION['agent_id'])){
    header("Location: ../login.php");
    exit();
}

$agent_id = $_SESSION['agent_id'];

// Fetch all feedback for this agent
$feedbacks = mysqli_query($conn, "
    SELECT f.*, u.username 
    FROM feedback f
    LEFT JOIN users u ON f.user_id = u.id
    WHERE f.agent_id = $agent_id
    ORDER BY f.created_at DESC
");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Agent Feedbacks</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container">
<h2>Your Feedbacks</h2>

<?php if(mysqli_num_rows($feedbacks) > 0){ ?>
<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>User</th>
        <th>Rating</th>
        <th>Comment</th>
        <th>Date</th>
    </tr>
    <?php while($f = mysqli_fetch_assoc($feedbacks)){ ?>
    <tr>
        <td><?php echo htmlspecialchars($f['username']); ?></td>
        <td><?php echo $f['rating']; ?> / 5</td>
        <td><?php echo nl2br(htmlspecialchars($f['comment'])); ?></td>
        <td><?php echo $f['created_at']; ?></td>
    </tr>
    <?php } ?>
</table>
<?php } else { ?>
<p>No feedback has been submitted yet.</p>
<?php } ?>

</div>
</body>
</html>
<?php mysqli_close($conn); ?>