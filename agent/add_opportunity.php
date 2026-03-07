<?php
session_start();
include "../config.php";

if(!isset($_SESSION['agent_id'])){
    header("Location: ../login.php");
    exit();
}

$agent_id = $_SESSION['agent_id'];

$message="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

$title = trim($_POST['title']);
$description = trim($_POST['description']);
$location = trim($_POST['location']);

$stmt = mysqli_prepare($conn,
"INSERT INTO opportunities (agent_id,title,description,location)
VALUES (?,?,?,?)");

mysqli_stmt_bind_param($stmt,"isss",
$agent_id,$title,$description,$location);

if(mysqli_stmt_execute($stmt)){
$message="Opportunity added successfully.";
}else{
$message="Error adding opportunity.";
}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Opportunity</title>
<link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

<?php include('navbar.php'); ?>

<div>

<h2>Add Opportunity</h2>

<?php if($message) echo $message; ?>

<form method="post">

<label>Title</label>
<input type="text" name="title" required>

  

<label>Description</label>
<textarea name="description" rows="5" required></textarea>

  

<label>Location</label>
<input type="text" name="location" required>

  

<button type="submit">Add Opportunity</button>

</form>

</div>

</body>
</html>