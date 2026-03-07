<?php
session_start();
include "config.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminlogin.php");
    exit();
}

$id = intval($_GET['id']);

$result = mysqli_query($conn,"SELECT * FROM events WHERE event_id=$id");
$event = mysqli_fetch_assoc($result);

$message="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

$title = trim($_POST['title']);
$description = trim($_POST['description']);
$event_date = $_POST['event_date'];
$location = trim($_POST['location']);

$stmt = mysqli_prepare($conn,
"UPDATE events SET title=?, description=?, event_date=?, location=? WHERE event_id=?");

mysqli_stmt_bind_param($stmt,"ssssi",$title,$description,$event_date,$location,$id);

if(mysqli_stmt_execute($stmt)){
$message="Event updated successfully.";
}else{
$message="Update failed.";
}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Event</title>
<link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

<?php include('navbar.php'); ?>

<div>

<h2>Edit Event</h2>

<?php if($message) echo $message; ?>

<form method="post">

<label>Title</label>
<input type="text" name="title"
value="<?php echo htmlspecialchars($event['title']); ?>" required>

  

<label>Description</label>
<textarea name="description" rows="5" required><?php echo htmlspecialchars($event['description']); ?></textarea>

  

<label>Event Date</label>
<input type="date" name="event_date"
value="<?php echo $event['event_date']; ?>" required>

  

<label>Location</label>
<input type="text" name="location"
value="<?php echo htmlspecialchars($event['location']); ?>" required>

  

<button type="submit">Update Event</button>

</form>

</div>

</body>
</html>