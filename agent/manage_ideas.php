<?php
session_start();
include "config.php";

if (!isset($_SESSION['agent_id'])) {
    header("Location: ../login.php");
    exit();
}

$agent_id = $_SESSION['agent_id'];

$successMsg = "";
$errorMsg = "";

/* DELETE IDEA */
if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);

    mysqli_query($conn, "DELETE FROM business_ideas 
                        WHERE idea_id=$deleteId 
                        AND agent_id=$agent_id");

    $successMsg = "Idea deleted successfully.";
}

/* SEARCH */
$searchQuery = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $searchQuery = "AND title LIKE '%$search%'";
}

/* FETCH IDEAS */
$ideas = mysqli_query(
    $conn,
    "SELECT b.*, c.category_name 
FROM business_ideas b
JOIN categories c ON b.category_id = c.category_id
WHERE b.agent_id = $agent_id
$searchQuery
ORDER BY idea_id DESC"
);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Business Ideas</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

    <?php include('navbar.php'); ?>

    <div>

        <h2>My Business Ideas</h2>

        <?php if ($successMsg)
            echo "<div style='color:green;'>$successMsg</div>"; ?>
        <?php if ($errorMsg)
            echo "<div style='color:red;'>$errorMsg</div>"; ?>

        <a href="add_idea.php" style="margin:40px;">Add New Idea</a>



        <form method="get">
            <input type="text" name="search" placeholder="Search Idea"
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Search</button>
        </form>



        <table border="1" cellpadding="10">

            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Category</th>
                <th>Investment</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($ideas)) { ?>

                <tr>

                    <td><?php echo $row['idea_id']; ?></td>

                    <td><?php echo htmlspecialchars($row['title']); ?></td>

                    <td><?php echo htmlspecialchars($row['category_name']); ?></td>

                    <td><?php echo number_format($row['investment_required'], 2); ?></td>

                    <td><?php echo $row['created_at']; ?></td>

                    <td>

                        <a href="edit_idea.php?id=<?php echo $row['idea_id']; ?>">Edit</a> |

                        <a href="?delete=<?php echo $row['idea_id']; ?>"
                            onclick="return confirm('Delete this idea?')">Delete</a>

                    </td>

                </tr>

            <?php } ?>

        </table>

    </div>

</body>

</html>

<?php mysqli_close($conn); ?>