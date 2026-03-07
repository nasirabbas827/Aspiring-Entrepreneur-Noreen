<?php
session_start();
include "config.php";

// 1. Stats Aggregation
$total_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM users"))['c'];
$pending_agents = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM agents WHERE approval_status='pending'"))['c'];
$total_ideas = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM business_ideas"))['c'];
$total_events = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM events"))['c'];

// 2. Data Queries
$user_list = mysqli_query($conn, "SELECT username, email, phone, created_at FROM users ORDER BY created_at DESC LIMIT 5");
$agent_list = mysqli_query($conn, "SELECT full_name, expertise_domain, approval_status FROM agents LIMIT 5");
$idea_list = mysqli_query($conn, "SELECT bi.title, c.category_name, bi.investment_required 
                                  FROM business_ideas bi 
                                  JOIN categories c ON bi.category_id = c.category_id LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Overview</title>
    <link rel="stylesheet" href="../CSS/style.css">
    
    <style>

        .dashboard-header {
            margin: 40px 0 20px 0;
            text-align: left;
            padding: 0 5%;
        }

        .dashboard-header h1 {
            text-align: left; /* Overriding your global center align */
            margin-bottom: 5px;
        }

        /* Stats Row */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            width: 90%;
            margin: 0 auto 40px auto;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            border-top: 4px solid var(--primary-color);
            transition: transform 0.2s;
        }

        .stat-card:hover { transform: translateY(-5px); }

        .stat-card h2 { margin: 0; font-size: 2.2rem; color: var(--text-primary); text-align: left; }
        .stat-card p { margin: 0; color: var(--text-secondary); font-weight: 600; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; }

        /* Tables Grid System */
        .data-grid {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Two columns for wide screens */
            gap: 30px;
            width: 90%;
            margin: 0 auto 50px auto;
        }

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }

        .table-container h3 {
            text-align: left;
            color: var(--text-primary);
            font-size: 1.2rem;
            border-bottom: 2px solid var(--bg-secondary);
            padding-bottom: 10px;
            margin-top: 0;
        }

        /* Specific Table Overrides for Dashboard */
        table { width: 100%; margin: 10px 0; box-shadow: none; border-radius: 0; }
        th { font-size: 0.85rem; padding: 10px; }
        td { font-size: 0.85rem; padding: 10px; }

        /* Badges */
        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-approved { background: #dcfce7; color: #166534; }

        @media (max-width: 1024px) {
            .data-grid { grid-template-columns: 1fr; } /* Stack tables on mobile */
        }
    </style>
</head>
<body>

<?php include('navbar.php'); ?>

<div class="dashboard-header">
    <h1>Platform Overview</h1>
    <p>Welcome back, Admin. Here is what's happening today.</p>
</div>

<div class="stats-row">
    <div class="stat-card">
        <p>Total Registered Users</p>
        <h2><?php echo $total_users; ?></h2>
    </div>
    <div class="stat-card" style="border-top-color: var(--secondary-color);">
        <p>Pending Agent Approvals</p>
        <h2><?php echo $pending_agents; ?></h2>
    </div>
    <div class="stat-card">
        <p>Business Ideas Published</p>
        <h2><?php echo $total_ideas; ?></h2>
    </div>
    <div class="stat-card">
        <p>Upcoming Events</p>
        <h2><?php echo $total_events; ?></h2>
    </div>
</div>

<div class="data-grid">

    <div class="table-container">
        <h3>Recent Users</h3>
        <table>
            <thead>
                <tr><th>Username</th><th>Email</th><th>Joined</th></tr>
            </thead>
            <tbody>
                <?php while($u = mysqli_fetch_assoc($user_list)): ?>
                <tr>
                    <td><?php echo $u['username']; ?></td>
                    <td><?php echo $u['email']; ?></td>
                    <td><?php echo date('M d', strtotime($u['created_at'])); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="table-container">
        <h3>Mentor/Agent Status</h3>
        <table>
            <thead>
                <tr><th>Full Name</th><th>Expertise</th><th>Status</th></tr>
            </thead>
            <tbody>
                <?php while($a = mysqli_fetch_assoc($agent_list)): ?>
                <tr>
                    <td><?php echo $a['full_name']; ?></td>
                    <td><?php echo $a['expertise_domain']; ?></td>
                    <td>
                        <span class="status-badge <?php echo ($a['approval_status'] == 'pending') ? 'status-pending' : 'status-approved'; ?>">
                            <?php echo $a['approval_status']; ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="table-container">
        <h3>Latest Business Ideas</h3>
        <table>
            <thead>
                <tr><th>Idea Title</th><th>Category</th><th>Investment</th></tr>
            </thead>
            <tbody>
                <?php while($i = mysqli_fetch_assoc($idea_list)): ?>
                <tr>
                    <td><?php echo $i['title']; ?></td>
                    <td><?php echo $i['category_name']; ?></td>
                    <td>RS <?php echo number_format($i['investment_required']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="table-container">
        <h3>Administrative Quick Links</h3>
        <div style="padding: 10px;">
            <button onclick="location.href='add_event.php'" style="width:100%; margin-bottom:10px;">Create New Event</button>
            <button onclick="location.href='manage_categories.php'" style="width:100%; background: var(--secondary-color); color: #000;">Manage Categories</button>
        </div>
    </div>

</div>

</body>
</html>