<?php
// Proses hapus post
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    if ($stmt->execute([$_GET['delete']])) {
        setFlashMessage('success', 'Post berhasil dihapus!');
    } else {
        setFlashMessage('danger', 'Gagal menghapus post!');
    }
    header("Location: index.php?page=posts");
    exit();
}

// Ambil semua posts
$posts = getAllPosts($pdo);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Posts</h2>
    <a href="index.php?page=posts&action=create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Post
    </a>
</div>

<div class="card">
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
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($posts as $post): ?>
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
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="index.php?page=posts&action=edit&id=<?php echo $post['id']; ?>" 
                                   class="btn btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?page=posts&delete=<?php echo $post['id']; ?>" 
                                   class="btn btn-danger"
                                   onclick="return confirmDelete('Apakah Anda yakin ingin menghapus post ini?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Form tambah/edit post
if (isset($_GET['action']) && ($_GET['action'] === 'create' || $_GET['action'] === 'edit')):
    $post = null;
    if ($_GET['action'] === 'edit' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $post = $stmt->fetch();
    }
?>
<div class="modal fade show" style="display: block;" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $_GET['action'] === 'create' ? 'Tambah Post' : 'Edit Post'; ?></h5>
                <a href="index.php?page=posts" class="btn-close"></a>
            </div>
            <div class="modal-body">
                <form action="index.php?page=posts&action=<?php echo $_GET['action']; ?>" method="POST">
                    <?php if ($post): ?>
                    <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?php echo $post ? htmlspecialchars($post['title']) : ''; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select class="form-select" id="category_id" name="category_id">
                            <option value="">Pilih Kategori</option>
                            <?php foreach (getAllCategories($pdo) as $category): ?>
                            <option value="<?php echo $category['id']; ?>" 
                                    <?php echo $post && $post['category_id'] == $category['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Konten</label>
                        <textarea class="form-control" id="content" name="content" rows="10" required><?php 
                            echo $post ? htmlspecialchars($post['content']) : ''; 
                        ?></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="draft" <?php echo $post && $post['status'] === 'draft' ? 'selected' : ''; ?>>
                                Draft
                            </option>
                            <option value="published" <?php echo $post && $post['status'] === 'published' ? 'selected' : ''; ?>>
                                Published
                            </option>
                        </select>
                    </div>
                    
                    <div class="text-end">
                        <a href="index.php?page=posts" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal-backdrop fade show"></div>
<?php endif; ?> 