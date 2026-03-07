<?php
session_start();
include "config.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminlogin.php");
    exit();
}

$message="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

$title = trim($_POST['title']);
$description = trim($_POST['description']);
$event_date = $_POST['event_date'];
$location = trim($_POST['location']);

$stmt = mysqli_prepare($conn,
"INSERT INTO events (title, description, event_date, location) VALUES (?, ?, ?, ?)");

mysqli_stmt_bind_param($stmt,"ssss",$title,$description,$event_date,$location);

if(mysqli_stmt_execute($stmt)){
$message="Event added successfully.";
}else{
$message="Error adding event.";
}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Event</title>
<link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

<?php include('navbar.php'); ?>

<div>

<h2>Add Event</h2>

<?php if($message) echo $message; ?>

<form method="post">

<label>Title</label>
<input type="text" name="title" required>

  

<label>Description</label>
<textarea name="description" rows="5" required></textarea>

  

<label>Event Date</label>
<input type="date" name="event_date" required>

  

<label>Location</label>
<input type="text" name="location" required>

  

<button type="submit">Add Event</button>

</form>

</div>

</body>
</html>