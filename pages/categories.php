<?php
require_once 'includes/header.php';

// Proses hapus kategori
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
    if ($stmt->execute([$_GET['delete']])) {
        setFlashMessage('success', 'Kategori berhasil dihapus!');
    } else {
        setFlashMessage('danger', 'Gagal menghapus kategori!');
    }
    header("Location: index.php?page=categories");
    exit();
}

// Proses tambah/edit kategori
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean($_POST['name']);
    $slug = strtolower(str_replace(' ', '-', $name));
    
    if (isset($_POST['id'])) {
        // Update kategori
        $stmt = $pdo->prepare("UPDATE categories SET name = ?, slug = ? WHERE id = ?");
        if ($stmt->execute([$name, $slug, $_POST['id']])) {
            setFlashMessage('success', 'Kategori berhasil diperbarui!');
        } else {
            setFlashMessage('danger', 'Gagal memperbarui kategori!');
        }
    } else {
        // Tambah kategori baru
        $stmt = $pdo->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
        if ($stmt->execute([$name, $slug])) {
            setFlashMessage('success', 'Kategori berhasil ditambahkan!');
        } else {
            setFlashMessage('danger', 'Gagal menambahkan kategori!');
        }
    }
    header("Location: index.php?page=categories");
    exit();
}

// Ambil semua kategori
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll();

// Cek jika ada filter kategori
$selected_category = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

// Jika kategori dipilih, ambil post dari kategori itu
$posts = [];
if ($selected_category) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE category_id = ?");
    $stmt->execute([$selected_category]);
    $posts = $stmt->fetchAll();
}
?>

<div class="container-fluid py-4">
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Pilih Kategori</h5>
        </div>
        <div class="card-body">
            <form method="get" class="row g-3">
                <div class="col-auto">
                    <select name="category_id" class="form-select" onchange="this.form.submit()">
                        <option value="0">-- Semua Kategori --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $selected_category == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <?php if ($selected_category && $posts): ?>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Post pada Kategori: <?= htmlspecialchars($categories[array_search($selected_category, array_column($categories, 'id'))]['name']) ?></h5>
            </div>
            <div class="card-body">
                <ul>
                    <?php foreach ($posts as $post): ?>
                        <li><?= htmlspecialchars($post['title']) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Daftar Kategori</h2>
    <a href="index.php?page=categories&action=create" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Kategori
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td><?php echo htmlspecialchars($category['slug']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($category['created_at'])); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="index.php?page=categories&action=edit&id=<?php echo $category['id']; ?>" 
                                   class="btn btn-info">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?page=categories&delete=<?php echo $category['id']; ?>" 
                                   class="btn btn-danger"
                                   onclick="return confirmDelete('Apakah Anda yakin ingin menghapus kategori ini?')">
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
// Form tambah/edit kategori
if (isset($_GET['action']) && ($_GET['action'] === 'create' || $_GET['action'] === 'edit')):
    $category = null;
    if ($_GET['action'] === 'edit' && isset($_GET['id'])) {
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $category = $stmt->fetch();
    }
?>
<div class="modal fade show" style="display: block;" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo $_GET['action'] === 'create' ? 'Tambah Kategori' : 'Edit Kategori'; ?></h5>
                <a href="index.php?page=categories" class="btn-close"></a>
            </div>
            <div class="modal-body">
                <form action="index.php?page=categories&action=<?php echo $_GET['action']; ?>" method="POST">
                    <?php if ($category): ?>
                    <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?php echo $category ? htmlspecialchars($category['name']) : ''; ?>" required>
                    </div>
                    
                    <div class="text-end">
                        <a href="index.php?page=categories" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal-backdrop fade show"></div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?> 