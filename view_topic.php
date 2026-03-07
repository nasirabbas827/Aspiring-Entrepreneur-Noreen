<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$topic_id = intval($_GET['id']);

// Fetch topic info
$topic_res = mysqli_query($conn, "SELECT ft.*, u.username FROM forum_topics ft 
                                  LEFT JOIN users u ON ft.user_id = u.id
                                  WHERE ft.topic_id=$topic_id");
$topic = mysqli_fetch_assoc($topic_res);

if(!$topic) {
    die("<div class='container'><h2>Topic not found.</h2></div>");
}

// Handle new reply
$successMsg = "";
if(isset($_POST['reply_submit'])){
    $message = trim($_POST['message']);
    if($message != ""){
        $stmt = mysqli_prepare($conn, "INSERT INTO forum_replies (topic_id, user_id, message) VALUES (?,?,?)");
        mysqli_stmt_bind_param($stmt, "iis", $topic_id, $user_id, $message);
        mysqli_stmt_execute($stmt);
        $successMsg = "Reply posted successfully.";
    }
}

// Fetch all replies
$replies = mysqli_query($conn, "SELECT fr.*, u.username FROM forum_replies fr 
                                LEFT JOIN users u ON fr.user_id = u.id
                                WHERE fr.topic_id=$topic_id
                                ORDER BY fr.created_at ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($topic['title']); ?> | Forum</title>
    <link rel="stylesheet" href="./CSS/style.css">
    <style>
                                .container{
            margin: 20px;
        }
        /* --- FORUM THREAD CSS --- */
        .topic-header-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            border-left: 6px solid var(--primary-color);
            margin-bottom: 40px;
        }

        .topic-header-card h2 { text-align: left; margin-top: 0; color: var(--text-primary); }
        
        .author-meta { font-size: 0.85rem; color: #666; margin-bottom: 20px; display: block; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .author-meta strong { color: var(--primary-color); }

        .topic-body { font-size: 1.1rem; line-height: 1.7; color: var(--text-secondary); }

        /* Replies Section */
        .replies-container { margin-left: 20px; border-left: 2px solid var(--bg-secondary); padding-left: 20px; }
        
        .reply-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 20px;
            border: 1px solid var(--border-color);
            position: relative;
        }

        .reply-author { font-weight: 700; font-size: 0.9rem; color: var(--text-primary); margin-bottom: 8px; display: block; }
        .reply-date { font-size: 0.75rem; color: #999; float: right; }
        .reply-text { font-size: 0.95rem; color: var(--text-secondary); line-height: 1.5; }

        /* Reply Form */
        .reply-form-section { background: #f8fafc; padding: 25px; border-radius: 12px; margin-top: 40px; border: 1px solid var(--border-color); }
        .reply-form-section h4 { margin-top: 0; text-align: left; }

        @media (max-width: 768px) {
            .replies-container { margin-left: 0; padding-left: 0; border-left: none; }
        }
    </style>
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container">
    
    <div class="topic-header-card">
        <span class="author-meta">
            Posted by <strong><?php echo htmlspecialchars($topic['username']); ?></strong> 
            on <?php echo date('M d, Y', strtotime($topic['created_at'])); ?>
        </span>
        <h2><?php echo htmlspecialchars($topic['title']); ?></h2>
        <div class="topic-body">
            <?php echo nl2br(htmlspecialchars($topic['description'])); ?>
        </div>
    </div>

    <?php if($successMsg): ?>
        <div class="alert alert-success"><?php echo $successMsg; ?></div>
    <?php endif; ?>

    <div class="replies-container">
        <h3>Discussion (<?php echo mysqli_num_rows($replies); ?>)</h3>
        
        <?php if(mysqli_num_rows($replies) > 0): ?>
            <?php while($r = mysqli_fetch_assoc($replies)): ?>
                <div class="reply-card">
                    <span class="reply-date"><?php echo date('M d, H:i', strtotime($r['created_at'])); ?></span>
                    <span class="reply-author">@<?php echo htmlspecialchars($r['username']); ?></span>
                    <div class="reply-text">
                        <?php echo nl2br(htmlspecialchars($r['message'])); ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color: #999; font-style: italic;">No replies yet. Start the conversation!</p>
        <?php endif; ?>
    </div>

    <div class="reply-form-section">
        <h4>Add a Reply</h4>
        <form method="post">
            <textarea name="message" rows="4" placeholder="Share your thoughts or ask a question..." required></textarea> 
            <button type="submit" name="reply_submit" style="width: auto; padding: 12px 30px;">Post My Reply</button>
        </form>
    </div>

    <div style="margin-top: 20px; text-align: center;">
        <a href="forum.php" style="color: var(--text-secondary); text-decoration: none;">&larr; Back to Forum Topics</a>
    </div>

</div>
</body>
</html>
<?php mysqli_close($conn); ?>