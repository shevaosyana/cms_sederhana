<?php
require_once 'includes/header.php';

// Ambil data user beserta total post dan total comments
$stmt = $pdo->query("
    SELECT 
        u.id, 
        u.username, 
        u.email,
        (SELECT COUNT(*) FROM posts p WHERE p.user_id = u.id) AS total_posts,
        (SELECT COUNT(*) FROM comments c WHERE c.user_id = u.id) AS total_comments
    FROM users u
");
$users = $stmt->fetchAll();
?>

<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar User</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Total Post</th>
                            <th>Total Comments</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $i => $user): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><?= htmlspecialchars($user['username']) ?></td>
                            <td><?= htmlspecialchars($user['email']) ?></td>
                            <td><?= $user['total_posts'] ?></td>
                            <td><?= $user['total_comments'] ?></td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 