<?php
session_start();
require_once 'config.php';

// Pagination
$posts_per_page = 6;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $posts_per_page;

// Fetch total posts count
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM blog_posts");
    $stmt->execute();
    $total_posts = $stmt->fetchColumn();
    $total_pages = ceil($total_posts / $posts_per_page);
} catch(PDOException $e) {
    $total_posts = 0;
    $total_pages = 0;
}

// Fetch blog posts with pagination
try {
    $stmt = $pdo->prepare("SELECT bp.*, u.fullname as author FROM blog_posts bp LEFT JOIN users u ON bp.user_id = u.id ORDER BY bp.created_at DESC LIMIT ? OFFSET ?");
    $stmt->execute([$posts_per_page, $offset]);
    $blog_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $blog_posts = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Blog | Rama Manansyah</title>
  <link rel="stylesheet" href="styles.css?v=<?=time()?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    .blog-page {
      padding: 120px 0 80px;
      background: #f8fafc;
      min-height: 100vh;
    }
    
    .blog-page-header {
      text-align: center;
      margin-bottom: 4rem;
    }
    
    .blog-page-header h1 {
      font-size: 3rem;
      color: #2d3748;
      margin-bottom: 1rem;
    }
    
    .blog-page-header p {
      font-size: 1.2rem;
      color: #718096;
      max-width: 600px;
      margin: 0 auto;
    }
    
    .pagination {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 0.5rem;
      margin-top: 3rem;
    }
    
    .pagination a,
    .pagination span {
      padding: 0.75rem 1rem;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    
    .pagination a {
      background: white;
      color: #4A90E2;
      border: 1px solid #e2e8f0;
    }
    
    .pagination a:hover {
      background: #4A90E2;
      color: white;
      border-color: #4A90E2;
    }
    
    .pagination .current {
      background: #4A90E2;
      color: white;
      border: 1px solid #4A90E2;
    }
    
    .back-to-home {
      text-align: center;
      margin-bottom: 2rem;
    }
    
    .back-to-home a {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      color: #4A90E2;
      font-weight: 500;
      padding: 0.75rem 1.5rem;
      border: 2px solid #4A90E2;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    
    .back-to-home a:hover {
      background: #4A90E2;
      color: white;
    }
    
    @media (max-width: 768px) {
      .blog-page-header h1 {
        font-size: 2.5rem;
      }
      
      .pagination {
        flex-wrap: wrap;
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
          <a href="index.php#about">Tentang</a>
          <a href="index.php#services">Layanan</a>
          <a href="index.php#portfolio">Proyek</a>
          <a href="index.php#blog">Blog</a>
          <a href="index.php#contact">Kontak</a>
          <?php if (isset($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
            <a href="admin-blog.php">Admin</a>
            <a href="logout.php">Logout</a>
          <?php else: ?>
            <a href="login.php">Login</a>
          <?php endif; ?>
        </div>
      </nav>
    </div>
  </header>

  <!-- Blog Page -->
  <section class="blog-page">
    <div class="container">
      <!-- Back to Home -->
      <div class="back-to-home">
        <a href="index.php#blog">
          <i class="fas fa-arrow-left"></i>
          Kembali ke Beranda
        </a>
      </div>

      <!-- Page Header -->
      <div class="blog-page-header">
        <h1>Blog Programming</h1>
        <p>Kumpulan artikel seputar programming, UI/UX design, dan teknologi terkini untuk menginspirasi dan berbagi pengetahuan</p>
      </div>

      <!-- Blog Posts -->
      <?php if (count($blog_posts) > 0): ?>
        <div class="blog-grid">
          <?php foreach ($blog_posts as $post): ?>
            <article class="blog-card">
              <?php if ($post['image']): ?>
                <div class="blog-image">
                  <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" />
                  <div class="blog-overlay">
                    <span class="blog-date">
                      <i class="fas fa-calendar"></i>
                      <?= date('d M Y', strtotime($post['created_at'])) ?>
                    </span>
                  </div>
                </div>
              <?php endif; ?>
              
              <div class="blog-content">
                <h4 class="blog-title"><?= htmlspecialchars($post['title']) ?></h4>
                <p class="blog-excerpt"><?= htmlspecialchars(substr($post['excerpt'], 0, 120)) ?>...</p>
                
                <div class="blog-meta">
                  <div class="blog-author">
                    <i class="fas fa-user"></i>
                    <span><?= htmlspecialchars($post['author'] ?? 'Admin') ?></span>
                  </div>
                  <a href="blog-detail.php?id=<?= $post['id'] ?>" class="blog-read-more">
                    Baca Selengkapnya
                    <i class="fas fa-arrow-right"></i>
                  </a>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
          <div class="pagination">
            <?php if ($page > 1): ?>
              <a href="?page=<?= $page - 1 ?>">
                <i class="fas fa-chevron-left"></i>
              </a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
              <?php if ($i == $page): ?>
                <span class="current"><?= $i ?></span>
              <?php else: ?>
                <a href="?page=<?= $i ?>"><?= $i ?></a>
              <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($page < $total_pages): ?>
              <a href="?page=<?= $page + 1 ?>">
                <i class="fas fa-chevron-right"></i>
              </a>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <!-- Admin Actions -->
        <?php if (isset($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
        <div class="blog-actions">
          <a href="admin-blog.php" class="btn btn-primary">Kelola Blog</a>
        </div>
        <?php endif; ?>

      <?php else: ?>
        <div class="blog-empty">
          <div class="empty-icon">
            <i class="fas fa-newspaper"></i>
          </div>
          <h4>Belum Ada Artikel</h4>
          <p>Artikel blog sedang dalam persiapan. Nantikan konten menarik seputar programming!</p>
          <?php if (isset($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
            <a href="admin-blog.php" class="btn btn-primary">Tambah Artikel Pertama</a>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="container">
      <p class="footer-text">© 2023 rama manansyah. All rights reserved.</p>
    </div>
  </footer>

  <script src="script.js?v=<?=time()?>"></script>
</body>
</html>
