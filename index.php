<?php
session_start();
include "config.php";

// Simple global search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search = mysqli_real_escape_string($conn, $search);
$search_condition = $search ? " AND (title LIKE '%$search%' OR description LIKE '%$search%')" : "";

// Featured / Recent items (limited to 6–8 each for landing page)
$ideas = mysqli_query($conn, 
    "SELECT bi.*, c.category_name, a.full_name 
     FROM business_ideas bi 
     JOIN categories c ON bi.category_id = c.category_id 
     JOIN agents a ON bi.agent_id = a.agent_id 
     WHERE a.approval_status = 'approved' 
     $search_condition 
     ORDER BY bi.created_at DESC LIMIT 6");

$opportunities = mysqli_query($conn, 
    "SELECT o.*, a.full_name 
     FROM opportunities o 
     JOIN agents a ON o.agent_id = a.agent_id 
     WHERE a.approval_status = 'approved' 
     $search_condition 
     ORDER BY o.created_at DESC LIMIT 6");

$tutorials = mysqli_query($conn, 
    "SELECT * FROM tutorials $search_condition ORDER BY created_at DESC LIMIT 6");

$events = mysqli_query($conn, 
    "SELECT * FROM events $search_condition ORDER BY event_date ASC LIMIT 6");

// Stats for hero / trust section
$total_ideas   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM business_ideas"))['cnt'];
$total_agents  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM agents WHERE approval_status='approved'"))['cnt'];
$total_tuts    = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as cnt FROM tutorials"))['cnt'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Aspiring Entrepreneurs Portal - Pakistan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <link rel="stylesheet" href="./CSS/style.css">

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --success: #10b981;
            --light: #f9fafb;
            --dark: #111827;
            --gray: #6b7280;
        }
        * { margin:0; padding:0; box-sizing:border-box; }

        .container { max-width: 1280px; margin: 0 auto; padding: 0 20px; }

        /* Hero */
        .hero {
            background: linear-gradient(rgba(0,0,0,0.65), rgba(0,0,0,0.45)), 
                        url('https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1974&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            min-height: 80vh;
            display: flex;
            align-items: center;
            text-align: center;
        }
        .hero-content h1 { font-size: 3.4rem; margin-bottom: 1.2rem; }
        .hero-content p  { font-size: 1.35rem; max-width: 720px; margin: 0 auto 2.5rem; opacity: 0.95; }
        .hero-stats {
            display: flex;
            justify-content: center;
            gap: 3rem;
            flex-wrap: wrap;
            margin-top: 2.5rem;
        }
        .stat { font-size: 1.1rem; }
        .stat strong { font-size: 2.1rem; display: block; color: var(--success); }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 14px 32px;
            font-size: 1.1rem;
            font-weight: 500;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.25s ease;
        }
        .btn-primary    { background: var(--primary); color: white; }
        .btn-outline    { background: transparent; border: 2px solid white; color: white; }
        .btn:hover      { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.2); }

        /* Search */
        .search-form {
            max-width: 620px;
            margin: 40px auto;
            display: flex;
            gap: 12px;
        }
        .search-form input {
            flex: 1;
            padding: 16px 20px;
            font-size: 1.1rem;
            border: 2px solid #ff5a5f ;
            border-radius: 50px 0 0 50px;

        }


        /* Sections */
        section { padding: 80px 0; }
        .section-title {
            text-align: center;
            font-size: 2.4rem;
            margin-bottom: 2.5rem;
            color: var(--dark);
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 28px;
        }
        .card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
            transition: transform 0.25s ease;
        }
        .card:hover { transform: translateY(-8px); }
        .card-body { padding: 24px; }
        .card h3 { margin: 0 0 12px; font-size: 1.35rem; }
        .card p  { color: var(--gray); font-size: 0.97rem; margin-bottom: 1rem; }

        /* Footer */
        footer {
            background: var(--dark);
            color: white;
            padding: 60px 0 30px;
            text-align: center;
        }
        footer a { color: var(--primary); text-decoration: none; }

        @media (max-width: 768px) {
            .hero { min-height: 70vh; }
            .hero h1 { font-size: 2.6rem; }
            .hero-stats { flex-direction: column; gap: 1.5rem; }
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?>

    <!-- Hero -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Turn Your Business Idea into Reality</h1>
                <p>Connect with mentors, explore opportunities, access templates & tutorials — everything you need to start and grow in Pakistan.</p>

                <div class="search-form">
                    <form method="GET">
                        <input type="text" name="search" placeholder="Search ideas, opportunities, tutorials, events..." value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit"><i class="fas fa-search"></i> Search</button>
                    </form>
                </div>

                <div class="hero-stats">
                    <div class="stat">
                        <strong><?php echo number_format($total_ideas); ?></strong>
                        Business Ideas
                    </div>
                    <div class="stat">
                        <strong><?php echo $total_agents; ?></strong>
                        Approved Mentors
                    </div>
                    <div class="stat">
                        <strong><?php echo $total_tuts; ?></strong>
                        Learning Resources
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Business Ideas -->
    <section style="background:#fff;">
        <div class="container">
            <h2 class="section-title">Featured Business Ideas</h2>
            <?php if (mysqli_num_rows($ideas) > 0): ?>
                <div class="grid">
                    <?php while ($row = mysqli_fetch_assoc($ideas)): ?>
                        <div class="card">
                            <div class="card-body">
                                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                                <p style="color:var(--primary); font-weight:500;">
                                    <?php echo htmlspecialchars($row['category_name']); ?>
                                </p>
                                <p><?php echo htmlspecialchars(substr($row['description'] ?? '', 0, 120)); ?>...</p>
                                <p style="margin-top:12px; font-size:0.95rem; color:var(--gray);">
                                    by <?php echo htmlspecialchars($row['full_name']); ?>
                                </p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p style="text-align:center; color:var(--gray);">No ideas found<?php echo $search ? " matching '$search'" : ""; ?>.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Opportunities -->
    <section style="background:#f9fafb;">
        <div class="container">
            <h2 class="section-title">Latest Opportunities</h2>
            <?php if (mysqli_num_rows($opportunities) > 0): ?>
                <div class="grid">
                    <?php while ($row = mysqli_fetch_assoc($opportunities)): ?>
                        <div class="card">
                            <div class="card-body">
                                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                                <p style="color:#e74c3c; font-weight:500;"><?php echo htmlspecialchars($row['location']); ?></p>
                                <p><?php echo htmlspecialchars(substr($row['description'] ?? '', 0, 110)); ?>...</p>
                                <p style="margin-top:12px; font-size:0.95rem; color:var(--gray);">
                                    by <?php echo htmlspecialchars($row['full_name']); ?>
                                </p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p style="text-align:center; color:var(--gray);">No opportunities found<?php echo $search ? " matching '$search'" : ""; ?>.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Tutorials & Events (side by side on larger screens) -->
    <section>
        <div class="container">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:40px;">
                
                <div>
                    <h2 class="section-title">Recent Tutorials</h2>
                    <?php if (mysqli_num_rows($tutorials) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($tutorials)): ?>
                            <div style="background:white; padding:18px; border-radius:10px; margin-bottom:16px; box-shadow:0 2px 10px rgba(0,0,0,0.06);">
                                <h4 style="margin:0 0 8px;"><?php echo htmlspecialchars($row['title']); ?></h4>
                                <p style="margin:0; color:var(--gray); font-size:0.95rem;">
                                    <?php echo htmlspecialchars(substr($row['description'] ?? '', 0, 100)); ?>...
                                </p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="color:var(--gray);">No tutorials found<?php echo $search ? " matching '$search'" : ""; ?>.</p>
                    <?php endif; ?>
                </div>

                <div>
                    <h2 class="section-title">Upcoming Events</h2>
                    <?php if (mysqli_num_rows($events) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($events)): ?>
                            <div style="background:white; padding:18px; border-radius:10px; margin-bottom:16px; box-shadow:0 2px 10px rgba(0,0,0,0.06);">
                                <h4 style="margin:0 0 8px;"><?php echo htmlspecialchars($row['title']); ?></h4>
                                <p style="margin:4px 0; color:#e74c3c;">
                                    <?php echo date('d M Y', strtotime($row['event_date'])); ?> • <?php echo htmlspecialchars($row['location']); ?>
                                </p>
                                <p style="margin:0; color:var(--gray); font-size:0.95rem;">
                                    <?php echo htmlspecialchars(substr($row['description'] ?? '', 0, 90)); ?>...
                                </p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="color:var(--gray);">No upcoming events<?php echo $search ? " matching '$search'" : ""; ?>.</p>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section style="background:var(--primary); color:white; text-align:center; padding:100px 20px;">
        <div class="container">
            <h2 style="font-size:2.6rem; margin-bottom:1.5rem;">Ready to Start Your Journey?</h2>
            <p style="font-size:1.3rem; max-width:700px; margin:0 auto 2.5rem; opacity:0.95;">
                Join thousands of aspiring entrepreneurs in Pakistan. Get mentorship, resources, and real opportunities.
            </p>
            <div style="display:flex; justify-content:center; gap:24px; flex-wrap:wrap;">
                <a href="register.php" class="btn btn-primary" style="background:white; color:var(--primary);">Create Free Account</a>
                <a href="login.php" class="btn btn-outline">Login</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>© <?php echo date("Y"); ?> Aspiring Entrepreneurs Portal. All rights reserved.</p>
            <p style="margin-top:12px; font-size:0.95rem; opacity:0.8;">
                Made with passion in Karachi, Pakistan
            </p>
        </div>
    </footer>

</body>
</html>

<?php mysqli_close($conn); ?>