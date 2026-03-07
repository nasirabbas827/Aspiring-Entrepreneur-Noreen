<?php
session_start();
include "config.php";

// Check admin login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

$successMsg = $errorMsg = "";

/* ---------- UPDATE AGENT STATUS ---------- */
if (isset($_GET['agent_id']) && isset($_GET['status'])) {

    $agent_id = intval($_GET['agent_id']);
    $status = $_GET['status'];

    $allowed = ['approved','rejected','pending'];

    if (in_array($status, $allowed)) {

        $stmt = mysqli_prepare($conn, "UPDATE agents SET approval_status=? WHERE agent_id=?");
        mysqli_stmt_bind_param($stmt, "si", $status, $agent_id);

        if(mysqli_stmt_execute($stmt)){
            $successMsg = "Agent status updated successfully.";
        } else {
            $errorMsg = "Failed to update status.";
        }

        mysqli_stmt_close($stmt);
    }
}

/* ---------- SEARCH ---------- */

$searchQueryUsers = "";
$searchQueryAgents = "";

if(isset($_GET['search'])){
    $search = trim($_GET['search']);

    $searchQueryUsers = "WHERE id LIKE '%$search%' 
                         OR username LIKE '%$search%' 
                         OR email LIKE '%$search%'";

    $searchQueryAgents = "WHERE agent_id LIKE '%$search%' 
                          OR full_name LIKE '%$search%' 
                          OR email LIKE '%$search%'";
}

/* ---------- FETCH USERS ---------- */

$users = mysqli_query($conn,"SELECT * FROM users $searchQueryUsers ORDER BY id DESC");

/* ---------- FETCH AGENTS ---------- */

$agents = mysqli_query($conn,"SELECT * FROM agents $searchQueryAgents ORDER BY agent_id DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Manage Users & Agents</title>
<link rel="stylesheet" href="../CSS/style.css">
</head>

<body>

<?php include('navbar.php'); ?>

<div>

<h2>Manage Users</h2>

<?php if ($successMsg) echo "<div style='color:green;'>$successMsg</div>"; ?>
<?php if ($errorMsg) echo "<div style='color:red;'>$errorMsg</div>"; ?>

<form method="get">
<input type="text" name="search" placeholder="Search users or agents"
value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
<button type="submit">Search</button>
</form>


<!-- ================= USERS TABLE ================= -->

<h3>Registered Users</h3>

<?php if(mysqli_num_rows($users) > 0){ ?>

<table border="1" cellpadding="10">

<tr>
<th>ID</th>
<th>Username</th>
<th>Email</th>
<th>Phone</th>
<th>CNIC</th>
<th>Created</th>
</tr>

<?php while($user = mysqli_fetch_assoc($users)){ ?>

<tr>

<td><?php echo $user['id']; ?></td>
<td><?php echo htmlspecialchars($user['username']); ?></td>
<td><?php echo htmlspecialchars($user['email']); ?></td>
<td><?php echo htmlspecialchars($user['phone']); ?></td>
<td><?php echo htmlspecialchars($user['cnic']); ?></td>
<td><?php echo $user['created_at']; ?></td>

</tr>

<?php } ?>

</table>

<?php } else { ?>

<p>No users found.</p>

<?php } ?>


<!-- ================= AGENTS TABLE ================= -->

<h3>Agents / Mentors</h3>

<?php if(mysqli_num_rows($agents) > 0){ ?>

<table border="1" cellpadding="10">

<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Phone</th>
<th>Expertise</th>
<th>Experience</th>
<th>Status</th>
<th>Actions</th>
</tr>

<?php while($agent = mysqli_fetch_assoc($agents)){ ?>

<tr>

<td><?php echo $agent['agent_id']; ?></td>

<td><?php echo htmlspecialchars($agent['full_name']); ?></td>

<td><?php echo htmlspecialchars($agent['email']); ?></td>

<td><?php echo htmlspecialchars($agent['phone']); ?></td>

<td><?php echo htmlspecialchars($agent['expertise_domain']); ?></td>

<td><?php echo $agent['experience_years']; ?> Years</td>

<td><?php echo $agent['approval_status']; ?></td>

<td>

<a href="?agent_id=<?php echo $agent['agent_id']; ?>&status=approved">Approve</a> |

<a href="?agent_id=<?php echo $agent['agent_id']; ?>&status=rejected">Reject</a> |

<a href="?agent_id=<?php echo $agent['agent_id']; ?>&status=pending">Pending</a>

</td>

</tr>

<?php } ?>

</table>

<?php } else { ?>

<p>No agents found.</p>

<?php } ?>

</div>

</body>
</html>