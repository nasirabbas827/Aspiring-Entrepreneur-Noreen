<?php
session_start();
include "config.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id'])) {
    header("Location: templates.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch template details with agent info
$query = "SELECT t.*, a.full_name AS agent_name, a.expertise_domain, a.bio, a.agent_id
          FROM business_templates t
          JOIN agents a ON t.agent_id = a.agent_id
          WHERE t.template_id = $id";

$result = mysqli_query($conn, $query);
$template = mysqli_fetch_assoc($result);

if(!$template){
    die("<div class='container' style='margin-top:50px;'><h2>Template not found.</h2><a href='templates.php'>Back to Library</a></div>");
}

// Logic to determine file icon/type (Optional but looks great)
$file_ext = pathinfo($template['file_path'], PATHINFO_EXTENSION);
$file_label = strtoupper($file_ext) . " Document";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($template['title']); ?> | Resources</title>
    <link rel="stylesheet" href="./CSS/style.css">
    <style>
                        .container{
            margin: 20px;
        }
        /* --- TEMPLATE PAGE CSS --- */
        .template-wrapper {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-top: 40px;
        }

        .main-content, .agent-sidebar {
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
        }

        .resource-header {
            border-bottom: 2px solid var(--bg-secondary);
            margin-bottom: 25px;
            padding-bottom: 15px;
        }

        .resource-header h2 { text-align: left; margin: 0; color: var(--text-primary); }

        /* Download Section */
        .download-box {
            background: var(--bg-secondary);
            border: 2px dashed var(--border-color);
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            margin: 25px 0;
        }

        .file-icon {
            font-size: 2.5rem;
            display: block;
            margin-bottom: 10px;
        }

        .btn-download {
            display: inline-block;
            background: var(--primary-color);
            color: white;
            padding: 15px 40px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 800;
            font-size: 1.1rem;
            transition: 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .btn-download:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            background: var(--primary-hover);
        }

        .description-text {
            line-height: 1.8;
            color: var(--text-secondary);
            margin-bottom: 20px;
        }

        /* Sidebar Agent Profile */
        .agent-sidebar h3 { text-align: left; margin-top: 0; font-size: 1.1rem; color: #666; }
        .agent-name { font-size: 1.3rem; font-weight: 700; color: var(--primary-color); display: block; margin-top: 10px; }
        .agent-tag { font-size: 0.8rem; background: #eee; padding: 2px 8px; border-radius: 4px; }

        .btn-view-profile {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
        }

        @media (max-width: 900px) {
            .template-wrapper { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container">
    <div class="template-wrapper">
        
        <main class="main-content">
            <div class="resource-header">
                <h2><?php echo htmlspecialchars($template['title']); ?></h2>
                <small style="color: #999;">Uploaded on: <?php echo date('F d, Y', strtotime($template['uploaded_at'])); ?></small>
            </div>

            <h3>About this Template</h3>
            <div class="description-text">
                <?php echo nl2br(htmlspecialchars($template['description'])); ?>
            </div>

            <div class="download-box">
                <span class="file-icon">📄</span>
                <p style="margin-bottom: 15px;"><strong>File Type:</strong> <?php echo $file_label; ?></p>
                <a href="uploads/templates/<?php echo $template['file_path']; ?>" 
                   class="btn-download" 
                   target="_blank" 
                   download>
                   Download Now
                </a>
                <p style="font-size: 0.8rem; color: #777; margin-top: 15px;">
                    Secure Download &middot; Virus Scanned
                </p>
            </div>
        </main>

        <aside class="agent-sidebar">
            <h3>Resource Contributor</h3>
            <div style="border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px;">
                <span class="agent-name"><?php echo htmlspecialchars($template['agent_name']); ?></span>
                <span class="agent-tag"><?php echo htmlspecialchars($template['expertise_domain']); ?></span>
            </div>
            
            <p style="font-size: 0.9rem; color: #666; line-height: 1.5;">
                <?php echo nl2br(htmlspecialchars($template['bio'])); ?>
            </p>

            <a href="view_agent.php?id=<?php echo $template['agent_id']; ?>" class="btn-view-profile">
                View Agent Profile
            </a>
        </aside>

    </div>
</div>

</body>
</html>
<?php mysqli_close($conn); ?>