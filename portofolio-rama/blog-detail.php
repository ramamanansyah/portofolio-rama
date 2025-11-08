<?php
session_start();
require_once 'config.php';

// Get blog post ID from URL
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($post_id <= 0) {
    header("Location: index.php#blog");
    exit();
}

// Fetch the specific blog post
try {
    $stmt = $pdo->prepare("SELECT bp.*, u.fullname as author FROM blog_posts bp LEFT JOIN users u ON bp.user_id = u.id WHERE bp.id = ?");
    $stmt->execute([$post_id]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$post) {
        header("Location: index.php#blog");
        exit();
    }
} catch(PDOException $e) {
    header("Location: index.php#blog");
    exit();
}

// Fetch related posts (excluding current post)
try {
    $stmt = $pdo->prepare("SELECT bp.*, u.fullname as author FROM blog_posts bp LEFT JOIN users u ON bp.user_id = u.id WHERE bp.id != ? ORDER BY bp.created_at DESC LIMIT 3");
    $stmt->execute([$post_id]);
    $related_posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $related_posts = [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($post['title']) ?> | Rama Manansyah Blog</title>
  <link rel="stylesheet" href="styles.css?v=<?=time()?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    .blog-detail {
      padding: 120px 0 80px;
      background: #f8fafc;
    }
    
    .blog-header {
      text-align: center;
      margin-bottom: 3rem;
    }
    
    .blog-header h1 {
      font-size: 2.5rem;
      color: #2d3748;
      margin-bottom: 1rem;
      line-height: 1.2;
    }
    
    .blog-meta-detail {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 2rem;
      color: #718096;
      font-size: 0.95rem;
      margin-bottom: 2rem;
    }
    
    .blog-meta-detail span {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .blog-meta-detail i {
      color: #4A90E2;
    }
    
    .blog-featured-image {
      margin-bottom: 3rem;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
      background: #e2e8f0;
      position: relative;
      width: 100%;
      height: 0;
      padding-bottom: 35%;
    }
    
    .blog-featured-image img {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: contain;
      object-position: center;
      display: block;
      background: #e2e8f0;
    }
    
    .blog-content-wrapper {
      background: white;
      padding: 3rem;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      margin-bottom: 4rem;
    }
    
    .blog-content-text {
      font-size: 1.1rem;
      line-height: 1.8;
      color: #2d3748;
    }
    
    .blog-content-text p {
      margin-bottom: 1.5rem;
    }
    
    .blog-navigation {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 4rem;
    }
    
    .nav-btn {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.75rem 1.5rem;
      background: #4A90E2;
      color: white;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    
    .nav-btn:hover {
      background: #2c5aa0;
      transform: translateY(-2px);
    }
    
    .related-posts {
      margin-top: 4rem;
    }
    
    .related-posts h3 {
      text-align: center;
      color: #2d3748;
      margin-bottom: 2rem;
      font-size: 1.75rem;
    }
    
    .related-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
    }
    
    @media (max-width: 768px) {
      .blog-header h1 {
        font-size: 2rem;
      }
      
      .blog-meta-detail {
        flex-direction: column;
        gap: 1rem;
      }
      
      .blog-content-wrapper {
        padding: 2rem;
      }
      
      .blog-navigation {
        flex-direction: column;
        gap: 1rem;
      }
      
      .related-grid {
        grid-template-columns: 1fr;
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

  <!-- Blog Detail Section -->
  <section class="blog-detail">
    <div class="container">
      <!-- Blog Header -->
      <div class="blog-header">
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        <div class="blog-meta-detail">
          <span>
            <i class="fas fa-user"></i>
            <?= htmlspecialchars($post['author'] ?? 'Admin') ?>
          </span>
          <span>
            <i class="fas fa-calendar"></i>
            <?= date('d M Y', strtotime($post['created_at'])) ?>
          </span>
          <span>
            <i class="fas fa-clock"></i>
            <?= ceil(str_word_count(strip_tags($post['content'])) / 200) ?> min baca
          </span>
        </div>
      </div>

      <!-- Featured Image -->
      <?php if ($post['image']): ?>
        <div class="blog-featured-image">
          <img src="<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" />
        </div>
      <?php endif; ?>

      <!-- Blog Content -->
      <div class="blog-content-wrapper">
        <div class="blog-content-text">
          <?= nl2br(htmlspecialchars($post['content'])) ?>
        </div>
      </div>

      <!-- Navigation -->
      <div class="blog-navigation">
        <a href="index.php#blog" class="nav-btn">
          <i class="fas fa-arrow-left"></i>
          Kembali ke Blog
        </a>
        <a href="index.php#contact" class="nav-btn">
          Hubungi Saya
          <i class="fas fa-arrow-right"></i>
        </a>
      </div>

      <!-- Related Posts -->
      <?php if (count($related_posts) > 0): ?>
        <div class="related-posts">
          <h3>Artikel Terkait</h3>
          <div class="related-grid">
            <?php foreach ($related_posts as $related): ?>
              <article class="blog-card">
                <?php if ($related['image']): ?>
                  <div class="blog-image">
                    <img src="<?= htmlspecialchars($related['image']) ?>" alt="<?= htmlspecialchars($related['title']) ?>" />
                    <div class="blog-overlay">
                      <span class="blog-date">
                        <i class="fas fa-calendar"></i>
                        <?= date('d M Y', strtotime($related['created_at'])) ?>
                      </span>
                    </div>
                  </div>
                <?php endif; ?>
                
                <div class="blog-content">
                  <h4 class="blog-title"><?= htmlspecialchars($related['title']) ?></h4>
                  <p class="blog-excerpt"><?= htmlspecialchars(substr($related['excerpt'], 0, 100)) ?>...</p>
                  
                  <div class="blog-meta">
                    <div class="blog-author">
                      <i class="fas fa-user"></i>
                      <span><?= htmlspecialchars($related['author'] ?? 'Admin') ?></span>
                    </div>
                    <a href="blog-detail.php?id=<?= $related['id'] ?>" class="blog-read-more">
                      Baca Selengkapnya
                      <i class="fas fa-arrow-right"></i>
                    </a>
                  </div>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
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
