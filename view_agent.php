<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$agent_id = intval($_GET['id']);

// Fetch agent info
$result = mysqli_query($conn,"SELECT * FROM agents WHERE agent_id=$agent_id");
$agent = mysqli_fetch_assoc($result);

if(!$agent) {
    die("<div class='container'><h2>Agent not found.</h2></div>");
}

// Success/Error Message Handlers
$successMsg = "";

// 1. Handle Mentorship Request
if(isset($_POST['mentorship_submit'])){
    $message = trim($_POST['message']);
    if($message != ""){
        $stmt = mysqli_prepare($conn,"INSERT INTO mentorship_requests (user_id, agent_id, message) VALUES (?,?,?)");
        mysqli_stmt_bind_param($stmt,"iis",$user_id,$agent_id,$message);
        mysqli_stmt_execute($stmt);
        $successMsg = "Mentorship request sent successfully.";
    }
}

// 2. Handle Private Message
if(isset($_POST['message_submit'])){
    $msg_text = trim($_POST['message_text']);
    if($msg_text != ""){
        $stmt = mysqli_prepare($conn,"INSERT INTO messages (sender_id, receiver_id, message) VALUES (?,?,?)");
        mysqli_stmt_bind_param($stmt,"iis",$user_id,$agent_id,$msg_text);
        mysqli_stmt_execute($stmt);
        $successMsg = "Message sent successfully.";
    }
}

// 3. Handle Feedback
if(isset($_POST['feedback_submit'])){
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);
    if($rating > 0 && $rating <= 5 && $comment != ""){
        $stmt = mysqli_prepare($conn,"INSERT INTO feedback (user_id, agent_id, rating, comment) VALUES (?,?,?,?)");
        mysqli_stmt_bind_param($stmt,"iiis",$user_id,$agent_id,$rating,$comment);
        mysqli_stmt_execute($stmt);
        $successMsg = "Feedback submitted successfully.";
    }
}

