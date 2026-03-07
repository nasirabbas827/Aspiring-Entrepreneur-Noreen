<?php
session_start();
include "../config.php";

if(!isset($_SESSION['agent_id'])){
    header("Location: ../login.php");
    exit();
}

$agent_id = $_SESSION['agent_id'];

$id = intval($_GET['id']);

$result = mysqli_query($conn,
"SELECT * FROM opportunities 
WHERE opportunity_id=$id AND agent_id=$agent_id");

$opportunity = mysqli_fetch_assoc($result);

$message="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

$title = trim($_POST['title']);
$description = trim($_POST['description']);
$location = trim($_POST['location']);

$stmt = mysqli_prepare($conn,
"UPDATE opportunities 
SET title=?, description=?, location=? 
WHERE opportunity_id=? AND agent_id=?");

mysqli_stmt_bind_param($stmt,"sssii",
$title,$description,$location,$id,$agent_id);

if(mysqli_stmt_execute($stmt)){
$message="Opportunity updated successfully.";
}else{
$message="Update failed.";
}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Opportunity</title>
<link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

<?php include('navbar.php'); ?>

<div>

<h2>Edit Opportunity</h2>

<?php if($message) echo $message; ?>

<form method="post">

<label>Title</label>
<input type="text" name="title"
value="<?php echo htmlspecialchars($opportunity['title']); ?>" required>

  

<label>Description</label>
<textarea name="description" rows="5" required><?php echo htmlspecialchars($opportunity['description']); ?></textarea>

  

<label>Location</label>
<input type="text" name="location"
value="<?php echo htmlspecialchars($opportunity['location']); ?>" required>

  

<button type="submit">Update Opportunity</button>

</form>

</div>

</body>
</html>