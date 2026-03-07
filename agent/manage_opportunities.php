<?php
session_start();
include "../config.php";

if (!isset($_SESSION['agent_id'])) {
    header("Location: ../login.php");
    exit();
}

$agent_id = $_SESSION['agent_id'];

$successMsg = "";
$errorMsg = "";

/* DELETE OPPORTUNITY */
if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);

    mysqli_query($conn, "DELETE FROM opportunities 
                        WHERE opportunity_id=$deleteId 
                        AND agent_id=$agent_id");

    $successMsg = "Opportunity deleted successfully.";
}

/* SEARCH */
$searchQuery = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $searchQuery = "AND title LIKE '%$search%'";
}

/* FETCH OPPORTUNITIES */
$opportunities = mysqli_query(
    $conn,
    "SELECT * FROM opportunities
WHERE agent_id = $agent_id
$searchQuery
ORDER BY opportunity_id DESC"
);

?>
<!DOCTYPE html>
<html>

<head>
    <title>Manage Opportunities</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

    <?php include('navbar.php'); ?>

    <div>

        <h2>My Opportunities</h2>

        <?php if ($successMsg)
            echo "<div style='color:green;'>$successMsg</div>"; ?>
        <?php if ($errorMsg)
            echo "<div style='color:red;'>$errorMsg</div>"; ?>

        <a href="add_opportunity.php" style="margin:40px;">Add New Opportunity</a>



        <form method="get">
            <input type="text" name="search" placeholder="Search Opportunity"
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Search</button>
        </form>



        <table border="1" cellpadding="10">

            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Location</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($opportunities)) { ?>

                <tr>

                    <td><?php echo $row['opportunity_id']; ?></td>

                    <td><?php echo htmlspecialchars($row['title']); ?></td>

                    <td><?php echo htmlspecialchars($row['description']); ?></td>

                    <td><?php echo htmlspecialchars($row['location']); ?></td>

                    <td><?php echo $row['created_at']; ?></td>

                    <td>

                        <a href="edit_opportunity.php?id=<?php echo $row['opportunity_id']; ?>">Edit</a> |

                        <a href="?delete=<?php echo $row['opportunity_id']; ?>"
                            onclick="return confirm('Delete this opportunity?')">Delete</a>

                    </td>

                </tr>

            <?php } ?>

        </table>

    </div>

</body>

</html>

<?php mysqli_close($conn); ?>