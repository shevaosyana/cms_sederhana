<?php
// Hitung total posts
$stmt = $pdo->query("SELECT COUNT(*) as total FROM posts");
$totalPosts = $stmt->fetch()['total'];

// Hitung total categories
$stmt = $pdo->query("SELECT COUNT(*) as total FROM categories");
$totalCategories = $stmt->fetch()['total'];

// Hitung total users
$stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
$totalUsers = $stmt->fetch()['total'];

// Ambil 5 post terbaru
$stmt = $pdo->query("SELECT posts.*, categories.name as category_name, users.username 
                     FROM posts 
                     LEFT JOIN categories ON posts.category_id = categories.id 
                     LEFT JOIN users ON posts.user_id = users.id 
                     ORDER BY posts.created_at DESC LIMIT 5");
$recentPosts = $stmt->fetchAll();
?>

<div class="row">
    <div class="col-md-4">
        <div class="card dashboard-card bg-primary text-white">
            <div class="card-body">
                <h5 class="card-title">Total Posts</h5>
                <h2 class="card-text"><?php echo $totalPosts; ?></h2>
                <a href="index.php?page=posts" class="text-white">Lihat semua posts →</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card dashboard-card bg-success text-white">
            <div class="card-body">
                <h5 class="card-title">Total Categories</h5>
                <h2 class="card-text"><?php echo $totalCategories; ?></h2>
                <a href="index.php?page=categories" class="text-white">Lihat semua kategori →</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card dashboard-card bg-info text-white">
            <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <h2 class="card-text"><?php echo $totalUsers; ?></h2>
                <a href="index.php?page=users" class="text-white">Lihat semua users →</a>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="card-title mb-0">Post Terbaru</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentPosts as $post): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                        <td><?php echo htmlspecialchars($post['category_name'] ?? 'Uncategorized'); ?></td>
                        <td><?php echo htmlspecialchars($post['username']); ?></td>
                        <td>
                            <span class="badge bg-<?php echo $post['status'] === 'published' ? 'success' : 'warning'; ?>">
                                <?php echo ucfirst($post['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($post['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div> 