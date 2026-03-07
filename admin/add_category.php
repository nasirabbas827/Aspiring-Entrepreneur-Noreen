<?php
session_start();
include "config.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminlogin.php");
    exit();
}

$message = "";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $category_name = trim($_POST['category_name']);

    $stmt = mysqli_prepare($conn,"INSERT INTO categories (category_name) VALUES (?)");
    mysqli_stmt_bind_param($stmt,"s",$category_name);

    if(mysqli_stmt_execute($stmt)){
        $message = "Category added successfully.";
    }else{
        $message = "Error adding category.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Category</title>
<link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

<?php include('navbar.php'); ?>

<div>

<h2>Add Category</h2>

<?php if($message) echo $message; ?>

<form method="post">

<label>Category Name</label>

<input type="text" name="category_name" required>

  

<button type="submit">Add Category</button>

</form>

</div>

</body>
</html>