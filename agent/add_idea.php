<?php
session_start();
include "config.php";

if(!isset($_SESSION['agent_id'])){
    header("Location: ../login.php");
    exit();
}

$agent_id = $_SESSION['agent_id'];

$message = "";

/* FETCH CATEGORIES */
$categories = mysqli_query($conn,"SELECT * FROM categories");

if($_SERVER["REQUEST_METHOD"]=="POST"){

$title = trim($_POST['title']);
$description = trim($_POST['description']);
$category_id = intval($_POST['category_id']);
$investment = $_POST['investment'];

$stmt = mysqli_prepare($conn,
"INSERT INTO business_ideas 
(agent_id, category_id, title, description, investment_required) 
VALUES (?, ?, ?, ?, ?)");

mysqli_stmt_bind_param($stmt,"iissd",
$agent_id,$category_id,$title,$description,$investment);

if(mysqli_stmt_execute($stmt)){
$message = "Idea added successfully.";
}else{
$message = "Error adding idea.";
}
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Business Idea</title>
<link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

<?php include('navbar.php'); ?>

<div>

<h2>Add Business Idea</h2>

<?php if($message) echo $message; ?>

<form method="post">

<label>Title</label>
<input type="text" name="title" required>

  

<label>Category</label>
<select name="category_id" required>

<option value="">Select Category</option>

<?php while($cat = mysqli_fetch_assoc($categories)){ ?>

<option value="<?php echo $cat['category_id']; ?>">
<?php echo $cat['category_name']; ?>
</option>

<?php } ?>

</select>

  

<label>Description</label>
<textarea name="description" rows="5" required></textarea>

  

<label>Investment Required</label>
<input type="number" step="0.01" name="investment" required>

  

<button type="submit">Add Idea</button>

</form>

</div>

</body>
</html>