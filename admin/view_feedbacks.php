<?php
session_start();
include "../config.php";

// Check if admin is logged in
if(!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true){
    header("Location: adminlogin.php");
    exit();
}

$successMsg = $errorMsg = "";

// Handle Delete Feedback
if(isset($_GET['delete'])){
    $deleteId = intval($_GET['delete']);
    $deleteQuery = "DELETE FROM feedback WHERE feedback_id = $deleteId";
    if(mysqli_query($conn, $deleteQuery)){
        $successMsg = "Feedback deleted successfully.";
    } else {
        $errorMsg = "Failed to delete feedback. Please try again.";
    }
}

// Fetch all feedbacks
$feedbacks = mysqli_query($conn, "
    SELECT f.*, u.username AS user_name, a.full_name AS agent_name
    FROM feedback f
    LEFT JOIN users u ON f.user_id = u.id
    LEFT JOIN agents a ON f.agent_id = a.agent_id
    ORDER BY f.created_at DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - Feedbacks</title>
<link rel="stylesheet" href="../CSS/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container">
<h2>All Feedbacks</h2>

<?php if($successMsg) echo "<div style='color:green;'>$successMsg</div>"; ?>
<?php if($errorMsg) echo "<div style='color:red;'>$errorMsg</div>"; ?>

<?php if(mysqli_num_rows($feedbacks) > 0){ ?>
<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>User</th>
        <th>Agent</th>
        <th>Rating</th>
        <th>Comment</th>
        <th>Created At</th>
        <th>Action</th>
    </tr>
    <?php while($f = mysqli_fetch_assoc($feedbacks)){ ?>
    <tr>
        <td><?php echo $f['feedback_id']; ?></td>
        <td><?php echo htmlspecialchars($f['user_name']); ?></td>
        <td><?php echo htmlspecialchars($f['agent_name']); ?></td>
        <td><?php echo $f['rating']; ?> / 5</td>
        <td><?php echo nl2br(htmlspecialchars($f['comment'])); ?></td>
        <td><?php echo $f['created_at']; ?></td>
        <td>
            <a href="?delete=<?php echo $f['feedback_id']; ?>" onclick="return confirm('Are you sure to delete this feedback?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
</table>
<?php } else { echo "<p>No feedbacks found.</p>"; } ?>

</div>
</body>
</html>
<?php mysqli_close($conn); ?>