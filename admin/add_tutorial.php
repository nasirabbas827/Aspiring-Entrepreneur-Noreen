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
$video_url = trim($_POST['video_url']);

$stmt = mysqli_prepare($conn,
"INSERT INTO tutorials (title, description, video_url) VALUES (?, ?, ?)");

mysqli_stmt_bind_param($stmt,"sss",$title,$description,$video_url);

if(mysqli_stmt_execute($stmt)){
$message = "Tutorial added successfully.";
}else{
$message = "Error adding tutorial.";
}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Tutorial</title>
<link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

<?php include('navbar.php'); ?>

<div>

<h2>Add Tutorial</h2>

<?php if($message) echo $message; ?>

<form method="post">

<label>Title</label>
<input type="text" name="title" required>

  

<label>Description</label>
<textarea name="description" rows="5" required></textarea>

  

<label>Video URL (YouTube)</label>
<input type="text" name="video_url" required>

  

<button type="submit">Add Tutorial</button>

</form>

</div>

</body>
</html>