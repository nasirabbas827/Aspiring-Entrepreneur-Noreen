<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

// Security: Ensure ID is present and numeric
if(!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = intval($_GET['id']);

$query = "SELECT b.*, c.category_name, a.full_name AS agent_name, a.expertise_domain, a.bio, a.agent_id
          FROM business_ideas b
          JOIN categories c ON b.category_id=c.category_id
          JOIN agents a ON b.agent_id=a.agent_id
          WHERE b.idea_id=$id";

$result = mysqli_query($conn, $query);
$idea = mysqli_fetch_assoc($result);

if(!$idea) {
    die("<div class='container'><h2>Idea not found.</h2><a href='dashboard.php'>Back to Dashboard</a></div>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($idea['title']); ?> | Details</title>
    <link rel="stylesheet" href="./CSS/style.css">
    <style>
        .container{
            margin: 20px;
        }
        /* --- IN-PAGE DETAILS CSS --- */
        .details-wrapper {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-top: 40px;
        }

        .idea-card, .agent-sidebar {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
        }

        .idea-header {
            border-bottom: 2px solid var(--bg-secondary);
            margin-bottom: 20px;
            padding-bottom: 15px;
        }

        .idea-header h2 { text-align: left; margin: 0; color: var(--text-primary); }

        .meta-info {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .meta-item {
            background: var(--bg-secondary);
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .meta-item strong { color: var(--primary-color); display: block; font-size: 0.75rem; text-transform: uppercase; }

        .description-text {
            line-height: 1.8;
            color: var(--text-secondary);
            font-size: 1.05rem;
        }

        /* Agent Sidebar */
        .agent-sidebar h3 { text-align: left; margin-top: 0; border-bottom: 1px solid var(--border-color); padding-bottom: 10px; }
        
        .agent-mini-profile { text-align: center; padding: 10px 0; }
        .agent-name { font-size: 1.2rem; font-weight: 700; color: var(--primary-color); display: block; }
        .agent-expertise { font-size: 0.85rem; color: var(--text-secondary); font-style: italic; }

        .btn-profile {
            display: block;
            text-align: center;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            padding: 12px;
            border-radius: 6px;
            margin-top: 20px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-profile:hover { background: var(--primary-hover); transform: translateY(-2px); }

        .investment-highlight {
            font-size: 1.5rem;
            color: #166534;
            font-weight: 800;
        }

        @media (max-width: 900px) {
            .details-wrapper { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container">
    <div class="details-wrapper">
        
        <div class="idea-card">
            <div class="idea-header">
                <h2><?php echo htmlspecialchars($idea['title']); ?></h2>
            </div>

            <div class="meta-info">
                <div class="meta-item">
                    <strong>Category</strong>
                    <?php echo htmlspecialchars($idea['category_name']); ?>
                </div>
                <div class="meta-item">
                    <strong>Investment Required</strong>
                    <span class="investment-highlight">PKR <?php echo number_format($idea['investment_required']); ?></span>
                </div>
            </div>

            <h3>Project Overview</h3>
            <div class="description-text">
                <?php echo nl2br(htmlspecialchars($idea['description'])); ?>
            </div>
        </div>

        <aside class="agent-sidebar">
            <h3>Verified Mentor</h3>
            <div class="agent-mini-profile">
                <span class="agent-name"><?php echo htmlspecialchars($idea['agent_name']); ?></span>
                <span class="agent-expertise"><?php echo htmlspecialchars($idea['expertise_domain']); ?></span>
            </div>
            
            <p style="font-size: 0.9rem; margin-top: 15px; color: var(--text-secondary);">
                <?php echo nl2br(htmlspecialchars($idea['bio'])); ?>
            </p>

            <a href="view_agent.php?id=<?php echo $idea['agent_id']; ?>" class="btn-profile">View Full Profile</a>
        </aside>

    </div>
</div>

</body>
</html>