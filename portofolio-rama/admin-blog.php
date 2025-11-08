<?php
session_start();
require_once 'config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Check if user has admin role
if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
    $_SESSION['error'] = "Akses ditolak. Hanya admin yang dapat mengelola blog.";
    header("Location: index.php#blog");
    exit();
}

// Handle post deletion
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    try {
        // Get image path before deleting
        $stmt = $pdo->prepare("SELECT image FROM blog_posts WHERE id = ?");
        $stmt->execute([$delete_id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Delete the post
        $stmt = $pdo->prepare("DELETE FROM blog_posts WHERE id = ?");
        $stmt->execute([$delete_id]);
        
        // Delete image file if exists
        if ($post && $post['image'] && file_exists($post['image'])) {
            unlink($post['image']);
        }
        
        $_SESSION['success'] = "Artikel berhasil dihapus";
    } catch(PDOException $e) {
        $_SESSION['error'] = "Gagal menghapus artikel: " . $e->getMessage();
    }
    header("Location: admin-blog.php");
    exit();
}

// Fetch all blog posts
try {
    $stmt = $pdo->prepare("SELECT bp.*, u.fullname as author FROM blog_posts bp LEFT JOIN users u ON bp.user_id = u.id ORDER BY bp.created_at DESC");
    $stmt->execute();
    $blog_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $blog_posts = [];
}

// Handle edit mode
$edit_post = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM blog_posts WHERE id = ?");
        $stmt->execute([$edit_id]);
        $edit_post = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $edit_post = null;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Blog | Rama Manansyah</title>
  <link rel="stylesheet" href="styles.css?v=<?=time()?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    .admin-blog {
      padding: 120px 0 80px;
      background: #f8fafc;
      min-height: 100vh;
    }
    
    .admin-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 3rem;
      background: white;
      padding: 2rem;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    
    .admin-header h1 {
      color: #2d3748;
      margin: 0;
    }
    
    .admin-actions {
      display: flex;
      gap: 1rem;
      align-items: center;
    }
    
    .blog-form {
      background: white;
      padding: 2rem;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      margin-bottom: 3rem;
    }
    
    .blog-form h3 {
      color: #2d3748;
      margin-bottom: 2rem;
      border-bottom: 2px solid #e2e8f0;
      padding-bottom: 1rem;
    }
    
    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }
    
    .form-group {
      margin-bottom: 1.5rem;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: #2d3748;
    }
    
    .form-control {
      width: 100%;
      padding: 12px;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      font-size: 1rem;
      transition: all 0.3s ease;
    }
    
    .form-control:focus {
      outline: none;
      border-color: #4A90E2;
      box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
    }
    
    textarea.form-control {
      min-height: 150px;
      resize: vertical;
    }
    
    .posts-table {
      background: white;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    
    .table-header {
      background: #4A90E2;
      color: white;
      padding: 1.5rem;
    }
    
    .table-header h3 {
      margin: 0;
      color: white;
    }
    
    .posts-list {
      padding: 0;
    }
    
    .post-item {
      display: grid;
      grid-template-columns: 80px 1fr auto;
      gap: 1rem;
      padding: 1.5rem;
      border-bottom: 1px solid #e2e8f0;
      align-items: center;
    }
    
    .post-item:last-child {
      border-bottom: none;
    }
    
    .post-image {
      width: 80px;
      height: 80px;
      border-radius: 8px;
      overflow: hidden;
      flex-shrink: 0;
    }
    
    .post-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
      display: block;
    }
    
    .post-image.no-image {
      background: #e2e8f0;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #718096;
    }
    
    .post-info h4 {
      margin: 0 0 0.5rem 0;
      color: #2d3748;
      font-size: 1.1rem;
    }
    
    .post-meta {
      color: #718096;
      font-size: 0.875rem;
      margin-bottom: 0.5rem;
    }
    
    .post-excerpt {
      color: #718096;
      font-size: 0.9rem;
      line-height: 1.4;
    }
    
    .post-actions {
      display: flex;
      gap: 0.5rem;
    }
    
    .action-btn {
      padding: 0.5rem;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 0.875rem;
      transition: all 0.3s ease;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
    
    .edit-btn {
      background: #f7fafc;
      color: #4A90E2;
      border: 1px solid #e2e8f0;
    }
    
    .edit-btn:hover {
      background: #4A90E2;
      color: white;
    }
    
    .delete-btn {
      background: #fed7d7;
      color: #e53e3e;
      border: 1px solid #feb2b2;
    }
    
    .delete-btn:hover {
      background: #e53e3e;
      color: white;
    }
    
    .alert {
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 1rem;
    }
    
    .alert-success {
      background: #c6f6d5;
      color: #22543d;
      border: 1px solid #9ae6b4;
    }
    
    .alert-error {
      background: #fed7d7;
      color: #742a2a;
      border: 1px solid #feb2b2;
    }
    
    .empty-state {
      text-align: center;
      padding: 3rem;
      color: #718096;
    }
    
    .empty-state i {
      font-size: 3rem;
      margin-bottom: 1rem;
      color: #cbd5e0;
    }
    
    @media (max-width: 768px) {
      .admin-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
      }
      
      .form-row {
        grid-template-columns: 1fr;
      }
      
      .post-item {
        grid-template-columns: 1fr;
        gap: 1rem;
      }
      
      .post-actions {
        justify-content: center;
      }
    }
  </style>
