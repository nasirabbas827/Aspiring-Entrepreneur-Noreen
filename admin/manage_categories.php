<?php
session_start();
include "config.php";

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

$successMsg = "";
$errorMsg = "";

/* DELETE CATEGORY */
if (isset($_GET['delete'])) {

    $deleteId = intval($_GET['delete']);

    if (mysqli_query($conn, "DELETE FROM categories WHERE category_id=$deleteId")) {
        $successMsg = "Category deleted successfully.";
    } else {
        $errorMsg = "Error deleting category.";
    }
}

/* SEARCH */

$searchQuery = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $searchQuery = "WHERE category_id LIKE '%$search%' OR category_name LIKE '%$search%'";
}

/* FETCH DATA */

$categories = mysqli_query($conn, "SELECT * FROM categories $searchQuery ORDER BY category_id DESC");

if (mysqli_num_rows($categories) == 0) {
    $errorMsg = "No categories found.";
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Categories</title>
    <link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

    <?php include('navbar.php'); ?>

    <div>

        <h2>Manage Business Categories</h2>

        <?php if ($successMsg)
            echo "<div style='color:green; '>$successMsg</div>"; ?>
        <?php if ($errorMsg)
            echo "<div style='color:red; '>$errorMsg</div>"; ?>

        <a href="add_category.php" style="margin:40px;">Add New Category</a>



        <form method="get">
            <input type="text" name="search" placeholder="Search Category"
                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Search</button>
        </form>



        <?php if (mysqli_num_rows($categories) > 0) { ?>

            <table border="1" cellpadding="10">

                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>

                <?php while ($cat = mysqli_fetch_assoc($categories)) { ?>

                    <tr>

                        <td><?php echo $cat['category_id']; ?></td>

                        <td><?php echo htmlspecialchars($cat['category_name']); ?></td>

                        <td>

                            <a href="edit_category.php?id=<?php echo $cat['category_id']; ?>">Edit</a> |

                            <a href="?delete=<?php echo $cat['category_id']; ?>"
                                onclick="return confirm('Delete this category?')">Delete</a>

                        </td>

                    </tr>

                <?php } ?>

            </table>

        <?php } ?>

    </div>

</body>

</html>