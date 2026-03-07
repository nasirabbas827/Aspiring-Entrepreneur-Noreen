<?php
session_start();
include "config.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Handle category filter
$category_filter = isset($_GET['category_id']) && $_GET['category_id'] !== '' ? intval($_GET['category_id']) : 0;

// Fetch categories
$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name ASC");

// Base queries with optional category filter (for ideas only — templates & opportunities not directly linked to category)
$where_clause = $category_filter ? " WHERE b.category_id = $category_filter" : "";
$order_clause = " ORDER BY created_at DESC LIMIT 12"; // limit for performance & UX

$ideas_query = "SELECT b.*, c.category_name, a.full_name AS agent_name, a.agent_id 
                FROM business_ideas b 
                JOIN categories c ON b.category_id = c.category_id 
                JOIN agents a ON b.agent_id = a.agent_id 
                $where_clause $order_clause";

$templates_query = "SELECT t.*, a.full_name AS agent_name, a.agent_id 
                    FROM business_templates t 
                    JOIN agents a ON t.agent_id = a.agent_id 
                    ORDER BY t.uploaded_at DESC LIMIT 12";

$opportunities_query = "SELECT o.*, a.full_name AS agent_name, a.agent_id 
                        FROM opportunities o 
                        JOIN agents a ON o.agent_id = a.agent_id 
                        ORDER BY o.created_at DESC LIMIT 12";

$ideas_result        = mysqli_query($conn, $ideas_query);
$templates_result    = mysqli_query($conn, $templates_query);
$opportunities_result = mysqli_query($conn, $opportunities_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Entrepreneur Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="CSS/style.css">

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --light: #f8fafc;
            --gray: #64748b;
            --dark: #1e293b;
            --border: #e2e8f0;
            --shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 24px;
        }

        h1, h2 {
            color: var(--dark);
            margin-bottom: 16px;
        }

        .filter-box {
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 32px;
        }

        .filter-box form {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
            align-items: center;
        }

        select, button {
            padding: 12px 16px;
            border-radius: 8px;
            border: 1px solid var(--border);
            font-size: 1rem;
        }

        button {
            background: var(--primary);
            color: white;
            border: none;
            cursor: pointer;
            transition: background 0.2s;
        }

        button:hover { background: var(--primary-dark); }

        .section {
            margin-bottom: 48px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
            gap: 24px;
        }

        .card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(0,0,0,0.12);
        }

        .card-header {
            padding: 18px 20px;
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            border-bottom: 1px solid var(--border);
        }

        .card-header h3 {
            margin: 0;
            font-size: 1.25rem;
            color: var(--dark);
        }

        .card-body {
            padding: 20px;
        }

        .meta {
            color: var(--gray);
            font-size: 0.9rem;
            margin-bottom: 12px;
        }

        .description {
            color: #475569;
            line-height: 1.5;
            margin-bottom: 16px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .actions {
            display: flex;
            gap: 12px;
            margin-top: 16px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary { background: var(--primary); color: white; }
        .btn-outline  { border: 1px solid var(--primary); color: var(--primary); }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray);
            font-size: 1.1rem;
        }

        @media (max-width: 768px) {
            .filter-box form { flex-direction: column; align-items: stretch; }
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">

    <h1>User Dashboard</h1>

    <!-- Category Filter -->
    <div class="filter-box">
        <h2>Filter by Business Category</h2>
        <form method="GET">
            <select name="category_id">
                <option value="">— All Categories —</option>
                <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                    <option value="<?php echo $cat['category_id']; ?>" 
                            <?php echo ($category_filter == $cat['category_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['category_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
            <button type="submit"><i class="fas fa-search"></i> Filter</button>
        </form>
    </div>

    <!-- Business Ideas -->
    <div class="section">
        <h2><i class="fas fa-lightbulb"></i> Business Ideas</h2>
        <?php if (mysqli_num_rows($ideas_result) > 0): ?>
            <div class="grid">
                <?php while ($idea = mysqli_fetch_assoc($ideas_result)): ?>
                    <div class="card">
                        <div class="card-header">
                            <h3><?php echo htmlspecialchars($idea['title']); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="meta">
                                <?php echo htmlspecialchars($idea['category_name']); ?> • 
                                Investment: <strong>PKR <?php echo number_format($idea['investment_required'], 0); ?></strong>
                            </div>
                            <div class="meta">By <?php echo htmlspecialchars($idea['agent_name']); ?></div>
                            <div class="description">
                                <?php echo nl2br(htmlspecialchars(substr($idea['description'], 0, 220))); ?>...
                            </div>
                            <div class="actions">
                                <a href="view_idea.php?id=<?php echo $idea['idea_id']; ?>" class="btn btn-primary">View Details</a>
                                <a href="view_agent.php?id=<?php echo $idea['agent_id']; ?>" class="btn btn-outline">View Mentor</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="far fa-lightbulb" style="font-size: 3rem; opacity: 0.5; margin-bottom: 16px;"></i><br>
                No business ideas found in this category.
            </div>
        <?php endif; ?>
    </div>

    <!-- Business Templates -->
    <div class="section">
        <h2><i class="fas fa-file-alt"></i> Business Templates</h2>
        <?php if (mysqli_num_rows($templates_result) > 0): ?>
            <div class="grid">
                <?php while ($tpl = mysqli_fetch_assoc($templates_result)): ?>
                    <div class="card">
                        <div class="card-header">
                            <h3><?php echo htmlspecialchars($tpl['title']); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="meta">Uploaded by <?php echo htmlspecialchars($tpl['agent_name']); ?></div>
                            <div class="meta">Date: <?php echo date('M d, Y', strtotime($tpl['uploaded_at'])); ?></div>
                            <div class="actions">
                                <a href="uploads/templates/<?php echo $tpl['file_path']; ?>" target="_blank" class="btn btn-primary">
                                    <i class="fas fa-download"></i> Download
                                </a>
                                <a href="view_agent.php?id=<?php echo $tpl['agent_id']; ?>" class="btn btn-outline">View Mentor</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="far fa-file" style="font-size: 3rem; opacity: 0.5; margin-bottom: 16px;"></i><br>
                No templates available yet.
            </div>
        <?php endif; ?>
    </div>

    <!-- Opportunities -->
    <div class="section">
        <h2><i class="fas fa-handshake"></i> Opportunities</h2>
        <?php if (mysqli_num_rows($opportunities_result) > 0): ?>
            <div class="grid">
                <?php while ($opp = mysqli_fetch_assoc($opportunities_result)): ?>
                    <div class="card">
                        <div class="card-header">
                            <h3><?php echo htmlspecialchars($opp['title']); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="meta">Location: <?php echo htmlspecialchars($opp['location']); ?></div>
                            <div class="meta">Posted by <?php echo htmlspecialchars($opp['agent_name']); ?></div>
                            <div class="actions">
                                <a href="view_opportunity.php?id=<?php echo $opp['opportunity_id']; ?>" class="btn btn-primary">View Details</a>
                                <a href="view_agent.php?id=<?php echo $opp['agent_id']; ?>" class="btn btn-outline">View Mentor</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="far fa-handshake" style="font-size: 3rem; opacity: 0.5; margin-bottom: 16px;"></i><br>
                No opportunities posted yet.
            </div>
        <?php endif; ?>
    </div>

</div>

</body>
</html>

<?php mysqli_close($conn); ?>