</head>
<body>

  <!-- Header -->
  <header>
    <div class="container">
      <nav>
        <a href="index.php" class="logo">rama.manansyah</a>
        <div class="nav-links">
          <a href="index.php#home">Beranda</a>
          <a href="index.php#blog">Blog</a>
          <a href="logout.php">Logout</a>
        </div>
      </nav>
    </div>
  </header>

  <!-- Admin Blog Section -->
  <section class="admin-blog">
    <div class="container">
      <!-- Admin Header -->
      <div class="admin-header">
        <h1>Kelola Blog</h1>
        <div class="admin-actions">
          <span>Selamat datang, <?= htmlspecialchars($_SESSION['user']['fullname']) ?></span>
          <a href="index.php#blog" class="btn btn-outline">Lihat Blog</a>
          <a href="logout.php" class="btn btn-primary">Logout</a>
        </div>
      </div>

      <!-- Success/Error Messages -->
      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
          <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
      <?php endif; ?>

      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
          <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <!-- Blog Form -->
      <div class="blog-form">
        <h3><?= $edit_post ? 'Edit Artikel' : 'Tambah Artikel Baru' ?></h3>
        <form action="save_post.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="post_id" value="<?= $edit_post ? $edit_post['id'] : '' ?>">
          
          <div class="form-row">
            <div class="form-group">
              <label for="title">Judul Artikel</label>
              <input type="text" id="title" name="title" class="form-control" 
                     value="<?= $edit_post ? htmlspecialchars($edit_post['title']) : '' ?>" 
                     placeholder="Masukkan judul artikel..." required>
            </div>
            <div class="form-group">
              <label for="image">Gambar Artikel</label>
              <input type="file" id="image" name="image" class="form-control" accept="image/*">
              <?php if ($edit_post && $edit_post['image']): ?>
                <small style="color: #718096; margin-top: 0.5rem; display: block;">
                  Gambar saat ini: <?= basename($edit_post['image']) ?>
                </small>
              <?php endif; ?>
            </div>
          </div>
          
          <div class="form-group">
            <label for="excerpt">Ringkasan Artikel</label>
            <textarea id="excerpt" name="excerpt" class="form-control" 
                      placeholder="Tulis ringkasan singkat artikel..." required><?= $edit_post ? htmlspecialchars($edit_post['excerpt']) : '' ?></textarea>
          </div>
          
          <div class="form-group">
            <label for="content">Konten Artikel</label>
            <textarea id="content" name="content" class="form-control" 
                      placeholder="Tulis konten lengkap artikel..." 
                      style="min-height: 200px;" required><?= $edit_post ? htmlspecialchars($edit_post['content']) : '' ?></textarea>
          </div>
          
          <div style="display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">
              <?= $edit_post ? 'Update Artikel' : 'Simpan Artikel' ?>
            </button>
            <?php if ($edit_post): ?>
              <a href="admin-blog.php" class="btn btn-outline">Batal Edit</a>
            <?php endif; ?>
          </div>
        </form>
      </div>

      <!-- Posts Table -->
      <div class="posts-table">
        <div class="table-header">
          <h3>Daftar Artikel (<?= count($blog_posts) ?> artikel)</h3>
        </div>
        
        <div class="posts-list">
          <?php if (count($blog_posts) > 0): ?>
            <?php foreach ($blog_posts as $post): ?>
              <div class="post-item">
                <div class="post-image <?= $post['image'] ? '' : 'no-image' ?>">
                  <?php if ($post['image']): ?>
                    <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                  <?php else: ?>
                    <i class="fas fa-image"></i>
                  <?php endif; ?>
                </div>
                
                <div class="post-info">
                  <h4><?= htmlspecialchars($post['title']) ?></h4>
                  <div class="post-meta">
                    <i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($post['created_at'])) ?>
                    <span style="margin: 0 0.5rem;">•</span>
                    <i class="fas fa-user"></i> <?= htmlspecialchars($post['author']) ?>
                  </div>
                  <div class="post-excerpt">
                    <?= htmlspecialchars(substr($post['excerpt'], 0, 100)) ?>...
                  </div>
                </div>
                
                <div class="post-actions">
                  <a href="blog-detail.php?id=<?= $post['id'] ?>" class="action-btn edit-btn" title="Lihat">
                    <i class="fas fa-eye"></i>
                  </a>
                  <a href="admin-blog.php?edit=<?= $post['id'] ?>" class="action-btn edit-btn" title="Edit">
                    <i class="fas fa-edit"></i>
                  </a>
                  <a href="admin-blog.php?delete=<?= $post['id'] ?>" 
                     class="action-btn delete-btn" 
                     title="Hapus"
                     onclick="return confirm('Yakin ingin menghapus artikel ini?')">
                    <i class="fas fa-trash"></i>
                  </a>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="empty-state">
              <i class="fas fa-newspaper"></i>
              <h4>Belum Ada Artikel</h4>
              <p>Mulai dengan menambahkan artikel pertama Anda di form di atas.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>

  <script src="script.js?v=<?=time()?>"></script>
</body>
</html>
