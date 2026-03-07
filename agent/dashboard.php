<?php
session_start();
include "config.php";

if (!isset($_SESSION['agent_id'])) {
    header("Location: ../login.php");
    exit();
}

$agent_id = $_SESSION['agent_id'];

// --------------------------------------------------
// Fetch Statistics
// --------------------------------------------------

// Total Business Ideas
$ideas_count = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as cnt FROM business_ideas WHERE agent_id = $agent_id"))['cnt'];

// Total Templates
$templates_count = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as cnt FROM business_templates WHERE agent_id = $agent_id"))['cnt'];

// Total Opportunities
$opportunities_count = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as cnt FROM opportunities WHERE agent_id = $agent_id"))['cnt'];

// Pending Mentorship Requests
$pending_requests = mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COUNT(*) as cnt FROM mentorship_requests 
     WHERE agent_id = $agent_id AND status = 'pending'"))['cnt'];

// Received Feedback (average rating)
$feedback_result = mysqli_query($conn, 
    "SELECT AVG(rating) as avg_rating, COUNT(*) as count 
     FROM feedback WHERE agent_id = $agent_id");
$feedback = mysqli_fetch_assoc($feedback_result);
$avg_rating = $feedback['avg_rating'] ? round($feedback['avg_rating'], 1) : '—';
$feedback_count = $feedback['count'];

// Recent Mentorship Requests (last 5)
$recent_requests = mysqli_query($conn, 
    "SELECT mr.*, u.username 
     FROM mentorship_requests mr 
     JOIN users u ON mr.user_id = u.id 
     WHERE mr.agent_id = $agent_id 
     ORDER BY mr.request_date DESC LIMIT 5");

// Recent Private Messages (last 5)
$recent_messages = mysqli_query($conn, 
    "SELECT m.*, u.username AS sender_name 
     FROM messages m 
     JOIN users u ON m.sender_id = u.id 
     WHERE m.receiver_id = $agent_id 
     ORDER BY m.sent_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Dashboard - Entrepreneur Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="../CSS/style.css">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --light: #f8fafc;
            --gray: #64748b;
            --dark: #0f172a;
            --border: #e2e8f0;
            --shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .container { max-width: 1280px; margin: 0 auto; padding: 24px; }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .page-header h1 {
            font-size: 1.9rem;
            margin: 0;
            color: var(--dark);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: transform 0.2s ease;
        }

        .stat-card:hover { transform: translateY(-6px); }

        .stat-icon {
            font-size: 2.2rem;
            margin-bottom: 12px;
            color: var(--primary);
        }

        .stat-value {
            font-size: 2.1rem;
            font-weight: 700;
            color: var(--dark);
            margin: 8px 0;
        }

        .stat-label {
            color: var(--gray);
            font-size: 0.95rem;
        }

        .section-title {
            font-size: 1.4rem;
            margin: 32px 0 16px;
            color: var(--dark);
            border-bottom: 2px solid var(--primary);
            display: inline-block;
            padding-bottom: 8px;
        }

        .request-card, .message-card {
            background: white;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 16px;
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary);
        }

        .request-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .requester-name {
            font-weight: 600;
            color: var(--primary);
        }

        .request-date {
            font-size: 0.85rem;
            color: var(--gray);
        }

        .request-text {
            color: #475569;
            margin: 12px 0;
            font-size: 0.97rem;
        }

        .action-buttons a {
            margin-left: 12px;
            padding: 6px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .btn-accept { background: var(--success); color: white; }
        .btn-reject  { background: var(--danger);  color: white; }

        .quick-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 24px;
        }

        .action-btn {
            background: var(--primary);
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
        }

        .action-btn:hover { background: var(--primary-dark); }

        @media (max-width: 768px) {
            .stats-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">

    <div class="page-header">
        <h1>Agent Dashboard</h1>
        <span style="color: var(--gray);">Welcome back • <?php echo date('M d, Y'); ?></span>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <i class="fas fa-lightbulb stat-icon"></i>
            <div class="stat-value"><?php echo $ideas_count; ?></div>
            <div class="stat-label">Business Ideas Shared</div>
        </div>
        <div class="stat-card">
            <i class="fas fa-file-alt stat-icon"></i>
            <div class="stat-value"><?php echo $templates_count; ?></div>
            <div class="stat-label">Business Templates</div>
        </div>
        <div class="stat-card">
            <i class="fas fa-handshake stat-icon"></i>
            <div class="stat-value"><?php echo $opportunities_count; ?></div>
            <div class="stat-label">Opportunities Posted</div>
        </div>
        <div class="stat-card">
            <i class="fas fa-user-check stat-icon"></i>
            <div class="stat-value"><?php echo $pending_requests; ?></div>
            <div class="stat-label">Pending Requests</div>
        </div>
        <div class="stat-card">
            <i class="fas fa-star stat-icon"></i>
            <div class="stat-value"><?php echo $avg_rating; ?> <small>(<?php echo $feedback_count; ?>)</small></div>
            <div class="stat-label">Average Rating</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <h2 class="section-title">Quick Actions</h2>
    <div class="quick-actions">
        <a href="add_idea.php" class="action-btn"><i class="fas fa-plus"></i> Add Business Idea</a>
        <a href="add_template.php" class="action-btn"><i class="fas fa-file-upload"></i> Upload Template</a>
        <a href="add_opportunity.php" class="action-btn"><i class="fas fa-bullhorn"></i> Post Opportunity</a>
        <a href="request&messages.php" class="action-btn"><i class="fas fa-envelope-open-text"></i> View All Requests</a>
    </div>

    <!-- Recent Mentorship Requests -->
    <h2 class="section-title">Recent Mentorship Requests</h2>

    <?php if (mysqli_num_rows($recent_requests) > 0): ?>
        <?php while ($req = mysqli_fetch_assoc($recent_requests)): ?>
            <div class="request-card">
                <div class="request-header">
                    <span class="requester-name"><?php echo htmlspecialchars($req['username']); ?></span>
                    <span class="request-date"><?php echo date('M d, Y • h:i A', strtotime($req['request_date'])); ?></span>
                </div>
                <div class="request-text">
                    <?php echo nl2br(htmlspecialchars(substr($req['message'], 0, 220))); ?>...
                </div>
                <?php if ($req['status'] === 'pending'): ?>
                    <div class="action-buttons">
                        <a href="manage_request.php?action=accept&rid=<?php echo $req['request_id']; ?>" class="btn-accept">Accept</a>
                        <a href="manage_request.php?action=reject&rid=<?php echo $req['request_id']; ?>" class="btn-reject">Reject</a>
                    </div>
                <?php else: ?>
                    <span style="color: var(--gray); font-style: italic;">Status: <?php echo ucfirst($req['status']); ?></span>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="color: var(--gray); text-align: center; padding: 30px 0;">
            <i class="far fa-envelope" style="font-size: 2.5rem; opacity: 0.6; display: block; margin-bottom: 12px;"></i>
            No recent mentorship requests.
        </p>
    <?php endif; ?>

    <!-- Recent Messages (Preview) -->
    <h2 class="section-title">Recent Messages</h2>

    <?php if (mysqli_num_rows($recent_messages) > 0): ?>
        <?php while ($msg = mysqli_fetch_assoc($recent_messages)): ?>
            <div class="message-card">
                <strong>From: <?php echo htmlspecialchars($msg['sender_name']); ?></strong><br>
                <small style="color: var(--gray);"><?php echo date('M d, h:i A', strtotime($msg['sent_at'])); ?></small>
                <p style="margin-top: 10px;"><?php echo nl2br(htmlspecialchars(substr($msg['message'], 0, 180))); ?>...</p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="color: var(--gray); text-align: center; padding: 30px 0;">
            <i class="far fa-comment-dots" style="font-size: 2.5rem; opacity: 0.6; display: block; margin-bottom: 12px;"></i>
            No recent messages.
        </p>
    <?php endif; ?>

</div>

</body>
</html>

<?php mysqli_close($conn); ?>