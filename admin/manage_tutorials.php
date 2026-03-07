<?php
session_start();
include "config.php";

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

$successMsg = "";
$errorMsg = "";

/* DELETE */
if(isset($_GET['delete'])){
    $deleteId = intval($_GET['delete']);

    if(mysqli_query($conn,"DELETE FROM tutorials WHERE tutorial_id=$deleteId")){
        $successMsg = "Tutorial deleted successfully.";
    }else{
        $errorMsg = "Error deleting tutorial.";
    }
}

/* SEARCH */
$searchQuery = "";

if(isset($_GET['search'])){
    $search = trim($_GET['search']);
    $searchQuery = "WHERE tutorial_id LIKE '%$search%' 
                    OR title LIKE '%$search%'";
}

/* FETCH DATA */
$tutorials = mysqli_query($conn,"SELECT * FROM tutorials $searchQuery ORDER BY tutorial_id DESC");

if(mysqli_num_rows($tutorials) == 0){
    $errorMsg = "No tutorials found.";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Tutorials</title>
<link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

<?php include('navbar.php'); ?>

<div>

<h2>Manage Tutorials</h2>

<?php if($successMsg) echo "<div style='color:green;'>$successMsg</div>"; ?>
<?php if($errorMsg) echo "<div style='color:red;'>$errorMsg</div>"; ?>

<a href="add_tutorial.php" style="margin:40px;">Add New Tutorial</a>

  

<form method="get">
<input type="text" name="search" placeholder="Search Tutorial"
value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
<button type="submit">Search</button>
</form>

 

<?php if(mysqli_num_rows($tutorials)>0){ ?>

<table border="1" cellpadding="10">

<tr>
<th>ID</th>
<th>Title</th>
<th>Description</th>
<th>Video</th>
<th>Date</th>
<th>Actions</th>
</tr>

<?php while($row = mysqli_fetch_assoc($tutorials)){ ?>

<tr>

<td><?php echo $row['tutorial_id']; ?></td>

<td><?php echo htmlspecialchars($row['title']); ?></td>

<td><?php echo htmlspecialchars($row['description']); ?></td>

<td>
<a href="<?php echo htmlspecialchars($row['video_url']); ?>" target="_blank">
Watch Video
</a>
</td>

<td><?php echo $row['created_at']; ?></td>

<td>

<a href="edit_tutorial.php?id=<?php echo $row['tutorial_id']; ?>">Edit</a> |

<a href="?delete=<?php echo $row['tutorial_id']; ?>" 
onclick="return confirm('Delete this tutorial?')">Delete</a>

</td>

</tr>

<?php } ?>

</table>

<?php } ?>

</div>

</body>
</html>