// Data Fetching for Display
$requests = mysqli_query($conn,"SELECT * FROM mentorship_requests WHERE user_id=$user_id AND agent_id=$agent_id ORDER BY request_date DESC");
$messages = mysqli_query($conn,"SELECT m.* FROM messages m WHERE (m.sender_id=$user_id AND m.receiver_id=$agent_id) OR (m.sender_id=$agent_id AND m.receiver_id=$user_id) ORDER BY m.sent_at ASC");
$feedbacks = mysqli_query($conn,"SELECT f.*, u.username FROM feedback f LEFT JOIN users u ON f.user_id = u.id WHERE f.agent_id=$agent_id ORDER BY f.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($agent['full_name']); ?> | Profile</title>
    <link rel="stylesheet" href="./CSS/style.css">
    <style>
                .container{
            margin: 20px;
        }
        /* Profile Header */
        .profile-header { background: white; padding: 40px; border-radius: 12px; box-shadow: var(--shadow-md); margin-top: 20px; text-align: center; border-top: 5px solid var(--primary-color); }
        .expertise-badge { background: var(--bg-secondary); color: var(--primary-color); padding: 5px 15px; border-radius: 20px; font-weight: 600; font-size: 0.9rem; }
        
        /* Layout Grid */
        .agent-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 30px; }
        .section-card { background: white; padding: 25px; border-radius: 12px; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color); height: fit-content; }
        .section-card h3 { text-align: left; color: var(--text-primary); margin-top: 0; font-size: 1.2rem; }

        /* Chat/Message Box */
        .chat-history { background: #f9fafb; border: 1px solid #eee; padding: 15px; height: 250px; overflow-y: auto; border-radius: 8px; margin-bottom: 15px; display: flex; flex-direction: column; gap: 10px; }
        .bubble { max-width: 80%; padding: 10px; border-radius: 10px; font-size: 0.85rem; }
        .bubble.me { align-self: flex-end; background: var(--primary-color); color: white; border-bottom-right-radius: 0; }
        .bubble.them { align-self: flex-start; background: #e5e7eb; color: var(--text-primary); border-bottom-left-radius: 0; }

        /* Feedback Stars */
        .feedback-item { border-bottom: 1px solid #eee; padding: 10px 0; }
        .stars { color: #ffcc00; font-weight: bold; }
        
        .status-pill { padding: 3px 10px; border-radius: 12px; font-size: 0.7rem; font-weight: bold; text-transform: uppercase; }
        .status-pending { background: #fef3c7; color: #92400e; }

        @media (max-width: 900px) { .agent-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container">
    
    <?php if($successMsg): ?>
        <div class="alert alert-success"><?php echo $successMsg; ?></div>
    <?php endif; ?>

    <div class="profile-header">
        <h1><?php echo htmlspecialchars($agent['full_name']); ?></h1>
        <span class="expertise-badge"><?php echo htmlspecialchars($agent['expertise_domain']); ?></span>
        <p style="margin-top: 15px; max-width: 700px; margin-left: auto; margin-right: auto;">
            <?php echo nl2br(htmlspecialchars($agent['bio'])); ?>
        </p>
        <div style="margin-top: 20px; font-size: 0.9rem; color: var(--text-secondary);">
            <strong>Experience:</strong> <?php echo $agent['experience_years']; ?> Years &nbsp; | &nbsp; 
            <strong>Email:</strong> <?php echo htmlspecialchars($agent['email']); ?>
        </div>
    </div>

    <div class="agent-grid">
        
        <div class="left-col">
            
            <div class="section-card" style="margin-bottom: 30px;">
                <h3>Apply for Mentorship</h3>
                <form method="post">
                    <textarea name="message" rows="3" placeholder="Describe why you want mentorship..." required></textarea>
                    <button type="submit" name="mentorship_submit" style="width: 100%;">Send Request</button>
                </form>
                
                <h4 style="margin-top: 20px; font-size: 0.9rem;">Your Past Requests</h4>
                <table style="width: 100%; font-size: 0.8rem;">
                    <?php while($req = mysqli_fetch_assoc($requests)): ?>
                    <tr>
                        <td><?php echo date('M d', strtotime($req['request_date'])); ?></td>
                        <td><span class="status-pill status-<?php echo $req['status']; ?>"><?php echo $req['status']; ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>

            <div class="section-card">
                <h3>Direct Message</h3>
                <div class="chat-history">
                    <?php while($msg = mysqli_fetch_assoc($messages)): 
                        $class = ($msg['sender_id'] == $user_id) ? 'me' : 'them'; ?>
                        <div class="bubble <?php echo $class; ?>">
                            <?php echo htmlspecialchars($msg['message']); ?>
                        </div>
                    <?php endwhile; ?>
                </div>
                <form method="post">
                    <input type="text" name="message_text" placeholder="Type a message..." required style="margin-bottom: 10px;">
                    <button type="submit" name="message_submit" style="width: 100%; background: #333;">Send Message</button>
                </form>
            </div>
        </div>

        <div class="right-col">
            <div class="section-card">
                <h3>Reviews & Feedback</h3>
                <form method="post" style="background: var(--bg-secondary); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <label>Your Rating:</label>
                    <select name="rating" required>
                        <option value="5">5 Stars - Excellent</option>
                        <option value="4">4 Stars - Very Good</option>
                        <option value="3">3 Stars - Good</option>
                        <option value="2">2 Stars - Fair</option>
                        <option value="1">1 Star - Poor</option>
                    </select>
                    <textarea name="comment" rows="2" placeholder="Leave a review..." required></textarea>
                    <button type="submit" name="feedback_submit" style="background: var(--secondary-color); color: black;">Submit Review</button>
                </form>

                <div class="feedback-list">
                    <?php if(mysqli_num_rows($feedbacks) > 0): ?>
                        <?php while($f = mysqli_fetch_assoc($feedbacks)): ?>
                        <div class="feedback-item">
                            <div style="display: flex; justify-content: space-between;">
                                <strong><?php echo htmlspecialchars($f['username']); ?></strong>
                                <span class="stars"><?php echo str_repeat('★', $f['rating']); ?></span>
                            </div>
                            <p style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 5px;">
                                <?php echo htmlspecialchars($f['comment']); ?>
                            </p>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="text-align: center; color: #999;">No reviews yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>