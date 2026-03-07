<?php
session_start();
include "config.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminlogin.php");
    exit();
}

$id = intval($_GET['id']);

$result = mysqli_query($conn,"SELECT * FROM tutorials WHERE tutorial_id=$id");
$tutorial = mysqli_fetch_assoc($result);

$message="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

$title = trim($_POST['title']);
$description = trim($_POST['description']);
$video_url = trim($_POST['video_url']);

$stmt = mysqli_prepare($conn,
"UPDATE tutorials SET title=?, description=?, video_url=? WHERE tutorial_id=?");

mysqli_stmt_bind_param($stmt,"sssi",$title,$description,$video_url,$id);

if(mysqli_stmt_execute($stmt)){
$message="Tutorial updated successfully.";
}else{
$message="Update failed.";
}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Tutorial</title>
<link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

<?php include('navbar.php'); ?>

<div>

<h2>Edit Tutorial</h2>

<?php if($message) echo $message; ?>

<form method="post">

<label>Title</label>
<input type="text" name="title"
value="<?php echo htmlspecialchars($tutorial['title']); ?>" required>

  

<label>Description</label>
<textarea name="description" rows="5" required><?php echo htmlspecialchars($tutorial['description']); ?></textarea>

  

<label>Video URL</label>
<input type="text" name="video_url"
value="<?php echo htmlspecialchars($tutorial['video_url']); ?>" required>

  

<button type="submit">Update Tutorial</button>

</form>

</div>

</body>
</html>