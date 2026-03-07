<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Security: Check for ID and validate
if(!isset($_GET['id'])) {
    header("Location: opportunities.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch opportunity details with agent info
$query = "SELECT o.*, a.full_name AS agent_name, a.expertise_domain, a.bio, a.agent_id, a.email
          FROM opportunities o
          JOIN agents a ON o.agent_id = a.agent_id
          WHERE o.opportunity_id = $id";

$result = mysqli_query($conn, $query);
$opportunity = mysqli_fetch_assoc($result);

if(!$opportunity){
    die("<div class='container' style='margin-top:50px;'><h2>Opportunity not found.</h2><a href='opportunities.php'>Back to List</a></div>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($opportunity['title']); ?> | Opportunity</title>
    <link rel="stylesheet" href="./CSS/style.css">
    <style>
                .container{
            margin: 20px;
        }
        /* --- OPPORTUNITY PAGE CSS --- */
        .opp-wrapper {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-top: 40px;
        }

        .opp-main-card, .agent-sidebar {
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
        }

        .opp-header {
            border-bottom: 2px solid var(--bg-secondary);
            margin-bottom: 25px;
            padding-bottom: 20px;
        }

        .opp-header h2 { text-align: left; margin: 0; color: var(--text-primary); font-size: 1.8rem; }

        .opp-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .badge-info {
            background: var(--bg-secondary);
            color: var(--text-primary);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .badge-location { background: #e0f2fe; color: #0369a1; } /* Light Blue */

        .description-box {
            line-height: 1.7;
            color: var(--text-secondary);
            font-size: 1.05rem;
            margin-bottom: 30px;
        }

        /* Agent Sidebar Styles */
        .agent-sidebar h3 { text-align: left; font-size: 1.2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-top: 0; }
        
        .agent-id-card {
            text-align: center;
            padding: 10px 0;
            margin-bottom: 15px;
        }

        .agent-name { font-size: 1.25rem; font-weight: 800; color: var(--primary-color); display: block; }
        .agent-domain { font-size: 0.85rem; color: #666; text-transform: uppercase; letter-spacing: 0.5px; }

        .sidebar-btn {
            display: block;
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            margin-top: 10px;
            transition: 0.3s;
        }

        .btn-primary-view { background: var(--primary-color); color: white; }
        .btn-secondary-msg { background: var(--bg-secondary); color: var(--text-primary); border: 1px solid var(--border-color); }
        
        .sidebar-btn:hover { opacity: 0.9; transform: translateY(-2px); }

        .post-date { font-size: 0.8rem; color: #999; margin-top: 20px; display: block; text-align: right; }

        @media (max-width: 900px) {
            .opp-wrapper { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container">
    <div class="opp-wrapper">
        
        <main class="opp-main-card">
            <div class="opp-header">
                <h2><?php echo htmlspecialchars($opportunity['title']); ?></h2>
            </div>

            <div class="opp-meta">
                <div class="badge-info badge-location">
                    📍 <?php echo htmlspecialchars($opportunity['location']); ?>
                </div>
                <div class="badge-info">
                    📅 Posted: <?php echo date('M d, Y', strtotime($opportunity['created_at'])); ?>
                </div>
            </div>

            <h3>Opportunity Details</h3>
            <div class="description-box">
                <?php echo nl2br(htmlspecialchars($opportunity['description'])); ?>
            </div>

            <span class="post-date">Ref ID: #OPP-<?php echo $opportunity['opportunity_id']; ?></span>
        </main>

        <aside class="agent-sidebar">
            <h3>Posted By</h3>
            
            <div class="agent-id-card">
                <span class="agent-name"><?php echo htmlspecialchars($opportunity['agent_name']); ?></span>
                <span class="agent-domain"><?php echo htmlspecialchars($opportunity['expertise_domain']); ?></span>
            </div>

            <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 20px;">
                <?php echo nl2br(htmlspecialchars($opportunity['bio'])); ?>
            </p>

            <a href="view_agent.php?id=<?php echo $opportunity['agent_id']; ?>" class="sidebar-btn btn-primary-view">
                View Full Profile
            </a>

            <a href="view_agent.php?id=<?php echo $opportunity['agent_id']; ?>#chat" class="sidebar-btn btn-secondary-msg">
                Message Agent
            </a>
            
            <p style="text-align: center; font-size: 0.75rem; color: #aaa; margin-top: 15px;">
                Always verify details before making financial commitments.
            </p>
        </aside>

    </div>
</div>

</body>
</html>
<?php mysqli_close($conn); ?>