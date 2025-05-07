<?php
require_once 'includes/header.php';

// Hitung total posts
$stmt = $pdo->query("SELECT COUNT(*) as total FROM posts");
$totalPosts = $stmt->fetch()['total'];

// Hitung total categories
$stmt = $pdo->query("SELECT COUNT(*) as total FROM categories");
$totalCategories = $stmt->fetch()['total'];

// Hitung total users
$stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
$totalUsers = $stmt->fetch()['total'];

// Hitung total comments
$stmt = $pdo->query("SELECT COUNT(*) as total FROM comments");
$totalComments = $stmt->fetch()['total'];

// Ambil 5 post terbaru
$stmt = $pdo->query("SELECT posts.*, categories.name as category_name, users.username 
                     FROM posts 
                     LEFT JOIN categories ON posts.category_id = categories.id 
                     LEFT JOIN users ON posts.user_id = users.id 
                     ORDER BY posts.created_at DESC LIMIT 5");
$recentPosts = $stmt->fetchAll();
?>

<div class="container-fluid py-4">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h1 class="display-4">Selamat Datang, Aldit Sheva Osyana!</h1>
                    <p class="lead">Selamat datang di Dashboard CMS Sederhana</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Posts</h5>
                    <p class="card-text"><?php echo $totalPosts; ?></p>
                    <a href="index.php?page=posts" class="btn btn-light btn-sm">Lihat Posts</a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Categories</h5>
                    <p class="card-text"><?php echo $totalCategories; ?></p>
                    <a href="index.php?page=categories" class="btn btn-light btn-sm">Lihat Categories</a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text"><?php echo $totalUsers; ?></p>
                    <a href="index.php?page=users" class="btn btn-light btn-sm">Lihat Users</a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card dashboard-card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Comments</h5>
                    <p class="card-text"><?php echo $totalComments; ?></p>
                    <a href="index.php?page=comments" class="btn btn-light btn-sm">Lihat Comments</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Posts -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Posts</h5>
                    <a href="index.php?page=posts" class="btn btn-primary btn-sm">View All</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentPosts as $post): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($post['title']); ?></td>
                                    <td><?php echo htmlspecialchars($post['category_name'] ?? 'Uncategorized'); ?></td>
                                    <td><?php echo htmlspecialchars($post['username']); ?></td>
                                    <td><?php echo date('d M Y', strtotime($post['created_at'])); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $post['status'] == 'published' ? 'success' : 'warning'; ?>">
                                            <?php echo ucfirst($post['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 