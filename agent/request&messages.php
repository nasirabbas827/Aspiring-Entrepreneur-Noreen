<?php
session_start();
include "../config.php";

if (!isset($_SESSION['agent_id'])) {
    header("Location: ../login.php");
    exit();
}

$agent_id = $_SESSION['agent_id'];

// Handle Mentorship Request Status Update
if (isset($_GET['action'], $_GET['request_id'])) {
    $request_id = intval($_GET['request_id']);
    $action = $_GET['action'];
    if (in_array($action, ['accepted', 'rejected'])) {
        $sql = "UPDATE mentorship_requests SET status = ? WHERE request_id = ? AND agent_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sii", $action, $request_id, $agent_id);
        mysqli_stmt_execute($stmt);
    }
    header("Location: dashboard.php"); // refresh to prevent resubmit
    exit();
}

// Handle Message Reply
if (isset($_POST['reply_submit'])) {
    $receiver_id = intval($_POST['receiver_id']);
    $message_text = trim($_POST['message_text']);
    if ($message_text !== "") {
        $sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iis", $agent_id, $receiver_id, $message_text);
        mysqli_stmt_execute($stmt);
    }
}

// Fetch Mentorship Requests
$requests = mysqli_query($conn, "SELECT mr.*, u.username AS user_name 
    FROM mentorship_requests mr 
    JOIN users u ON mr.user_id = u.id
    WHERE mr.agent_id = $agent_id
    ORDER BY mr.request_date DESC");

// Fetch Users with messages
$users_messages = mysqli_query($conn, "SELECT DISTINCT u.id, u.username
    FROM messages m
    JOIN users u ON (u.id = CASE WHEN m.sender_id = $agent_id THEN m.receiver_id ELSE m.sender_id END)
    WHERE m.sender_id = $agent_id OR m.receiver_id = $agent_id
    ORDER BY u.username ASC");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Workspace | Entrepreneur Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../CSS/style.css">

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --success: #10b981;
            --danger: #ef4444;
            --light: #f8fafc;
            --dark: #1e293b;
            --gray: #64748b;
            --border: #e2e8f0;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }



        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 24px;
        }

        .header {
            margin-bottom: 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            color: var(--dark);
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 28px;
        }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-md);
            overflow: hidden;
            border: 1px solid var(--border);
            transition: transform 0.2s ease;
        }

        .card:hover {
            transform: translateY(-4px);
        }

        .card-header {
            padding: 20px 24px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid var(--border);
        }

        .card-header h2 {
            font-size: 1.35rem;
            color: var(--dark);
            margin: 0;
        }

        .card-body {
            padding: 24px;
        }

        /* Requests */
        .request-item {
            padding: 18px 0;
            border-bottom: 1px solid var(--border);
        }

        .request-item:last-child {
            border-bottom: none;
        }

        .req-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .user-name {
            font-weight: 600;
            color: var(--primary);
            font-size: 1.05rem;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-accepted {
            background: #d1fae5;
            color: #065f46;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .req-message {
            color: #475569;
            margin: 12px 0;
            font-size: 0.95rem;
        }

        .req-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            color: var(--gray);
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-accept {
            background: var(--success);
            color: white;
        }

        .btn-reject {
            background: var(--danger);
            color: white;
        }

        .btn:hover {
            filter: brightness(1.08);
            transform: translateY(-1px);
        }

        /* Chat */
        .chat-select {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border);
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 1rem;
        }

        .message-box {
            background: #f8fafc;
            border-radius: 12px;
            height: 420px;
            overflow-y: auto;
            padding: 20px;
            margin-bottom: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .msg {
            max-width: 78%;
            padding: 12px 16px;
            border-radius: 18px;
            font-size: 0.95rem;
            line-height: 1.45;
            position: relative;
        }

        .msg.sent {
            align-self: flex-end;
            background: var(--primary);
            color: white;
            border-bottom-right-radius: 4px;
        }

        .msg.received {
            align-self: flex-start;
            background: white;
            color: #1e293b;
            border-bottom-left-radius: 4px;
            box-shadow: var(--shadow-sm);
        }

        .msg time {
            display: block;
            margin-top: 6px;
            font-size: 0.75rem;
            opacity: 0.7;
        }

        .reply-form {
            display: flex;
            gap: 12px;
        }

        .reply-form textarea {
            flex: 1;
            padding: 14px;
            border: 1px solid var(--border);
            border-radius: 12px;
            resize: none;
            font-size: 0.95rem;
            min-height: 54px;
        }

        .reply-form button {
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0 24px;
            font-weight: 500;
            cursor: pointer;
        }

        .reply-form button:hover {
            background: var(--primary-dark);
        }

        .no-chat {
            text-align: center;
            padding: 80px 20px;
            color: var(--gray);
            font-size: 1.1rem;
        }

        @media (max-width: 992px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="header">
            <h1>Agent Workspace</h1>
            <span style="color:var(--gray);">Welcome back • <?php echo date('M d, Y'); ?></span>
        </div>

        <div class="dashboard-grid">

            <!-- Mentorship Requests -->
            <div class="card">
                <div class="card-header">
                    <h2>Mentorship Requests</h2>
                </div>
                <div class="card-body">
                    <?php if (mysqli_num_rows($requests) > 0): ?>
                        <?php while ($req = mysqli_fetch_assoc($requests)): ?>
                            <div class="request-item">
                                <div class="req-header">
                                    <span class="user-name"><i class="far fa-user-circle"></i>
                                        <?php echo htmlspecialchars($req['user_name']); ?></span>
                                    <span class="status-badge status-<?php echo $req['status']; ?>">
                                        <?php echo strtoupper($req['status']); ?>
                                    </span>
                                </div>
                                <div class="req-message">
                                    <?php echo nl2br(htmlspecialchars($req['message'])); ?>
                                </div>
                                <div class="req-footer">
                                    <span><?php echo date('M d, Y • h:i A', strtotime($req['request_date'])); ?></span>
                                    <?php if ($req['status'] === 'pending'): ?>
                                        <div>
                                            <a href="?action=accepted&request_id=<?php echo $req['request_id']; ?>"
                                                class="btn btn-accept">Accept</a>
                                            <a href="?action=rejected&request_id=<?php echo $req['request_id']; ?>"
                                                class="btn btn-reject">Reject</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="no-chat">
                            <i class="far fa-envelope" style="font-size:2.2rem; opacity:0.5; margin-bottom:12px;"></i><br>
                            No mentorship requests yet.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Private Messages -->
            <div class="card">
                <div class="card-header">
                    <h2>Private Messages</h2>
                </div>
                <div class="card-body">
                    <form method="get">
                        <select name="user_id" class="chat-select" onchange="this.form.submit()">
                            <option value="">— Select a conversation —</option>
                            <?php
                            mysqli_data_seek($users_messages, 0);
                            while ($u = mysqli_fetch_assoc($users_messages)):
                                $selected = (isset($_GET['user_id']) && $_GET['user_id'] == $u['id']) ? 'selected' : '';
                                ?>
                                <option value="<?php echo $u['id']; ?>" <?php echo $selected; ?>>
                                    <?php echo htmlspecialchars($u['username']); ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </form>

                    <?php if (isset($_GET['user_id'])):
                        $user_id = intval($_GET['user_id']);
                        $conversation = mysqli_query($conn, "SELECT * FROM messages 
                        WHERE (sender_id=$agent_id AND receiver_id=$user_id)
                           OR (sender_id=$user_id AND receiver_id=$agent_id)
                        ORDER BY sent_at ASC");
                        ?>
                        <div class="message-box">
                            <?php while ($msg = mysqli_fetch_assoc($conversation)):
                                $is_sent = ($msg['sender_id'] == $agent_id);
                                ?>
                                <div class="msg <?php echo $is_sent ? 'sent' : 'received'; ?>">
                                    <?php echo nl2br(htmlspecialchars($msg['message'])); ?>
                                    <time><?php echo date('h:i A', strtotime($msg['sent_at'])); ?></time>
                                </div>
                            <?php endwhile; ?>
                        </div>

                        <form method="post" class="reply-form">
                            <textarea name="message_text" placeholder="Type your message..." required></textarea>
                            <input type="hidden" name="receiver_id" value="<?php echo $user_id; ?>">
                            <button type="submit" name="reply_submit"><i class="fas fa-paper-plane"></i></button>
                        </form>
                    <?php else: ?>
                        <div class="no-chat">
                            <i class="far fa-comment-dots"
                                style="font-size:2.5rem; opacity:0.5; margin-bottom:16px;"></i><br>
                            Select a user to view or start a conversation
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

</body>

</html>

<?php mysqli_close($conn); ?>