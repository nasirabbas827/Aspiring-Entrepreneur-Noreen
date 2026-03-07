<?php
session_start();
include "config.php";

if(!isset($_SESSION['agent_id'])){
    header("Location: ../login.php");
    exit();
}

$agent_id = $_SESSION['agent_id'];
$id = intval($_GET['id']);

$categories = mysqli_query($conn,"SELECT * FROM categories");

$result = mysqli_query($conn,
"SELECT * FROM business_ideas 
WHERE idea_id=$id AND agent_id=$agent_id");

$idea = mysqli_fetch_assoc($result);

$message="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

$title = trim($_POST['title']);
$description = trim($_POST['description']);
$category_id = intval($_POST['category_id']);
$investment = $_POST['investment'];

$stmt = mysqli_prepare($conn,
"UPDATE business_ideas 
SET category_id=?, title=?, description=?, investment_required=? 
WHERE idea_id=? AND agent_id=?");

mysqli_stmt_bind_param($stmt,"issdii",
$category_id,$title,$description,$investment,$id,$agent_id);

if(mysqli_stmt_execute($stmt)){
$message="Idea updated successfully.";
}else{
$message="Update failed.";
}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Business Idea</title>
<link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

<?php include('navbar.php'); ?>

<div>

<h2>Edit Business Idea</h2>

<?php if($message) echo $message; ?>

<form method="post">

<label>Title</label>
<input type="text" name="title"
value="<?php echo htmlspecialchars($idea['title']); ?>" required>

  

<label>Category</label>

<select name="category_id" required>

<?php while($cat = mysqli_fetch_assoc($categories)){ ?>

<option value="<?php echo $cat['category_id']; ?>"
<?php if($cat['category_id'] == $idea['category_id']) echo "selected"; ?>>

<?php echo $cat['category_name']; ?>

</option>

<?php } ?>

</select>

  

<label>Description</label>

<textarea name="description" rows="5" required>
<?php echo htmlspecialchars($idea['description']); ?>
</textarea>

  

<label>Investment Required</label>

<input type="number" step="0.01" name="investment"
value="<?php echo $idea['investment_required']; ?>" required>

  

<button type="submit">Update Idea</button>

</form>

</div>

</body>
</html>