<?php
session_start();
include "config.php";

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: adminlogin.php");
    exit();
}

$id = intval($_GET['id']);

$result = mysqli_query($conn,"SELECT * FROM categories WHERE category_id=$id");
$category = mysqli_fetch_assoc($result);

$message = "";

if($_SERVER["REQUEST_METHOD"]=="POST"){

    $category_name = trim($_POST['category_name']);

    $stmt = mysqli_prepare($conn,"UPDATE categories SET category_name=? WHERE category_id=?");
    mysqli_stmt_bind_param($stmt,"si",$category_name,$id);

    if(mysqli_stmt_execute($stmt)){
        $message = "Category updated successfully.";
    }else{
        $message = "Update failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Category</title>
<link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

<?php include('navbar.php'); ?>

<div>

<h2>Edit Category</h2>

<?php if($message) echo $message; ?>

<form method="post">

<label>Category Name</label>

<input type="text" name="category_name"
value="<?php echo htmlspecialchars($category['category_name']); ?>" required>

  

<button type="submit">Update Category</button>

</form>

</div>

</body>
</html>