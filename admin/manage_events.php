<?php
session_start();
include "config.php";

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

$successMsg = "";
$errorMsg = "";

/* DELETE EVENT */
if(isset($_GET['delete'])){
    $deleteId = intval($_GET['delete']);

    if(mysqli_query($conn,"DELETE FROM events WHERE event_id=$deleteId")){
        $successMsg = "Event deleted successfully.";
    } else {
        $errorMsg = "Error deleting event.";
    }
}

/* SEARCH */
$searchQuery = "";

if(isset($_GET['search'])){
    $search = trim($_GET['search']);
    $searchQuery = "WHERE event_id LIKE '%$search%' OR title LIKE '%$search%'";
}

/* FETCH EVENTS */
$events = mysqli_query($conn,"SELECT * FROM events $searchQuery ORDER BY event_id DESC");

if(mysqli_num_rows($events) == 0){
    $errorMsg = "No events found.";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Events</title>
<link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

<?php include('navbar.php'); ?>

<div>

<h2>Manage Events</h2>

<?php if($successMsg) echo "<div style='color:green;'>$successMsg</div>"; ?>
<?php if($errorMsg) echo "<div style='color:red;'>$errorMsg</div>"; ?>

<a href="add_event.php" style="margin:40px;">Add New Event</a>

  

<form method="get">
<input type="text" name="search" placeholder="Search Event"
value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
<button type="submit">Search</button>
</form>

 

<?php if(mysqli_num_rows($events)>0){ ?>

<table border="1" cellpadding="10">

<tr>
<th>ID</th>
<th>Title</th>
<th>Description</th>
<th>Event Date</th>
<th>Location</th>
<th>Actions</th>
</tr>

<?php while($row = mysqli_fetch_assoc($events)){ ?>

<tr>

<td><?php echo $row['event_id']; ?></td>

<td><?php echo htmlspecialchars($row['title']); ?></td>

<td><?php echo htmlspecialchars($row['description']); ?></td>

<td><?php echo $row['event_date']; ?></td>

<td><?php echo htmlspecialchars($row['location']); ?></td>

<td>

<a href="edit_event.php?id=<?php echo $row['event_id']; ?>">Edit</a> |

<a href="?delete=<?php echo $row['event_id']; ?>"
onclick="return confirm('Delete this event?')">Delete</a>

</td>

</tr>

<?php } ?>

</table>

<?php } ?>

</div>

</body>
</html>