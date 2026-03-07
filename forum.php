<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle new topic submission
$topic_msg = "";
if(isset($_POST['topic_submit'])){
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    if($title != "" && $description != ""){
        $stmt = mysqli_prepare($conn, "INSERT INTO forum_topics (user_id, title, description) VALUES (?,?,?)");
        mysqli_stmt_bind_param($stmt, "iss", $user_id, $title, $description);
        mysqli_stmt_execute($stmt);
        $topic_msg = "New topic created successfully.";
    }
}

// Fetch all topics
$topics = mysqli_query($conn, "SELECT ft.*, u.username FROM forum_topics ft 
                               LEFT JOIN users u ON ft.user_id = u.id
                               ORDER BY ft.created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Forum</title>
<link rel="stylesheet" href="./CSS/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container">
<h2>Forum Topics</h2>

<?php if($topic_msg) echo "<div style='color:green;'>$topic_msg</div>"; ?>

<form method="post">
    <input type="text" name="title" placeholder="Topic Title" required> 
    <textarea name="description" rows="4" placeholder="Topic Description" required></textarea> 
    <button type="submit" name="topic_submit">Create Topic</button>
</form>

<hr>

<?php if(mysqli_num_rows($topics) > 0){ ?>
<table border="1" cellpadding="5">
<tr>
<th>Title</th>
<th>Created By</th>
<th>Date</th>
<th>Actions</th>
</tr>
<?php while($t = mysqli_fetch_assoc($topics)){ ?>
<tr>
<td><?php echo htmlspecialchars($t['title']); ?></td>
<td><?php echo htmlspecialchars($t['username']); ?></td>
<td><?php echo $t['created_at']; ?></td>
<td><a href="view_topic.php?id=<?php echo $t['topic_id']; ?>">View</a></td>
</tr>
<?php } ?>
</table>
<?php } else { echo "<p>No topics yet.</p>"; } ?>

</div>
</body>
</html>
<?php mysqli_close($conn); ?>