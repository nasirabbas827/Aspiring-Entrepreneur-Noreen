<?php
session_start();
include "../config.php";

if(!isset($_SESSION['agent_id'])){
    header("Location: ../login.php");
    exit();
}

$agent_id = $_SESSION['agent_id'];

$message = "";

if($_SERVER["REQUEST_METHOD"]=="POST"){

$title = trim($_POST['title']);
$description = trim($_POST['description']);

$fileName = $_FILES['template_file']['name'];
$tmpName = $_FILES['template_file']['tmp_name'];

$uploadDir = "../uploads/templates/";
$newFileName = time()."_".$fileName;

move_uploaded_file($tmpName,$uploadDir.$newFileName);

$stmt = mysqli_prepare($conn,
"INSERT INTO business_templates (agent_id,title,description,file_path)
VALUES (?,?,?,?)");

mysqli_stmt_bind_param($stmt,"isss",
$agent_id,$title,$description,$newFileName);

if(mysqli_stmt_execute($stmt)){
$message="Template uploaded successfully.";
}else{
$message="Upload failed.";
}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Upload Template</title>
<link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

<?php include('navbar.php'); ?>

<div>

<h2>Upload Business Template</h2>

<?php if($message) echo $message; ?>

<form method="post" enctype="multipart/form-data">

<label>Title</label>
<input type="text" name="title" required>

  

<label>Description</label>
<textarea name="description" rows="5" required></textarea>

  

<label>Upload Template File</label>
<input type="file" name="template_file" required>

  

<button type="submit">Upload Template</button>

</form>

</div>

</body>
</html>