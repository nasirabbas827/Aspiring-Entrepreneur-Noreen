<?php
session_start();
include "config.php";

// Optional: Check if user is logged in
$user_logged_in = isset($_SESSION['user_id']);

// Fetch Tutorials
$tutorials = mysqli_query($conn, "SELECT * FROM tutorials ORDER BY created_at DESC");

// Fetch Events
$events = mysqli_query($conn, "SELECT * FROM events ORDER BY event_date ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tutorials & Events</title>
    <link rel="stylesheet" href="./CSS/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container">
    <h2>Tutorials</h2>
    <?php if(mysqli_num_rows($tutorials) > 0){ ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Video</th>
                <th>Created At</th>
            </tr>
            <?php while($tut = mysqli_fetch_assoc($tutorials)){ ?>
            <tr>
                <td><?php echo htmlspecialchars($tut['title']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($tut['description'])); ?></td>
                <td>
                    <?php if($tut['video_url']){ ?>
                        <a href="<?php echo htmlspecialchars($tut['video_url']); ?>" target="_blank">Watch Video</a>
                    <?php } else { echo "No Video"; } ?>
                </td>
                <td><?php echo $tut['created_at']; ?></td>
            </tr>
            <?php } ?>
        </table>
    <?php } else { echo "<p>No tutorials available.</p>"; } ?>

    <hr>

    <h2>Events</h2>
    <?php if(mysqli_num_rows($events) > 0){ ?>
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Date</th>
                <th>Location</th>
            </tr>
            <?php while($ev = mysqli_fetch_assoc($events)){ ?>
            <tr>
                <td><?php echo htmlspecialchars($ev['title']); ?></td>
                <td><?php echo nl2br(htmlspecialchars($ev['description'])); ?></td>
                <td><?php echo $ev['event_date']; ?></td>
                <td><?php echo htmlspecialchars($ev['location']); ?></td>
            </tr>
            <?php } ?>
        </table>
    <?php } else { echo "<p>No upcoming events.</p>"; } ?>

</div>
</body>
</html>
<?php mysqli_close($conn); ?>