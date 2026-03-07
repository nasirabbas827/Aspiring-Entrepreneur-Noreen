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
"SELECT * FROM business_templates 
WHERE template_id=$id AND agent_id=$agent_id");

$template = mysqli_fetch_assoc($result);

$message="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

$title = trim($_POST['title']);
$description = trim($_POST['description']);

$fileName = $template['file_path'];

if(!empty($_FILES['template_file']['name'])){

$newName = time()."_".$_FILES['template_file']['name'];
$tmp = $_FILES['template_file']['tmp_name'];

move_uploaded_file($tmp,"../uploads/templates/".$newName);

$fileName = $newName;
}

$stmt = mysqli_prepare($conn,
"UPDATE business_templates 
SET title=?, description=?, file_path=? 
WHERE template_id=? AND agent_id=?");

mysqli_stmt_bind_param($stmt,"sssii",
$title,$description,$fileName,$id,$agent_id);

if(mysqli_stmt_execute($stmt)){
$message="Template updated successfully.";
}else{
$message="Update failed.";
}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Template</title>
<link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

<?php include('navbar.php'); ?>

<div>

<h2>Edit Template</h2>

<?php if($message) echo $message; ?>

<form method="post" enctype="multipart/form-data">

<label>Title</label>
<input type="text" name="title"
value="<?php echo htmlspecialchars($template['title']); ?>" required>

  

<label>Description</label>

<textarea name="description" rows="5" required>
<?php echo htmlspecialchars($template['description']); ?>
</textarea>

  

<label>Replace File (Optional)</label>
<input type="file" name="template_file">

  

<button type="submit">Update Template</button>

</form>

</div>

</body>
</html>