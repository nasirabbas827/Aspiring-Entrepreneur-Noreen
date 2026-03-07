<?php
session_start();
include "../config.php";

if(!isset($_SESSION['agent_id'])){
    header("Location: ../login.php");
    exit();
}

$agent_id = $_SESSION['agent_id'];

$successMsg = "";
$errorMsg = "";

/* DELETE TEMPLATE */
if(isset($_GET['delete'])){

    $deleteId = intval($_GET['delete']);

    $result = mysqli_query($conn,"SELECT file_path FROM business_templates 
                                  WHERE template_id=$deleteId AND agent_id=$agent_id");

    $row = mysqli_fetch_assoc($result);

    if($row && file_exists("../uploads/templates/".$row['file_path'])){
        unlink("../uploads/templates/".$row['file_path']);
    }

    mysqli_query($conn,"DELETE FROM business_templates 
                        WHERE template_id=$deleteId AND agent_id=$agent_id");

    $successMsg = "Template deleted successfully.";
}

/* SEARCH */

$searchQuery = "";

if(isset($_GET['search'])){
    $search = trim($_GET['search']);
    $searchQuery = "AND title LIKE '%$search%'";
}

/* FETCH TEMPLATES */

$templates = mysqli_query($conn,
"SELECT * FROM business_templates
WHERE agent_id = $agent_id
$searchQuery
ORDER BY template_id DESC");

?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Templates</title>
<link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

<?php include('navbar.php'); ?>

<div>

<h2>My Business Templates</h2>

<?php if($successMsg) echo "<div style='color:green;'>$successMsg</div>"; ?>
<?php if($errorMsg) echo "<div style='color:red;'>$errorMsg</div>"; ?>

<a href="add_template.php" style="margin:40px;">Upload New Template</a>

  

<form method="get">
<input type="text" name="search" placeholder="Search Template"
value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
<button type="submit">Search</button>
</form>

 

<table border="1" cellpadding="10">

<tr>
<th>ID</th>
<th>Title</th>
<th>Description</th>
<th>File</th>
<th>Date</th>
<th>Actions</th>
</tr>

<?php while($row = mysqli_fetch_assoc($templates)){ ?>

<tr>

<td><?php echo $row['template_id']; ?></td>

<td><?php echo htmlspecialchars($row['title']); ?></td>

<td><?php echo htmlspecialchars($row['description']); ?></td>

<td>
<a href="../uploads/templates/<?php echo $row['file_path']; ?>" target="_blank">
Download
</a>
</td>

<td><?php echo $row['uploaded_at']; ?></td>

<td>

<a href="edit_template.php?id=<?php echo $row['template_id']; ?>">Edit</a> |

<a href="?delete=<?php echo $row['template_id']; ?>"
onclick="return confirm('Delete this template?')">Delete</a>

</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>

<?php mysqli_close($conn); ?>