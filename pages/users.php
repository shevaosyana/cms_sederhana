<?php
// Proses tambah user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'create') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $email, $password])) {
        setFlashMessage('success', 'User berhasil ditambahkan!');
    } else {
        setFlashMessage('danger', 'Gagal menambahkan user!');
    }
    header("Location: index.php?page=users");
    exit();
}

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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar User</h2>
        <a href="index.php?page=users&action=create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah User
        </a>
    </div>
    <?php if (isset($_GET['action']) && $_GET['action'] === 'create'): ?>
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Tambah User</h5>
        </div>
        <div class="card-body">
            <form action="index.php?page=users&action=create" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="text-end">
                    <a href="index.php?page=users" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>
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